<?php
/**
 * Get value from GET variable or return default value.
 *
 * @param string $key     to look for
 * @param mixed  $default value to set if key does not exists
 *
 * @return mixed value from GET or the default value
 */
function getGet($key, $default = null)
{
    return isset($_GET[$key])
        ? $_GET[$key]
        : $default;
}

/**
 * Get value from POST variable or return default value.
 *
 * @param string $key     to look for
 * @param mixed  $default value to set if key does not exists
 *
 * @return mixed value from POST or the default value
 */
function getPost($key, $default = null)
{
    return isset($_POST[$key])
        ? $_POST[$key]
        : $default;
}

/**
 * Sanitize value for output in view.
 *
 * @param string $value to sanitize
 *
 * @return string beeing sanitized
 */
function esc($value)
{
    return htmlentities($value);
}

/**
* Sanitize data
*/
function clean($data) {
    if ($data && $data != "") {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlentities($data);
    }

    return $data;
}
