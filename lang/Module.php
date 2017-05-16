<?php


namespace CUri\lang;

class Module
{

    private static $_instance = null;
    private $_current = null;
    private $_default = 'ru';
    private $_list = [
        'ru' => [
            'ru' => 'Русский',
            'en' => 'Английский',
        ],
        'en' => [
            'ru' => 'Russian',
            'en' => 'English',
        ]
    ];

    private function __construct() {
        $this->_current = $this->define();
    }

    private function __clone() {

    }

    public function element()
    {
        return $this->_current;
    }

    public function default()
    {
        return $this->_default;
    }

    public function define()
    {
        return $this->_default;
    }

    public static function instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function elements() {
        return $this->_list;
    }

}