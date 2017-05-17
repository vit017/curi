<?php


namespace CUri\lang;

class Module
{

    private static $_instance = null;
    private $current = null;
    private $config = [];
    private $default = 'ru';
    private $iblockID = 0;
    private $data = [];
    private $list = [];
    private $translates = [];

    private function __construct()
    {
        $this->config = $this->config();
        $this->data = $this->read();
        $code = $this->define();
        $this->list = $this->data[$this->iblockID];
        $this->current = $this->list[$code];
        $this->translates(true);
    }

    private function __clone()
    {

    }

    public function __get($key)
    {
        $data = $this->current;
        $upperKey = strtoupper($key);
        if (array_key_exists($upperKey, $data)) {
            return $data[$upperKey];
        } elseif (array_key_exists($upperKey, $data['PROPERTIES'])) {
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

    public function current()
    {
        return $this->current;
    }

    public function default()
    {
        return $this->default;
    }

    public function define()
    {
        $code = $this->default();//in uri, in cookie
        $iblocks = $this->info('iblocks')['langs'];

        if (array_key_exists($code, $iblocks)) {
            $this->iblockID = $iblocks[$code];
            return $code;
        }

        return false;
    }

    private function data()
    {
        return $this->data;
    }

    public function list()
    {
        return $this->list;
    }

    public static function instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function store(array $data = [])
    {
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

    public function tr(string $text)
    {
        $translates = $this->translates();

        if (array_key_exists($text, $translates)) {
            return $translates[$text];
        }

        return $text;
    }

    private function translates($set = false)
    {
        if (!$set) {
            return $this->translates;
        }

        $path = __DIR__ . DIRECTORY_SEPARATOR . 'translates' . DIRECTORY_SEPARATOR . $this->code . DIRECTORY_SEPARATOR . 'tr.ini';
        $this->translates = parse_ini_file($path);
    }

}