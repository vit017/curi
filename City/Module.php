<?php


namespace City;

use Lang\Module as Lang;
use Exception\ItemsException;

class Module extends \CUriObject
{
    const CACHE_DIR = __DIR__ . DIRECTORY_SEPARATOR . 'cache';
    const CACHE_ITEMS_FILE = self::CACHE_DIR . DIRECTORY_SEPARATOR . 'elements.dat';
    const SETTINGS_FILE = __DIR__ . DIRECTORY_SEPARATOR . 'settings.ini';

    const IBLOCK_TYPE = 'cities';

    const COOKIE_TIME = 3600 * 24 * 7;

    protected static $_settings = [];
    protected static $_fields = [
        'id' => 0,
        'code' => '',
        'in_uri' => false,
        'list' => [],
        'iblocks' => [],
        'items' => [],
    ];


    protected static function check_settings(array $settings)
    {
        $position = (int)$settings['uri_position'];
        $add_in_uri = (bool)$settings['add_in_uri'];

        if ($add_in_uri && !$position)
            return 'uri_position';

        return true;
    }

    public static function init()
    {
        self::load_list();
        self::load_items();
        self::load_code();
        self::load_id(self::code());
    }

    public static function disable()
    {
        setcookie(self::cookie_var(), '', 0, '/');
    }

    public static function enable()
    {
        if (!self::get_from_cookie())
            setcookie(self::cookie_var(), self::code(), time() + self::COOKIE_TIME, '/');
    }

    public static function load_id($code)
    {
        if (!array_key_exists($code, self::items()))
            throw new ItemsException();

        $id = self::items()[$code]['id'];

        self::field('id', $id);
    }

    public static function cache($data = null)
    {
        if (!func_num_args())
            return unserialize(file_get_contents(self::CACHE_ITEMS_FILE));

        return (false !== file_put_contents(self::CACHE_ITEMS_FILE, serialize($data)));
    }

    public static function load_items()
    {
        $all_items = self::cache();
        $list = self::list();
        $iblock_id = $list[Lang::code()];

        if (!array_key_exists($iblock_id, $all_items))
            throw new ItemsException();

        self::field('items', $all_items[$iblock_id]);
    }
}