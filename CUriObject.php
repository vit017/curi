<?php

use Exception\ItemsException, Exception\SettingsException, Exception\FieldsException, Exception\IBlockException, Exception\NotFoundMethodException;

abstract class CUriObject {

    const IBLOCKS_FILE = __DIR__ . DIRECTORY_SEPARATOR . 'iblocks.ini';

    abstract static protected function check_settings(array $settings);
    abstract static protected function cache($data = null);
    abstract static public function load_items();
    abstract static public function init();

    public static function __callStatic($code, $arguments)
    {
        if (array_key_exists($code, static::$_fields))
            return static::$_fields[$code];
        elseif (array_key_exists($code, static::$_settings))
            return static::$_settings[$code];

        throw new NotFoundMethodException();
    }

    protected static function field($code, $val)
    {
        if (!array_key_exists($code, static::$_fields)) {
            throw new FieldsException();
        }

        static::$_fields[$code] = $val;
    }

    public static function load_settings()
    {
        $settings = parse_ini_file(static::SETTINGS_FILE);
        if (true !== ($mess = static::check_settings($settings)))
            throw new SettingsException($mess . ' incorrect in ' . static::class);

        static::$_settings = $settings;
    }

    public static function load_iblocks()
    {
        $data = parse_ini_file(static::IBLOCKS_FILE, true);
        if (!check_array($data))
            throw new IBlockException();

        static::field('iblocks', $data);
    }

    public static function load_list()
    {
        $list = [];
        foreach (static::iblocks() as $section => $ar_iblocks)
            $list[$section] = $ar_iblocks[static::IBLOCK_TYPE];

        if (!check_array($list))
            throw new IBlockException();

        static::field('list', $list);
    }

    protected static function get_from_uri($uri)
    {
        $ar_uri = explode('/', uri_path($uri));
        if ((array_key_exists(static::uri_position(), $ar_uri) && array_key_exists($ar_uri[static::uri_position()], static::list()))
        ) {
            return $ar_uri[static::uri_position()];
        }

        return false;
    }

    protected static function get_from_cookie()
    {
        if (array_key_exists(static::cookie_var(), $_COOKIE) && (array_key_exists($_COOKIE[static::cookie_var()], static::list()))) {
            return $_COOKIE[static::cookie_var()];
        }

        return false;
    }

    public static function load_code()
    {
        if ($code = static::get_from_uri($_SERVER['REQUEST_URI'])) {
            static::field('in_uri', true);
        } elseif ($code = static::get_from_cookie()) {
        } else {
            $code = static::default_code();
        }

        if (!$code)
            throw new ItemsException();

        static::field('code', $code);
    }

    public static function iblock_id($type)
    {
        try {
            return static::iblocks()[static::code()][$type];
        }
        catch (\Exception $e) {
            return null;
        }

        //throw new IBlockException();
    }

}