<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/include/CUri/functions.php';

foreach (Storage::$ar_disable_uri as $uri) {
    if (0 === strpos($_SERVER['REQUEST_URI'], $uri))
        return;
}

require_once $_SERVER["DOCUMENT_ROOT"] . '/include/CUri/Lang/init.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/include/CUri/City/init.php';

$site_prefix = Storage::get_site_prefix();
define('SITE_PREFIX', $site_prefix);

if ($site_prefix && ($uri = Storage::correct_uri($_SERVER['REQUEST_URI']))) {
    header('Location: '.$uri);
    exit();
}
unset($site_prefix);

if (defined('AUTH_404') && ($file_path = Storage::find_file($_SERVER['REQUEST_URI']))) {
    $_SERVER["REAL_FILE_PATH"] = $file_path;
    include_once $file_path;
    die();
}

include_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . Storage::URL_REWRITE_FILE);
if (!defined(Storage::URL_REWRITE)) {
    if (false === Storage::change_url_rewrite())
        throw new Exception\StorageException();
}