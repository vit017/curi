<?php

define('SITE_PREFIX', '');
use Lang\Module as Lang;

try {
    Lang::load_settings();
    Lang::get_iblocks();
    if (!Lang::active()) {
        Lang::disable();
        return;
    }
    Lang::init();
} catch (Exception $e) {
    dd('internal', 0);
    dd(get_class($e), 0);
    dd($e->getFile(), 0);
    dd($e->getLine(), 0);
    dd($e->getMessage(), 0);
    throw new $e;
}
