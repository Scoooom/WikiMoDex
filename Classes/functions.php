<?php
/* Useful functions.
 * This file contains some useful functions for demo.
 * @author : MarkisDev
 * @copyright : https://markis.dev
 */
 
ini_set('display_errors',0);
ini_set('error_reporting',E_ERROR | E_PARSE );
define('GPATH','/home/void/pokevoid-main/./public/images/pokemon/glitch/');

define('basePath','/var/www/void.scooom.xyz/');
define('baseTplPath',basePath.'/tpl/');
define('HardPath',basePath.'Classes/');
define('debugLog',basePath.'/logs/debugLog');
define('debugMode',1);
define('BUILTIN',basePath.'/html/glitch_parsed.json');
define('BUILTINS',basePath.'/html/smitty_parsed.json');
define('BUILTINF',basePath.'/html/smittyf_parsed.json');
define('DTAG','scooom');

# A function to redirect user.
function redirect($url)
{
    if (!headers_sent())
    {    
        header('Location: '.$url);
        exit;
        }
    else
        {  
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>';
        exit;
    }
}

# A function which returns users IP
function client_ip()
{
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
	{
		return $_SERVER['HTTP_X_FORWARDED_FOR'];
	}
	else
	{
		return $_SERVER['REMOTE_ADDR'];
	}
}

# Check user's avatar type
function is_animated($avatar)
{
	$ext = substr($avatar, 0, 2);
	if ($ext == "a_")
	{
		return ".gif";
	}
	else
	{
		return ".png";
	}
}


