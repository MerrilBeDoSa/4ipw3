<?php
require_once "../app/config/app.php";
require_once "../app/config/model.php";

/**
 * include all MVC PHP files
 */


/**
 * option de présention
 */
if (isset($_GET['theme'])) {
    setcookie('theme', $_GET['theme'], time() + 30 * 24 * 60 * 60, '/');
    $_COOKIE['theme'] = $_GET['theme'];
}
if (isset($_GET['font'])) {
    setcookie('font', $_GET['font'], time() + 30 * 24 * 60 * 60, '/');
    $_COOKIE['font'] = $_GET['font'];
}




function include_mvc_php_files()
{
	// include all PHP files
	foreach ( array( 'model', 'view', 'controller') as $dir )
	{
		$file_a = scandir(ROOT_DIR.$dir);

		foreach ( $file_a as $file)
		{
			if( substr( $file, -4, 4 ) != ".php" ) continue;
			// echo($file."\n");
			require_once( ROOT_DIR.$dir.DIRECTORY_SEPARATOR.$file );
		}
	}

}

///////////////////////////////////////////////////////////////////////////////

// ROUTER
session_start();

include_mvc_php_files();

// select page to load,  function to call
// $page = @$_GET['page'] ?: 'home';
// making router more universal => using superglobal REQUEST instead of POST or GET
$page = @$_REQUEST['page'] ?: 'home';
$main = "main_{$page}";
echo $main();

