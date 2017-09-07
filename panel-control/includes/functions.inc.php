<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 31/ene/2017
 * Time: 20:31
 */
function resizeImage($path, $height, $width, $extension)
{
    Debugger::add('resizeImage', $path, false, __LINE__, __METHOD__);
    ini_set('memory_limit',-1);
    if ($extension == 'jpg') {
        $img_original = imagecreatefromjpeg($path);
    } else if ($extension == 'png') {
        $img_original = imagecreatefrompng($path);
    } else {
        $img_original = imagecreatefromjpeg($path);
    }

    $max_width = $width;
    $max_height = $height;

    list($ancho, $alto) = getimagesize($path);

    $x_ratio = $max_width / $ancho;
    $y_ratio = $max_height / $alto;

    if (($ancho <= $max_width) && ($alto <= $max_height)) {
        $ancho_final = $ancho;
        $alto_final = $alto;
    } elseif (($x_ratio * $alto) < $max_height) {
        $alto_final = ceil($x_ratio * $alto);
        $ancho_final = $max_width;
    } else {
        $ancho_final = ceil($y_ratio * $ancho);
        $alto_final = $max_height;
    }

    $tmp = imagecreatetruecolor($ancho_final, $alto_final);

    imagecopyresampled($tmp, $img_original, 0, 0, 0, 0, $ancho_final, $alto_final, $ancho, $alto);

    imagedestroy($img_original);

    $tmp2 = imagecreatetruecolor($width, $height);

    if ($extension == 'jpg') {
        $color = imagecolorallocate($tmp2, 255, 255, 255);
        imagefill($tmp2, 0, 0, $color);
    } else {
        $color = imagecolorallocate($tmp2, 0, 0, 0);
        imagecolortransparent($tmp2, $color);
    }

    $distancia_x = round(($max_width - $ancho_final) / 2);
    $distancia_y = round(($max_height - $alto_final) / 2);

    imagecopy($tmp2, $tmp, $distancia_x, $distancia_y, 0, 0, $ancho_final, $alto_final);

    if ($extension == 'jpg') {
        $calidad = 100;
        imagejpeg($tmp2, $path, $calidad);
    } else {
        $calidad = 9;
        imagepng($tmp2, $path, $calidad, PNG_ALL_FILTERS);
    }

    Debugger::add('resizeImage final', $path, false, __LINE__, __METHOD__);
}

/**
 * Sanitizes an user inputed variable or a GET variable
 *
 * @param string $variable Variable to be sanitized
 * @param string $filter Filter to be used. Default FILTER_SANITIZE_STRING,
 *        all the available filters are listed at http://php.net/manual/en/filter.filters.php
 *
 * @return array Information about the country.
 */
function sanitizeVariable($variable, $filter = FILTER_SANITIZE_STRING)
{
    return filter_var($variable, FILTER_SANITIZE_STRING);
}

function stripNonAlphaNumeric($string)
{
    return preg_replace("/[^a-z0-9]/i", "", $string);
}

function stripExcessWhitespace($string)
{
    return preg_replace('/  +/', ' ', $string);
}

function stripNonAlphaNumericSpaces($string)
{
    return preg_replace("/[^a-z0-9 ]/i", "", $string);
}

function formatForUrl($string)
{
    $string = stripNonAlphaNumericSpaces(trim(strtolower($string)));

    return str_replace(" ", "-", stripExcessWhitespace(trim($string)));
}

function formatString($string)
{
    $string = stripNonAlphaNumericSpaces(trim($string));

    return stripExcessWhitespace(trim($string));
}

function getIndexExtension($file)
{
    $array = explode('/', $file);

    $file_name = $array[sizeof($array) - 1];

    $index = explode('_', $file_name);

    $index = $index[sizeof($index) - 1];

    return $index;
}

function getIndex($index_extension)
{
    $index = explode('.', $index_extension);

    $index = $index[sizeof($index) - 2];

    return $index;
}

function getExtension($index_extension)
{
    $extension = explode('.', $index_extension);

    $extension = $extension[sizeof($extension) - 1];

    return $extension;
}

function UTF8Converter($array)
{
    array_walk_recursive($array, function (&$item, $key) {
        if (!mb_detect_encoding($item, 'utf-8', true)) {
            $item = utf8_encode($item);
        }
    });

    return $array;
}

function dateInput($date, $sign = '-')
{
    $array = explode("/", $date);

    return $array[2] . $sign . $array[0] . $sign . $array[1];
}

function getRegularExpresion($type)
{
    switch ($type) {
        case TYPE_INT:
            $regex = '/^[\d]{1,14}$/';
            break;
        case TYPE_FLOAT:
            $regex = '/^(\d){1,14}.?(\d){0,14}/';
            break;
        case TYPE_ALPHA:
            $regex = '/^[a-z A-Z .\w-]+/';
            break;
        case TYPE_PASSWORD:
            $regex = '/^[\w\d\W]+/';
            break;
        case TYPE_DATETIME:
            $regex = '/^(20[0-9]{2}-(0[1-9]{1}|1[0-2]{1}))-(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[01]{1}) ([0-1]{1}[0-9]{1}|2[0-3]{1}):[0-5]{1}[0-9]{1}:[0-5]{1}[0-9]{1}$/';
            break;
        case TYPE_DATE:
            $regex = '/^(20[0-9]{2}-(0[1-9]{1}|1[0-2]{1}))-(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[01]{1})$/';
            break;
        default:
            return false;
            break;
    }
    return $regex;
}