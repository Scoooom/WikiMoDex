<?php
ini_set('display_errors',0);
ini_set('error_reporting',E_ALL);
define('basePath','/var/www/void.scooom.xyz/bot/');
define('HardPath',basePath.'Classes/');

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
		die('Error AutoLoading: '.$file."\n");
	}
});


/** END AUTOLOADER ***/
