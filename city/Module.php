<?php


namespace CUri\city;

class Module
{

    private static $_instance = null;
    private $_current = null;
    private $_config = [];
    private $_default = 'moskva';
    private $_list = [];

    private function __construct()
    {
        $this->_config = $this->config();
        $this->_list = $this->read();
        $this->_current = $this->define();
    }

    private function __clone()
    {

    }

    public function __get($key) {
        $data = $this->_current;
        $upperKey = strtoupper($key);
        if (array_key_exists($upperKey, $data)) {
            return $data[$upperKey];
        }
        elseif (array_key_exists($upperKey, $data['PROPERTIES'])) {
            return $data['PROPERTIES'][$upperKey];
        }
    }

    public function info(string $key)
    {
        return $this->config()[$key];
    }

    private function config()
    {
        return require __DIR__ . DIRECTORY_SEPARATOR . 'config.php';
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
        return $this->list()[$this->default()];
    }

    public function list()
    {
        return $this->_list;
    }

    public static function instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function elements()
    {
        return $this->_list;
    }

    public function store($data = [])
    {
        $num = func_num_args();

        if (!$num) {
            return $this->read();
        }

        return $this->write($data);
    }

    private function read()
    {
        return unserialize(file_get_contents($this->info('cachePath')));
    }

    private function write($content)
    {
        return file_put_contents($this->info('cachePath'), serialize($content));
    }

}