<?php


require_once("functions.php");
//die('12');
function lRequired($level = 1) {
    if (!\Login\User::isLoggedIn()) {
        header("Location: /login.html");
    }
    $user = \Login\User::getCurrent();
    if (!($user->user_level >= $level)) { // User Level too low to access
        define("401b",TRUE);
    }
}
function logMsg($str) {
	
	// Let's make sure the file exists and is writable first.
	if (debugMode) {
		$filename = debugLog;
		$bt = debug_backtrace();
		$caller = array_shift($bt);
		$send = "\n------\nCaller: \n".print_r($caller,1)."\n\nLog: \n".$str."\n------\n";
		$fp = fopen($filename, 'a');
		fwrite($fp, $send);
		fclose($fp);
	}
}
	

function flipBitwise($a) {
    return -($a-1);
}

function code($txt) {
    return "<code style='white-space: pre;'>".$txt."</code>";
}
function dCode($txt) {
	if (is_object($txt) || is_array($txt))
		die(code(print_r($txt,1)));
	else
		die(code($txt));
}
function collapse($btn,$txt) {
    $id = 'c'.rand(10000,20000).time();   
    return "<p><a class='btn btn-primary' data-toggle='collapse' href='#{$id}' role='button' aria-expanded='false' aria-controls='{$id}'>{$btn}</a></p><div class='collapse' id='{$id}'>{$txt}</div>";
} 
/**    AUTOLOADER ***/
/**
 * An example of a project-specific implementation.
 *
 * After registering this autoload function with SPL, the following line
 * would cause the function to attempt to load the \Foo\Bar\Baz\Qux class
 * from /path/to/project/src/Baz/Qux.php:
 *
 *      new \Foo\Bar\Baz\Qux;
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = '';

    // base directory for the namespace prefix
    $base_dir = HardPath . '/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class);

    // if the file exists, require it
    if (file_exists($file.'.class.php')) {
        require $file.'.class.php';
    } elseif (file_exists($file.'.php')) {
        require_once $file.'.php';
    } elseif (file_exists($base_dir . 'db/'. str_replace('\\', '/', $relative_class).'.php')) {
        require_once $base_dir . 'db/'. str_replace('\\', '/', $relative_class).'.php';
    } else {
		echo "<pre>".print_r(debug_backtrace(),1)."</pre>";
		die('Error AutoLoading: '.$file);
	}
});


/** END AUTOLOADER ***/
