<?php
/**
* Session
*/
if (session_status() == PHP_SESSION_NONE) {
    // Enable output buffering
    ob_start();
    // Start session
    session_start();
}

/**
 * Set error reporting.
 */
error_reporting(E_ALL);           // Report all type of errors
ini_set("display_errors", 1);     // Display all errors

/**
* Default timezone
*/
date_default_timezone_set('Europe/Madrid');

/**
* Locale. Check that locale install on server.
*/
setlocale(LC_ALL, "es_ES.utf8");
// locale_set_default("es_ES.utf8");

/**
 * Default exception handler.
 */
set_exception_handler(function ($e) {
    echo "Uncaught exception: <p>"
        . $e->getMessage()
        . "</p><p>Code: "
        . $e->getCode()
        . "</p><pre>"
        . $e->getTraceAsString()
        . "</pre>";
});

/**
 * Database details.
 */

/** 
 * Local 
 */
$databaseConfig = [
    "dsn"      => "mysql:host=localhost;dbname=tecnikanto;",
    "login"    => "root",
    "password" => "0098",
    "options"  => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
];


/**
 * SMTP user pass
 */
$smtpUser = "";
$smtpPass = "";
