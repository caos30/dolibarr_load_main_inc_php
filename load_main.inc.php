<?php
/* 
 * Licensed under the GNU GPL v3 or higher (See file gpl-3.0.html)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 * or see http://www.gnu.org/
 * 
 * 
 * * * * * * INSTRUCTIONS TO USE (for module developers)
 * 
 * 	1. include this PHP file in the root of your module
 * 
 * 	2. at the top of any PHP file of your module needing to load main.inc.php put his:
 * 
 * 		include_once('load_main.inc.php');
 * 
 */

// ACTIVATE the ERROR reporting (use only to debug)

//	ini_set('display_errors',1);ini_set('display_startup_errors',1);ini_set('error_reporting', E_ALL);

// 1. try to get the location of main.inc.php from PHYSICAL TEXT FILE

	if (file_exists("../load_main_inc_php")){
		$path = @file_get_contents("../load_main_inc_php");
		if (file_exists($path) && @include($path)) return;
	}

// 2. Try into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)

	$path='';
	
	if (!empty($_SERVER["CONTEXT_DOCUMENT_ROOT"]) && file_exists($_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php")){
		if (@include($_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php")) $path = $_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php";
	}

// 3. Try into web root detected using web root calculated from SCRIPT_FILENAME
	if ($path==''){
		$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME']; $tmp2 = realpath(__FILE__); $i = strlen($tmp) - 1; $j = strlen($tmp2) - 1;
		while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) {
			$i--; $j--;
		}
		if ($path=='' && $i > 0 && file_exists(substr($tmp, 0, ($i + 1))."/main.inc.php")) {
			if (@include(substr($tmp, 0, ($i + 1))."/main.inc.php")) $path = substr($tmp, 0, ($i + 1))."/main.inc.php";
		}
		if ($path=='' && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php")) {
			if (@include(dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php")) $path = dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php";
		}
	}

// 4. try to find main.inc.php in the USUAL LOCATIONS

	if ($path=='' && file_exists("../main.inc.php")){
		if (@include("../main.inc.php")) $path = "../main.inc.php";
	}
	if ($path=='' && file_exists("../../main.inc.php")){
		if (@include("../../main.inc.php")) $path = "../../main.inc.php";
	}
	if ($path=='' && file_exists("../../../main.inc.php")){
		if (@include("../../../main.inc.php")) $path = "../../../main.inc.php";
	}
	if ($path=='' && file_exists("../../../../main.inc.php")){
		if (@include("../../../../main.inc.php")) $path = "../../../../main.inc.php";
	}

// 5. try to find main.inc.php in the LOCATIONS used by some providers

	// Bitnami over AWS
	if ($path=='' && file_exists("/opt/bitnami/dolibarr/htdocs/main.inc.php")){
		if (@include("/opt/bitnami/dolibarr/htdocs/main.inc.php")) $path = "/opt/bitnami/dolibarr/htdocs/main.inc.php";
	}

// 6. last try, with the PATH passed from the <FORM> in this script to let the user indicate the path

	if ($path=='' && !empty($_POST['main_inc_php_path']) && file_exists($_POST['main_inc_php_path'])){
		
		define('NOCSRFCHECK',1); // this disable for this unique call the check of the CSRF security token!
		
		if (@include($_POST['main_inc_php_path'])) $path = $_POST['main_inc_php_path'];
	}


// if we accomplished to load main.inc.php file then save it and return
	
	if ($path!=''){
	
		// if the load was not successful then we empty the path from this file
		@file_put_contents("../load_main_inc_php",$path);
		
		return;
	}

// we did not accomplished to load the main.inc.php
// so we request help to user rendering a form to let she to indicate the PATH 

?>
<!doctype html>
<html lang="ca">
	<head>
		<meta charset="utf-8">
		<meta name="robots" content="noindex,nofollow">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body style='font-family:sans-serif;font-size:1.2em;'>
		<form method='post' style='display:block;text-align:center;width:90%;max-width:500px;margin:2em auto;'>
			<h3 style='color:red;'>Dolibarr <u>main.inc.php</u><br />not found in the usual locations</h3>
			<p><br />Please, specify absolute path of that file:</p>
			<p><input type='text' name='main_inc_php_path' style='width:100%;font-size:1.2em;padding:0.5em 0.7em;' placeholder='/var/www/htdocs/main.inc.php'
					  value='<?= !empty($_POST['main_inc_php_path']) ? $_POST['main_inc_php_path'] : '' ?>' /></p>
		    <?php if (!empty($_POST['main_inc_php_path'])){ ?>
				<p style='color:red;'>This path also failed to load the file <u>main.inc.php</u>.</p>
			<?php } ?>
			<p><br /><button type='submit' style='font-size:1.2em;padding:0.5em 1.5em;cursor:pointer;'>✅ &nbsp; Check this location</button></p>
			<p style='opacity:0.5;font-size:0.9em;'><br />It's normal for the Dolibarr core files location to vary depending on the type of installation. 
			Some Dolibarr hosting providers use custom installations, which may place the core in a different path 
			than the one you'd encounter if you installed Dolibarr manually.</p>
		</form>
	</body>
</html>
<?php	die();
		
