<?php
@session_start();

define('PRE_SALT', 'oTg2w4S5rykFUe2r0');
define('POST_SALT', 'Fzgin5vq88j085oVz');

function logout()
{
    @session_start();
    @session_unset();
    @session_destroy();
    @session_start();
    unset($_SESSION["user"]);
}

function login($user)
{
    $_SESSION["user"] = $user;
}

function hash512($string, $addSalt = true)
{
    if ($addSalt) {
        return hash("sha512", PRE_SALT . $string . POST_SALT);
    } else {
        return hash("sha512", $string);
    }
}
