<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 15/ene/2017
 * Time: 18:58
 */
require_once __CONTROLLER__ . 'CBaseController.class.inc.php';
require_once __CONTROLLER__ . 'CImagesController.class.inc.php';
require_once __MODEL__ . 'CSearchModel.class.inc.php';

class Search extends BaseController
{
    private static $object = null;

    /**
     * @return null|Search
     */
    public static function singleton()
    {
        if (is_null(self::$object)) {
            self::$object = new self();
        }
        return self::$object;
    }

    public function getProductsbyQuery($query = null)
    {
        if (is_null($query)) {
            return false;
        }

        if (!$result = SearchModel::singleton()->getProductsByQuery($query)) {
            return false;
        }

        return $result;
    }
}