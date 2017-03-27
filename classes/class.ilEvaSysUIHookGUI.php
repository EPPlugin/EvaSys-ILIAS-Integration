<?php

/*
EvaSys ILIAS Plugin
Copyright (C) 2016  Electric Paper Evaluationssysteme GmbH

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

Contact:
Electric Paper
Evaluationssysteme GmbH
Konrad-Zuse-Allee 13
21337 Lüneburg
Deutschland

E-Mail: info@evasys.de
*/

// error_reporting(E_ALL);

include_once("./Services/UIComponent/classes/class.ilUIHookPluginGUI.php");

/**
 * User interface hook class.
 *
 * This class hooks into the user interface of ILIAS. The main goal is
 * to display a new block on top of the left column on the personal desktop.
 *
 * @author Alex Killing <killing@leifos.de>
 * @author Electric Paper <info@electricpaper.de>
 *
 * @version $Id$
 */
class ilEvaSysUIHookGUI extends ilUIHookPluginGUI
{
	private $oEvaSysLogger = NULL;
	/**
	 * Get html for a user interface area
	 *
	 * @param
	 * @return
	 */

	function getHTML($a_comp, $a_part, $a_par = array())
	{
		// if we are on the personal desktop and the left column is rendered
		if ($a_comp == "Services/PersonalDesktop" && $a_part == "left_column")
		{
			// prepend the HTML of the EvaSys block
			return array("mode" => ilUIHookPluginGUI::PREPEND,
				"html" => $this->getBlockHTML());
		}

		// in all other cases, keep everything as it is
		return array("mode" => ilUIHookPluginGUI::KEEP, "html" => "");
	}

	/**
	 * Get EvaSys block html
	 *
	 * @return string HTML of evasys block
	 */
	function getBlockHTML()
	{
		$ilEvaSysPlugin = new ilEvaSysPlugin();
		$ilEvaSysPlugin->includeClass("class.ilEvaSysLogger.php");
		$ilEvaSysPlugin->includeClass("class.ilEvaSysSOAPConnector.php");

		global $ilUser;
		$pl = $this->getPluginObject();

		if (!$this->getConfigValue('show_block'))
		{
			return;
		}
		define('EVASYS_DEBUG_MODE', $this->getConfigValue('evasys_logging'));

		// Initialize EvaSys logger
		if (EVASYS_DEBUG_MODE)
		{
			$this->oEvaSysLogger = new ilEvaSysLogger($this->getConfigValue('evasys_logfile_path'));
		}
		else
		{
			$this->oEvaSysLogger = new ilEvaSysLogger(NULL);
		}

		// Retrieve EvaSys Surveys
		if(empty($_SESSION['evasys_survey_links']) || EVASYS_DEBUG_MODE)
		{
			$sEmailAddress = $ilUser->getEmail();

			if (!empty($sEmailAddress))
			{
				$this->oEvaSysLogger->logMsg(
					"Retrieving survey links for '". $sEmailAddress . "'");
				$_SESSION['evasys_survey_links'] =
					$this->getSurveysForEmailAddress($sEmailAddress, $pl);
				// $this->oEvaSysLogger->logMsg(print_r($_SESSION['evasys_survey_links'], true));
			}
			else
			{
				$this->oEvaSysLogger->logMsg($pl->txt("evasys_NO_ILIAS_EMAIL_FOUND"));
				$_SESSION['evasys_survey_links'] = $pl->txt("evasys_NOT_CONNECTED");
			}
		}

		$btpl = $pl->getTemplate("tpl.evasys_block.html");

		// output title
		$btpl->setVariable("TITLE", $pl->txt("evasys_surveys"));

		// output user data
		$btpl->setVariable("USER_LAST", $ilUser->getLastname());
		$btpl->setVariable("USER_FIRST", $ilUser->getFirstname());

		$btpl->setVariable("USER_ID", $ilUser->getId());
		$btpl->setVariable("USER_LOGIN", $ilUser->getLogin());

		$btpl->setVariable("USER_EMAIL", $ilUser->getEmail());
		$btpl->setVariable("USER_EVASYS_SURVEYS", $_SESSION['evasys_survey_links']);

		/* TODO: REMOVE THIS
		$this->oEvaSysLogger->logMsg(print_r(get_class_methods($ilUser), true));
		$this->oEvaSysLogger->logMsg(print_r($ilUser), true));
		*/

		return $btpl->get();
	}

	function getSurveysForEmailAddress($p_sIliasUserEmail, $pl)
	{
		try
		{
			// Initialize SOAP client
			$oEvaSysSoapClient = new ilEvaSysSOAPConnector($this->oEvaSysLogger);

			$result = $oEvaSysSoapClient->getSurveyLinksForIliasUser(
				$p_sIliasUserEmail, $this->getConfigValue('evasys_login_path'));
			return $result;
		}
		catch(Exception $fault)
		{
			$sFaultMessage = '';
			$bIsAdmin 		= FALSE;

			global $rbacsystem;
			if($rbacsystem->checkAccess("read", SYSTEM_FOLDER_ID)) {
			   $bIsAdmin = TRUE;
			}

			if ($bIsAdmin)
			{
				if($fault->faultstring != 'ERR_206')
				{
					$sFaultMessage = $pl->txt("evasys_FOLLOWING_ERROR_OCCURED") .
					'<p /><b>' . $fault->faultstring . '</b> ' . $fault->detail;
					$this->oEvaSysLogger->logMsg($sFaultMessage);
				}
				else
				{
					$sFaultMessage = $pl->txt("evasys_CONNECTION_SUCCESSFULLY_TESTED");
				}
			}
			else
			{
				$sFaultMessage = $pl->txt("evasys_NO_ONLINE_SURVEYS");
			}

			return $sFaultMessage;
		}
	}

	protected function getConfigValue($name, $default = '')
	{
		global $ilDB;

		$sql = "SELECT `value`
				FROM `ui_uihk_evasys_config`
				WHERE `name` = {$ilDB->quote($name, "text")}";

		$result = $ilDB->query($sql);
		$row = $ilDB->fetchObject($result);

		if(!$row)
			return $default;
		else
			return $row->value;
	}
}
?>
