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

class ilEvaSysLogger
{
	private $logFileHandle = NULL;

	public function  __construct($p_sLogFilePath)
	{
		if(!is_null($p_sLogFilePath))
		{
			try
			{
				$this->logFileHandle = fopen($p_sLogFilePath, "a");
			}
			catch(Exception $e)
			{ }
		}
	}

	public function logMsg($msg = '')
	{
		if($this->logFileHandle)
		{
			fwrite($this->logFileHandle, strftime("%b %d %H:%M:%S", time()) .
				" - " . $msg . "\n" );
		}
	}
}
?>
