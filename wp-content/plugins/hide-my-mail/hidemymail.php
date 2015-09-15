<?php
/**
 * Plugin Name: Hide My Mail
 * Plugin URI: http://hmm.wordpress.pdkwebs.nl
 * Description: Hide all Email Addresses from bots by displaying it with JavaScript and Unicode.
 * Version: 1.2
 * Author: Patrick de Koning
 * Author URI: http://www.pcdekoning.nl/wordpress-plugins
 * License: GPL2
 */

/*  Copyright 2014  Patrick de Koning  (email : hmm@wordpress.pdkwebs.nl)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function hmm_convert($emailMatch) {
	$unicodeJS = "<script type='text/javascript'>document.write('";
	for($i=0; $i<strlen($emailMatch); $i++) {
		if($emailMatch[$i] == '!') { $unicodeJS .= '\u0021'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '"') { $unicodeJS .= '\u0022'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '#') { $unicodeJS .= '\u0023'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '$') { $unicodeJS .= '\u0024'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '%') { $unicodeJS .= '\u0025'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '&') { $unicodeJS .= '\u0026'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '\'') { $unicodeJS .= '\u0027'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '(') { $unicodeJS .= '\u0028'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == ')') { $unicodeJS .= '\u0029'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '*') { $unicodeJS .= '\u002A'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '+') { $unicodeJS .= '\u002B'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == ',') { $unicodeJS .= '\u002C'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '-') { $unicodeJS .= '\u002D'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '.') { $unicodeJS .= '\u002E'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '/') { $unicodeJS .= '\u002f'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '0') { $unicodeJS .= '\u0030'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '1') { $unicodeJS .= '\u0031'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '2') { $unicodeJS .= '\u0032'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '3') { $unicodeJS .= '\u0033'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '4') { $unicodeJS .= '\u0034'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '5') { $unicodeJS .= '\u0035'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '6') { $unicodeJS .= '\u0036'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '7') { $unicodeJS .= '\u0037'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '8') { $unicodeJS .= '\u0038'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '9') { $unicodeJS .= '\u0039'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == ':') { $unicodeJS .= '\u003A'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == ';') { $unicodeJS .= '\u003B'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '<') { $unicodeJS .= '\u003C'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '=') { $unicodeJS .= '\u003D'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '>') { $unicodeJS .= '\u003E'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '?') { $unicodeJS .= '\u003F'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '@') { $unicodeJS .= '\u0040'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'A') { $unicodeJS .= '\u0041'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'B') { $unicodeJS .= '\u0042'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'C') { $unicodeJS .= '\u0043'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'D') { $unicodeJS .= '\u0044'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'E') { $unicodeJS .= '\u0045'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'F') { $unicodeJS .= '\u0046'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'G') { $unicodeJS .= '\u0047'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'H') { $unicodeJS .= '\u0048'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'I') { $unicodeJS .= '\u0049'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'J') { $unicodeJS .= '\u004A'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'K') { $unicodeJS .= '\u004B'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'L') { $unicodeJS .= '\u004C'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'M') { $unicodeJS .= '\u004D'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'N') { $unicodeJS .= '\u004E'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'O') { $unicodeJS .= '\u004F'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'P') { $unicodeJS .= '\u0050'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'Q') { $unicodeJS .= '\u0051'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'R') { $unicodeJS .= '\u0052'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'S') { $unicodeJS .= '\u0053'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'T') { $unicodeJS .= '\u0054'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'U') { $unicodeJS .= '\u0055'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'V') { $unicodeJS .= '\u0056'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'W') { $unicodeJS .= '\u0057'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'X') { $unicodeJS .= '\u0058'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'Y') { $unicodeJS .= '\u0059'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'Z') { $unicodeJS .= '\u005A'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '[') { $unicodeJS .= '\u005B'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '\\') { $unicodeJS .= '\u005C'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == ']') { $unicodeJS .= '\u005D'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '^') { $unicodeJS .= '\u005E'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '_') { $unicodeJS .= '\u005F'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '`') { $unicodeJS .= '\u0060'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'a') { $unicodeJS .= '\u0061'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'b') { $unicodeJS .= '\u0062'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'c') { $unicodeJS .= '\u0063'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'd') { $unicodeJS .= '\u0064'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'e') { $unicodeJS .= '\u0065'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'f') { $unicodeJS .= '\u0066'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'g') { $unicodeJS .= '\u0067'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'h') { $unicodeJS .= '\u0068'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'i') { $unicodeJS .= '\u0069'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'j') { $unicodeJS .= '\u006A'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'k') { $unicodeJS .= '\u006B'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'l') { $unicodeJS .= '\u006C'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'm') { $unicodeJS .= '\u006D'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'n') { $unicodeJS .= '\u006E'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'o') { $unicodeJS .= '\u006F'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'p') { $unicodeJS .= '\u0070'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'q') { $unicodeJS .= '\u0071'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'r') { $unicodeJS .= '\u0072'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 's') { $unicodeJS .= '\u0073'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 't') { $unicodeJS .= '\u0074'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'u') { $unicodeJS .= '\u0075'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'v') { $unicodeJS .= '\u0076'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'w') { $unicodeJS .= '\u0077'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'x') { $unicodeJS .= '\u0078'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'y') { $unicodeJS .= '\u0079'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == 'z') { $unicodeJS .= '\u007A'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '{') { $unicodeJS .= '\u007B'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '|') { $unicodeJS .= '\u007C'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '}') { $unicodeJS .= '\u007D'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else if($emailMatch[$i] == '~') { $unicodeJS .= '\u007E'; if(rand(0,1) == 1) { $unicodeJS .= '\',\''; }}
		else { $unicodeJS .= $emailMatch[$i]; }
	}
	$unicodeJS .= "');</script>";
	return $unicodeJS;
}

function hmm_main($text) {
	$pregMatch = preg_match_all('/((<a)?.*(href="mailto:)?[a-z][a-z0-9_.-\/]*@[^\s\"\)\?<>]+\.[a-z]{2,6}(">)?)/i', $text, $emailMatches);
	foreach($emailMatches[1] as $emailMatch) {
		$text = str_replace($emailMatch, hmm_convert($emailMatch), $text);
	}
	return $text;
}

add_filter('the_content', 'hmm_main', 99);
add_filter('widget_text', 'hmm_main', 99);
?>
