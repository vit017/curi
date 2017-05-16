<?php


namespace CUri\city;

class Module
{

    private static $_instance = null;
    private $_current = null;
    private $_default = 'moskva';
    private $_list = [
        'moskva' => [
            'id' => 315
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