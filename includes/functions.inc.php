<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 12/sep/2017
 * Time: 20:07
 */

function getProductsUrl($result)
{
    $png = '.png';
    $jpg = '.jpg';

    if (empty($result)) {
        return $result;
    }

    foreach ($result as $key => $value) {
        if (file_exists(PRODUCT_IMG_ROOT . $value['id'] . $jpg)) {
            $result[$key]['url_image'] = PRODUCT_IMG . $value['id'] . $jpg;
        } else if (file_exists(PRODUCT_IMG_ROOT . $value['id'] . $png)) {
            $result[$key]['url_image'] = PRODUCT_IMG . $value['id'] . $png;
        } else {
            $result[$key]['url_image'] = PRODUCT_IMG . $this->default_image . $jpg;
        }
    }
    return $result;
}

function getProductUrl($result)
{
    $png = '.png';
    $jpg = '.jpg';

    if (empty($result)) {
        return $result;
    }

    if (file_exists(PRODUCT_IMG_ROOT . $result['id'] . $jpg)) {
        $result['url_image'] = PRODUCT_IMG . $result['id'] . $jpg;
    } else if (file_exists(PRODUCT_IMG_ROOT . $result['id'] . $png)) {
        $result['url_image'] = PRODUCT_IMG . $result['id'] . $png;
    } else {
        $result['url_image'] = PRODUCT_IMG . $this->default_image . $jpg;
    }
    return $result;
}