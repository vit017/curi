<?php

spl_autoload_register('CUriLoader');

function CUriLoader($className)
{
    $ar_class = explode('\\', $className);
    $class = implode(DIRECTORY_SEPARATOR, array_splice($ar_class, 1));

    include_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'curi' . DIRECTORY_SEPARATOR . $class . '.php';
}

function dd($d, $die = true)
{
    echo "<pre>";
    var_dump($d);
    echo "</pre>";

    if ($die)
        die($die);
}

function check_array($array)
{
    if (!is_array($array) || empty($array))
        return false;

    return true;
}

function get_uri_path($uri)
{
    $parse_uri = parse_url($uri);

    return (string)$parse_uri["path"];
}

function get_uri_query($uri)
{
    $parse_uri = parse_url($uri);

    if (!array_key_exists("query", $parse_uri))
        return "";

    return "?" . $parse_uri["query"];
}

function uri_insert($uri, $position, $code)
{
    $uri_path = get_uri_path($uri);
    $ar_uri = explode("/", $uri_path);
    if (!array_key_exists(($position - 1), $ar_uri))
        return false;

    array_splice($ar_uri, $position, 0, $code);

    $new_uri = implode("/", $ar_uri) . get_uri_query($uri);

    return $new_uri;
}

function uri_remove($uri, $position)
{
    $uri_path = get_uri_path($uri);
    $ar_uri = explode("/", $uri_path);

    if (!array_key_exists($position, $ar_uri))
        return false;

    array_splice($ar_uri, $position, 1);

    $new_uri = implode("/", $ar_uri) . get_uri_query($uri);

    return $new_uri;
}

function tr($text, $lang = false)
{
    if (class_exists("CUriLang") && CUriLang::get_instance()->is_active())
        return CUriLang::tr($text, $lang);

    return $text;
}
