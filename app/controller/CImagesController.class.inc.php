<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 20/feb/2017
 * Time: 20:34
 */
require_once __CONTROLLER__ . 'CBaseController.class.inc.php';

class Images extends BaseController
{
    private static $object = null;

    private $parameters = array();
    private $default_image = 'default_image';
    private $validParameters = array(
        'id_producto' => TYPE_INT
    );

    /**
     * @return Images|null
     */
    public static function singleton()
    {
        if (is_null(self::$object)) {
            self::$object = new self();
        }
        return self::$object;
    }

    /**
     * @return string
     */
    public function getProductsUrl($result)
    {
        $png = '.png';
        $jpg = '.jpg';

        if (empty($result)) {
            return $result;
        }
        foreach ($result as $key => $value) {
            if (file_exists(PRODUCT_IMG_ROOT . $value['id_producto'] . $jpg)) {
                $result[$key]['url_image'] = PRODUCT_IMG . $value['id_producto'] . $jpg;
            } else if (file_exists(PRODUCT_IMG_ROOT . $value['id_producto'] . $png)) {
                $result[$key]['url_image'] = PRODUCT_IMG . $value['id_producto'] . $png;
            } else {
                $result[$key]['url_image'] = PRODUCT_IMG . $this->default_image . $jpg;
            }
        }
        return $result;
    }

    public function getProductUrl($result)
    {
        $png = '.png';
        $jpg = '.jpg';

        if (empty($result)) {
            return $result;
        }

        if (file_exists(PRODUCT_IMG_ROOT . $result['id_producto'] . $jpg)) {
            $result['url_image'] = PRODUCT_IMG . $result['id_producto'] . $jpg;
        } else if (file_exists(PRODUCT_IMG_ROOT . $result['id_producto'] . $png)) {
            $result['url_image'] = PRODUCT_IMG . $result['id_producto'] . $png;
        } else {
            $result['url_image'] = PRODUCT_IMG . $this->default_image . $jpg;
        }
        return $result;
    }

    public function getProjectsUrl($result)
    {
        $png = '.png';
        $jpg = '.jpg';

        if (empty($result)) {
            return $result;
        }
        foreach ($result as $key => $value) {
            if (file_exists(PROJECT_IMG_ROOT . $value['id_caso_exito'] . $jpg)) {
                $result[$key]['url_image'] = PROJECT_IMG . $value['id_caso_exito'] . $jpg;
            } else if (file_exists(PROJECT_IMG_ROOT . $value['id_caso_exito'] . $png)) {
                $result[$key]['url_image'] = PROJECT_IMG . $value['id_caso_exito'] . $png;
            } else {
                $result[$key]['url_image'] = PROJECT_IMG . $this->default_image . $jpg;
            }
        }
        return $result;
    }

    public function getProjectUrl($result)
    {
        $png = '.png';
        $jpg = '.jpg';

        if (empty($result)) {
            return $result;
        }

        if (file_exists(PROJECT_IMG_ROOT . $result['id_caso_exito'] . $jpg)) {
            $result['url_image'] = PROJECT_IMG . $result['id_caso_exito'] . $jpg;
        } else if (file_exists(PROJECT_IMG_ROOT . $result['id_caso_exito'] . $png)) {
            $result['url_image'] = PROJECT_IMG . $result['id_caso_exito'] . $png;
        } else {
            $result['url_image'] = PROJECT_IMG . $this->default_image . $jpg;
        }
        return $result;
    }

    public function getBannerTopUrl($num_imagenes = 0)
    {
        $png = '.png';
        $jpg = '.jpg';

        $array = array();
        if ($num_imagenes == 0) {
            $array[] = BANNER_TOP_IMG . $this->default_image . $jpg;
            return $array;
        }

        for ($i = 1; $i <= $num_imagenes; $i++) {
            if ($i == 1) {
                if (file_exists(BANNER_TOP_IMG_ROOT . 'banner' . $jpg)) {
                    $array[] = BANNER_TOP_IMG . 'banner' . $jpg;
                } else if (file_exists(BANNER_TOP_IMG_ROOT . 'banner' . $png)) {
                    $array[] = BANNER_TOP_IMG . 'banner' . $png;
                } else {
                    $array[] = BANNER_BIG_IMG . $this->default_image . $jpg;
                }
            } else {
                if (file_exists(BANNER_TOP_IMG_ROOT . 'banner_' . $i . $jpg)) {
                    $array[] = BANNER_TOP_IMG . 'banner_' . $i . $jpg;
                } else if (file_exists(BANNER_TOP_IMG_ROOT . 'banner_' . $i . $png)) {
                    $array[] = BANNER_TOP_IMG . 'banner_' . $i . $png;
                } else {
                    $array[] = BANNER_TOP_IMG . $this->default_image . $jpg;
                }
            }
        }

        return $array;
    }

