<?php

define('WWW_ROOT_DIR', dirname(__FILE__));
define ('APPROOT', dirname(WWW_ROOT_DIR));

global $cfg;

$cfg = new StdClass;

$cfg->environment = 'dev';
$cfg->https       = false;
$cfg->baseURI     = '';

// Clean the REQUEST_URI
list($_SERVER['REQUEST_URI']) = explode('?', $_SERVER['REQUEST_URI']);

// Remember REQUEST_URI
$REQUEST_URI = $_SERVER['REQUEST_URI'];

// Try to set relative REDIRECT_URL...
if (strstr($_SERVER['REDIRECT_URL'], dirname($_SERVER['PHP_SELF']))) {
    $_SERVER['REQUEST_URI'] = str_replace(dirname($_SERVER['PHP_SELF']), '', $_SERVER['REDIRECT_URL']);
} else {
    $_SERVER['REQUEST_URI'] = '/';
}

// ... and finaly the `$cfg->baseURI` automatically
$cfg->baseURI = str_replace('@theend', '', str_replace($_SERVER['REQUEST_URI'].'@theend', '', $REQUEST_URI.'@theend'));

// Environment Switch
switch ($cfg->environment) {/*
    case 'live':
        $cfg->https = true;
    break;

    case 'dev':
        $cfg->baseURI = '/any/subdirectory';
    break;
*/}

try {
    require_once '../../../src/run.php';
} catch (Exception $e) {
    $errorLevel = error_reporting();

    if ($errorLevel === E_ALL) {
        print_r($e);
    } elseif ($errorLevel === E_NOTICE) {
        echo "[ERROR]: ".$e->getMessage();
    } else {
        echo "Unexpected error. Sorry.";
    }
}
