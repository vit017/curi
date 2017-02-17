<?php

use Exception\StorageException;

class Storage
{

    const URL_REWRITE_FILE = 'urlrewrite.php';
    const URL_REWRITE = 'CURI_URL_REWRITE';

    private static $_storage = [];
    public static $ar_disable_uri = [
        '/bitrix'
    ];


    public static function add($class_name)
    {
        $position = (int)$class_name::uri_position();
        if (array_key_exists($position, self::$_storage))
            throw new StorageException();

        self::$_storage[$position] = $class_name;
        ksort(self::$_storage);
    }

    public static function correct_uri($full_uri)
    {
        $uri = uri_path(preg_replace('#[/]{2,}#', '/', $full_uri . '/'));
        $ar_uri = explode('/', $uri);
        $count = count($ar_uri);

        $prefix = constant('SITE_PREFIX');
        $ar_prefix = explode('/', $prefix);

        foreach ($ar_prefix as $pos => $pref) {
            if (!$pref) continue;
            if (array_key_exists($pos, $ar_uri) && $ar_uri[$pos] != $pref)
                array_splice($ar_uri, $pos, 0, $pref);
        }

        if ($count == count($ar_uri))
            return;

        $redirect_uri = implode('/', $ar_uri) . uri_query($full_uri);
        return preg_replace('#[/]{2,}#', '/', $redirect_uri . '/');
    }

    public static function get_site_prefix()
    {
        foreach (self::$_storage as $uri_position => $class_name) {
            $ar_prefix[$uri_position] = $class_name::code();
        }

        return $ar_prefix ? '/' . implode('/', $ar_prefix) : '';
    }

    public static function find_file($full_uri) {
        $uri_path = uri_path(preg_replace('#[/]{2,}#', '/', $full_uri . '/'));
        $path = str_replace(constant('SITE_PREFIX'), '', $uri_path);

        if (is_dir($_SERVER["DOCUMENT_ROOT"] . $path)) {
            $path .= 'index.php';
        }

        if (file_exists($_SERVER["DOCUMENT_ROOT"] . $path) && is_file($_SERVER["DOCUMENT_ROOT"] . $path)) {
            return $_SERVER["DOCUMENT_ROOT"] . $path;
        }

        return false;
    }

    public static function change_url_rewrite() {
        $url_rewrite_path = $_SERVER["DOCUMENT_ROOT"].DIRECTORY_SEPARATOR.self::URL_REWRITE_FILE;
        $url_rewrite_content = trim(file_get_contents($url_rewrite_path));

        $url_rewrite_content_append = (substr($url_rewrite_content, -2) == '?>') ? '<?'.PHP_EOL : PHP_EOL.PHP_EOL;
        $url_rewrite_content_append .=
            'if (defined(\'SITE_PREFIX\')) {
  foreach ($arUrlRewrite as $i => $rule) {
    $arUrlRewrite[$i]["CONDITION"] = str_replace("//", "/", substr_replace($rule["CONDITION"], SITE_PREFIX, 2, 0));
  }
  define("'.self::URL_REWRITE.'", true);
}';

        $content = $url_rewrite_content.$url_rewrite_content_append;

        return file_put_contents($url_rewrite_path, $content);
    }

}