    public function getBannerMainUrl($num_imagenes = 0)
    {
        $png = '.png';
        $jpg = '.jpg';

        $array = array();
        if ($num_imagenes == 0) {
            $array[] = BANNER_BIG_IMG . $this->default_image . $jpg;
            return $array;
        }

        for ($i = 1; $i <= $num_imagenes; $i++) {
            if ($i == 1) {
                if (file_exists(BANNER_BIG_IMG_ROOT . 'banner' . $jpg)) {
                    $array[] = BANNER_BIG_IMG . 'banner' . $jpg;
                } else if (file_exists(BANNER_BIG_IMG_ROOT . 'banner' . $png)) {
                    $array[] = BANNER_BIG_IMG . 'banner' . $png;
                } else {
                    $array[] = BANNER_BIG_IMG . $this->default_image . $jpg;
                }
            } else {
                if (file_exists(BANNER_BIG_IMG_ROOT . 'banner_' . $i . $jpg)) {
                    $array[] = BANNER_BIG_IMG . 'banner_' . $i . $jpg;
                } else if (file_exists(BANNER_BIG_IMG_ROOT . 'banner_' . $i . $png)) {
                    $array[] = BANNER_BIG_IMG . 'banner_' . $i . $png;
                } else {
                    $array[] = BANNER_BIG_IMG . $this->default_image . $jpg;
                }
            }
        }

        return $array;
    }

    public function getBannerBrandsUrl($num_imagenes = 0)
    {
        $png = '.png';
        $jpg = '.jpg';

        $array = array();
        if ($num_imagenes == 0) {
            $array[] = BANNER_BRANDS_IMG . $this->default_image . $jpg;
            return $array;
        }

        for ($i = 1; $i <= $num_imagenes; $i++) {
            if ($i == 1) {
                if (file_exists(BANNER_BRANDS_IMG_ROOT . 'banner' . $jpg)) {
                    $array[] = BANNER_BRANDS_IMG . 'banner' . $jpg;
                } else if (file_exists(BANNER_BRANDS_IMG_ROOT . 'banner' . $png)) {
                    $array[] = BANNER_BRANDS_IMG . 'banner' . $png;
                } else {
                    $array[] = BANNER_BRANDS_IMG . $this->default_image . $jpg;
                }
            } else {
                if (file_exists(BANNER_BRANDS_IMG_ROOT . 'banner_' . $i . $jpg)) {
                    $array[] = BANNER_BRANDS_IMG . 'banner_' . $i . $jpg;
                } else if (file_exists(BANNER_BRANDS_IMG_ROOT . 'banner_' . $i . $png)) {
                    $array[] = BANNER_BRANDS_IMG . 'banner_' . $i . $png;
                } else {
                    $array[] = BANNER_BRANDS_IMG . $this->default_image . $jpg;
                }
            }
        }

        return $array;
    }

    /**
     * @return string
     */
    public function getCategoriesUrl($result)
    {
        $png = '.png';
        $jpg = '.jpg';

        if (empty($result)) {
            return $result;
        }
        foreach ($result as $key => $value) {
            if (file_exists(CATEGORY_IMG_ROOT . $value['id_categoria'] . $jpg)) {
                $result[$key]['url_image'] = CATEGORY_IMG . $value['id_categoria'] . $jpg;
            } else if (file_exists(CATEGORY_IMG_ROOT . $value['id_categoria'] . $png)) {
                $result[$key]['url_image'] = CATEGORY_IMG . $value['id_categoria'] . $png;
            } else {
                $result[$key]['url_image'] = CATEGORY_IMG . $this->default_image . $jpg;
            }
        }
        return $result;
    }
}