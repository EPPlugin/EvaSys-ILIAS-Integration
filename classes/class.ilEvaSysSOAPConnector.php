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

define("PSE_EVASYS_LOGIN_PATH", "/indexstud.php?type=html&user_tan=");

class ilEvaSysSOAPConnector
{
	private $m_oSoapClient;
	private $m_oEvaSysLogger;

	public function  __construct($p_oEvaSysLogger)
	{
		$this->m_oEvaSysLogger = $p_oEvaSysLogger;
		$oClient = new SoapClient($this->getConfigValue('evasys_server'),
					array('trace' => 1,
						'feature' => SOAP_SINGLE_ELEMENT_ARRAYS));
		$header_input = array(	'Login' => $this->getConfigValue('evasys_soap_user'),
								'Password' => $this->getConfigValue('evasys_soap_password'));


		$wsdlName = substr($this->getConfigValue('evasys_server'),
			strrpos($this->getConfigValue('evasys_server'), "/")+1);
		$soapHeaders = new SoapHeader(
		$wsdlName,
		'Header',
		$header_input);

		$oClient->__setSoapHeaders($soapHeaders);
		$this->m_oSoapClient = $oClient;
	}

	public function getSurveyLinksForIliasUser($p_iliasUserEmail, $p_LoginPath)
	{
		$sText = '';
		$oPswds = $this->m_oSoapClient->getPswdsByParticipant($p_iliasUserEmail);

		$passwords = !is_array($oPswds->OnlineSurveyKeys) ?
			array($oPswds->OnlineSurveyKeys) : $oPswds->OnlineSurveyKeys;
		foreach($passwords as $oPswd)
		{
			$sText .= "<a href='" .
			$p_LoginPath .
			PSE_EVASYS_LOGIN_PATH .
			$oPswd->TransactionNumber .
			"' target='_blank'>" .
			$oPswd->CourseName .
			"</a><br />";
		}
		return $sText;
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
