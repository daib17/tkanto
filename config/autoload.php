<?php
/**
 * Autoloader for classes.
 *
 * @param string $class the name of the class.
 */
spl_autoload_register(function ($class) {
    $path = __DIR__ . "/../src/classes/{$class}.php";
    if (is_file($path)) {
        include($path);
    }
});
