<?php

define('SITE_PREFIX', '');
use City\Module as City;

try {
    City::load_settings();
    City::get_iblocks();
    if (!City::active()) {
        City::disable();
        return;
    }
    City::init();
} catch (Exception $e) {
    dd('internal', 0);
    dd(get_class($e), 0);
    dd($e->getFile(), 0);
    dd($e->getLine(), 0);
    dd($e->getMessage(), 0);
    throw new $e;
}
