<?php


namespace Lang;

use Exception\ItemsException, Exception\SettingsException, Exception\FieldsException, Exception\IBlockException, Exception\NotFoundMethodException;

class Module
{
    const CACHE_DIR = __DIR__ . DIRECTORY_SEPARATOR . 'cache';
    const CACHE_ITEMS_FILE = 'elements.dat';
    const SETTINGS_FILE = 'settings.ini';
    const IBLOCKS_FILE = 'iblocks.ini';
    const IBLOCK_TYPE = 'langs';

    const COOKIE_TIME = 3600 * 24 * 7;

    private static $_settings = [];
    private static $_fields = [
        'code' => '',
        'in_uri' => false,
        'iblocks' => [],
        'list' => [],
        'items' => [],
    ];

    public static function __callStatic($code, $arguments)
    {
        if (array_key_exists($code, self::$_fields))
            return self::$_fields[$code];
        elseif (array_key_exists($code, self::$_settings))
            return self::$_settings[$code];

        throw new NotFoundMethodException();
    }

    private static function field($code, $val)
    {
        if (!array_key_exists($code, self::$_fields)) {
            throw new FieldsException();
        }

        self::$_fields[$code] = $val;
    }

    private static function check_settings($settings)
    {
        $position = (int)$settings['uri_position'];
        $add_in_uri = (bool)$settings['add_in_uri'];

        if ($add_in_uri && !$position)
            return 'uri_position';

        return true;
    }

    public static function load_settings()
    {
        $settings = parse_ini_file(__DIR__ . DIRECTORY_SEPARATOR . self::SETTINGS_FILE);
        if (true !== ($mess = self::check_settings($settings)))
            throw new SettingsException($mess . ' incorrect in ' . self::class);

        self::$_settings = $settings;
    }

    public static function init()
    {
        self::load_list();
        self::load_code();
        self::load_items();
    }

    public static function disable()
    {
        define('LANGUAGE_CODE', self::default_code());
        setcookie(self::cookie_var(), '', 0, '/');
    }

    public static function enable()
    {
        define('LANGUAGE_CODE', self::code());
        if (!self::get_from_cookie())
            setcookie(self::cookie_var(), self::code(), time() + self::COOKIE_TIME, '/');
    }

    public static function load_iblocks()
    {
        $data = parse_ini_file(dirname(__DIR__) . DIRECTORY_SEPARATOR . self::IBLOCKS_FILE, true);
        if (!check_array($data))
            throw new IBlockException();

        self::field('iblocks', $data);
    }

    public static function load_list()
    {
        $list = [];
        foreach (self::iblocks() as $section => $ar_iblocks)
            $list[$section] = $ar_iblocks[self::IBLOCK_TYPE];

        if (!check_array($list))
            throw new IBlockException();

        self::field('list', $list);
    }

    private static function get_from_uri($uri)
    {
        $ar_uri = explode('/', uri_path($uri));
        if ((array_key_exists(self::uri_position(), $ar_uri) && array_key_exists($ar_uri[self::uri_position()], self::list()))
        ) {
            return $ar_uri[self::uri_position()];
        }

        return false;
    }

    private static function get_from_cookie()
    {
        if (array_key_exists(self::cookie_var(), $_COOKIE) && (array_key_exists($_COOKIE[self::cookie_var()], self::list()))) {
            return $_COOKIE[self::cookie_var()];
        }

        return false;
    }

    public static function load_code()
    {
        if ($code = self::get_from_uri($_SERVER['REQUEST_URI'])) {
            self::field('in_uri', true);
        } elseif ($code = self::get_from_cookie()) {
        } else {
            $code = self::default_code();
        }

        if (!$code)
            throw new ItemsException();

        self::field('code', $code);
    }

    public static function cache($data = null)
    {
        if (!func_num_args())
            return unserialize(file_get_contents(self::CACHE_DIR . DIRECTORY_SEPARATOR . self::CACHE_ITEMS_FILE));

        return (false !== file_put_contents(self::CACHE_DIR . DIRECTORY_SEPARATOR . self::CACHE_ITEMS_FILE, serialize($data)));
    }

    public static function load_items()
    {
        $all_items = self::cache();
        $iblock_id = self::list()[self::code()];

        if (!array_key_exists($iblock_id, $all_items))
            throw new ItemsException();

        self::field('items', $all_items[$iblock_id]);
    }

    public static function iblock_id($type)
    {
        $iblocks = self::iblocks();
        $code = self::active() ? self::code() : self::default_code();

        if (array_key_exists($code, $iblocks) && array_key_exists($type, $iblocks[$code]))
            return $iblocks[$code][$type];

        //throw new IBlockException();
    }
}