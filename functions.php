<?php

spl_autoload_register(function ($class_name) {
    include_once str_replace('\\', '/', __DIR__ . DIRECTORY_SEPARATOR . $class_name . '.php');
});

function tr($t)
{
    return $t;
}

function dd($d, $die = true)
{
    echo '<pre>';
    var_dump($d);
    echo '</pre>';

    if ($die) die(__FILE__);
}

function check_array($arr)
{
    if (!is_array($arr) || empty($arr)) return false;
    return true;
}

function uri_path($uri)
{
    $parse_uri = parse_url($uri);
    return (string)$parse_uri["path"];
}

function uri_query($uri)
{
    $parse_uri = parse_url($uri);
    if (!array_key_exists("query", $parse_uri))
        return "";
    return "?" . $parse_uri["query"];
}

function find_in(array $search, array $subject, $position = null)
{
    if ($position === null)
        return current(array_intersect($subject, $search));

    if (array_key_exists($position, $subject) && (false !== ($key = array_search($subject[$position], $search))))
        return $search[$key];

    return false;
}