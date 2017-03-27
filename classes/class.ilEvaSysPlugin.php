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

include_once("./Services/UIComponent/classes/class.ilUserInterfaceHookPlugin.php");

/**
 * EvaSys plugin. This class only returns the internal plugin name.
 * It must correspond to the directory the plugin is located.
 *
 * @author Alex Killing <killing@leifos.de>
 * @author Electric Paper <info@electricpaper.de>
 *
 * @version $Id$
 */
class ilEvaSysPlugin extends ilUserInterfaceHookPlugin
{
	function getPluginName()
	{
		return "EvaSys";
	}
}

?>
