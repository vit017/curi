<?php

use Lang\Module as Lang;

try {
    Lang::load_settings();
    Lang::load_iblocks();

    if (!Lang::active()) {
        Lang::disable();
    }
    else {
        Lang::init();
        Lang::enable();
        if (Lang::add_in_uri())
            Storage::add(Lang::class);
    }
} catch (Exception $e) {
    dd('internal', 0);
    dd(get_class($e), 0);
    dd($e->getFile(), 0);
    dd($e->getLine(), 0);
    dd($e->getMessage(), 0);
    throw new $e;
}
