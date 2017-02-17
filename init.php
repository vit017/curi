<?php

spl_autoload_register(function ($class_name) {
    include_once str_replace('\\', '/', __DIR__ . DIRECTORY_SEPARATOR . $class_name . '.php');
});

foreach (Storage::$ar_disable_uri as $uri) {
    if (0 === strpos($_SERVER['REQUEST_URI'], $uri))
        return;
}

require_once $_SERVER["DOCUMENT_ROOT"] . '/include/CUri/functions.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/include/CUri/Lang/init.php';
require_once $_SERVER["DOCUMENT_ROOT"] . '/include/CUri/City/init.php';

$site_prefix = Storage::get_site_prefix();
define('SITE_PREFIX', $site_prefix);

if ($site_prefix && ($uri = Storage::correct_uri($_SERVER['REQUEST_URI']))) {
    header('Location: '.$uri);
    exit();
}
unset($site_prefix);

if ($file_path = Storage::find_file($_SERVER['REQUEST_URI'])) {
    include_once $file_path;
    exit();
}

include_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . Storage::URL_REWRITE_FILE);
if (!defined(Storage::URL_REWRITE)) {
    if (false === Storage::change_url_rewrite())
        throw new Exception\StorageException();
}