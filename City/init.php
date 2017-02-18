<?php

use City\Module as City;

try {
    City::load_settings();
    City::get_iblocks();
    if (!City::active()) {
        City::disable();
    }
    else {
        City::init();
        City::enable();
        if (City::add_in_uri())
            Storage::add(City::class);
    }

} catch (Exception $e) {
    dd('internal', 0);
    dd(get_class($e), 0);
    dd($e->getFile(), 0);
    dd($e->getLine(), 0);
    dd($e->getMessage(), 0);
    throw new $e;
}
