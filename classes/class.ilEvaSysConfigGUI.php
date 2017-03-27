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

include_once("./Services/Component/classes/class.ilPluginConfigGUI.php");

/**
 * EvaSys configuration user interface class
 *
 * @author Alex Killing <killing@leifos.de>
 * @author Electric Paper <info@electricpaper.de>
 *
 * @version $Id$
 */
class ilEvaSysConfigGUI extends ilPluginConfigGUI
{
	/**
	 * Handles all commmands, default is "configure"
	 */
	function performCommand($cmd)
	{
		switch ($cmd)
		{
			default:
				$this->$cmd();
				break;
		}
	}

	/**
	 * Configure screen
	 */
	function configure()
	{
		global $tpl;

		$form = $this->initConfigurationForm();
		$tpl->setContent($form->getHTML());
	}

	//
	// From here on, this is just an example implementation using
	// a standard form (without saving anything)
	//

	/**
	 * Init configuration form.
	 *
	 * @return object form object
	 */
	public function initConfigurationForm()
	{
		global $ilCtrl;

		$pl = $this->getPluginObject();

		include_once("Services/Form/classes/class.ilPropertyFormGUI.php");
		$form = new ilPropertyFormGUI();

		// show block?
		$cb = new ilCheckboxInputGUI($pl->txt("show_block"), "show_block");
		$cb->setValue(1);
		$cb->setChecked($this->getConfigValue('show_block', false));
		$form->addItem($cb);

		// EvaSys server
		$evasys_server = new ilTextInputGUI($pl->txt("evasys_server"),
			"evasys_server");
		$evasys_server->setRequired(true);
		$evasys_server->setMaxLength(255);
		$evasys_server->setSize(60);
		$evasys_server->setValue($this->getConfigValue('evasys_server'));
		$form->addItem($evasys_server);

		// EvaSys login path
		$evasys_login_path = new ilTextInputGUI($pl->txt("evasys_login_path"),
			"evasys_login_path");
		$evasys_login_path->setRequired(true);
		$evasys_login_path->setMaxLength(255);
		$evasys_login_path->setSize(60);
		$evasys_login_path->setValue(
			$this->getConfigValue('evasys_login_path'));
		$form->addItem($evasys_login_path);

		// EvaSys SOAP user
		$evasys_soap_user = new ilTextInputGUI($pl->txt("evasys_soap_user"),
			"evasys_soap_user");
		$evasys_soap_user->setRequired(true);
		$evasys_soap_user->setMaxLength(255);
		$evasys_soap_user->setSize(60);
		$evasys_soap_user->setValue($this->getConfigValue('evasys_soap_user'));
		$form->addItem($evasys_soap_user);

		// EvaSys SOAP password
		$evasys_soap_password = new ilTextInputGUI(
			$pl->txt("evasys_soap_password"), "evasys_soap_password");
		$evasys_soap_password->setRequired(true);
		$evasys_soap_password->setInputType('password');
		$evasys_soap_password->setMaxLength(255);
		$evasys_soap_password->setSize(60);
		$evasys_soap_password->setValue(
			$this->getConfigValue('evasys_soap_password'));
		$form->addItem($evasys_soap_password);

		// Connection timeout in seconds
		$evasys_connection_timeout = new ilTextInputGUI(
			$pl->txt("evasys_connection_timeout"), "evasys_connection_timeout");
		$evasys_connection_timeout->setRequired(true);
		$evasys_connection_timeout->setMaxLength(255);
		$evasys_connection_timeout->setSize(60);
		$evasys_connection_timeout->setValue(
			$this->getConfigValue('evasys_connection_timeout'));
		$form->addItem($evasys_connection_timeout);

		// Logging
		$evasys_logging = new ilCheckboxInputGUI(
			$pl->txt("evasys_logging"), "evasys_logging");
		$evasys_logging->setValue(1);
		$evasys_logging->setChecked($this->getConfigValue('evasys_logging', false));
		$form->addItem($evasys_logging);

		// Log file (absolute path to the current log file)
		$evasys_logfile_path = new ilTextInputGUI(
			$pl->txt("evasys_logfile_path"), "evasys_logfile_path");
		$evasys_logfile_path->setRequired(true);
		$evasys_logfile_path->setMaxLength(255);
		$evasys_logfile_path->setSize(60);
		$evasys_logfile_path->setValue(
			$this->getConfigValue('evasys_logfile_path'));
		$form->addItem($evasys_logfile_path);

		$form->addCommandButton("save", $pl->txt("save"));

		$form->setTitle($pl->txt("evasys_configuration"));
		$form->setFormAction($ilCtrl->getFormAction($this));

		return $form;
	}

	/**
	 * Save form input (currently does not save anything to db)
	 *
	 */
	public function save()
	{
		global $tpl, $ilCtrl;

		$pl = $this->getPluginObject();

		$form = $this->initConfigurationForm();
		if ($form->checkInput())
		{
			$evasys_server = $form->getInput("evasys_server");
			$evasys_login_path = $form->getInput("evasys_login_path");
			$evasys_soap_user = $form->getInput("evasys_soap_user");
			$evasys_soap_password = $form->getInput("evasys_soap_password");
			$evasys_connection_timeout = $form->getInput("evasys_connection_timeout");
			$evasys_logging = $form->getInput("evasys_logging");
			$evasys_logfile_path = $form->getInput("evasys_logfile_path");
			$sb = $form->getInput("show_block");

			$this->storeConfigValue('evasys_server', $evasys_server);
			$this->storeConfigValue('evasys_login_path', $evasys_login_path);
			$this->storeConfigValue('evasys_soap_user', $evasys_soap_user);
			$this->storeConfigValue('evasys_soap_password', $evasys_soap_password);
			$this->storeConfigValue('evasys_connection_timeout', $evasys_connection_timeout);
			$this->storeConfigValue('evasys_logging', $evasys_logging);
			$this->storeConfigValue('evasys_logfile_path', $evasys_logfile_path);
			$this->storeConfigValue('show_block', $sb);

			ilUtil::sendSuccess($pl->txt("configuration_saved"), true);
			$ilCtrl->redirect($this, "configure");
		}
		else
		{
			$form->setValuesByPost();
			$tpl->setContent($form->getHtml());
		}
	}

	protected function storeConfigValue($name, $value)
	{
		global $ilDB;

		if($this->getConfigValue($name, false) === false)
			$sql = "INSERT INTO `ui_uihk_evasys_config` (`name`,`value`)
					VALUES (
						{$ilDB->quote($name, "text")},
						{$ilDB->quote($value, "text")})";
		else
			$sql = "UPDATE `ui_uihk_evasys_config`
					SET `value` = {$ilDB->quote($value, "text")}
					WHERE `name` = {$ilDB->quote($name, "text")}";

		return $ilDB->manipulate($sql);
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
