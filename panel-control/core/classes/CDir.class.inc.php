<?php

/**
 * Created by PhpStorm.
 * User: mario.cuevas
 * Date: 7/11/2016
 * Time: 9:43 AM
 */


require_once __CONTROLLER__ . 'CBaseController.class.inc.php';

class CDir extends BaseController
{

    private static $object = null;
    private $log = array();
    private $dir = null;

    private $type = null;
    private $name = null;
    private $key_name = null;
    private $category = null;

    public static function singleton()
    {
        if (!isset(self::$object)) {
            self::$object = new CDir();
        }
        return self::$object;
    }

    /**
     * @param null $dir
     * @return bool
     */
    public function createDir($dir = null)
    {
        if (!empty($dir)) {
            $this->dir = $dir;
        }

        if (empty($this->dir)) {
            return false;
        }

        if (file_exists($this->dir)) {
            return true;
        }

        mkdir($this->dir, 0777);
        return true;
    }

    /**
     * @param null $dir
     * @return bool
     */
    public function delete($dir = null)
    {
        if (empty($dir)) {
            return false;
        }

        $files = glob($dir . '/*');

        foreach ($files as $file) {
            is_dir($file) ? $this->delete($file) : unlink($file);
        }

        rmdir($dir);

        return true;
    }

    /**
     * @return bool
     */
    public function rename()
    {
        if ($this->name != $this->key_name) {
            if($this->_getType() == 'categorias') {
                $old_path_image = BASE_IMAGES_CATEGORIES . $this->key_name . "/";
            }
            else {
                $old_path_image = BASE_IMAGES_CATEGORIES . $this->category . '/productos/' . $this->key_name . "/";
            }

            if (!rename($old_path_image, $this->getDir())) {
                return false;
            }
        }
        return true;
    }

    /**
     * @return string
     */
    public function update()
    {
        if (!$this->setDir()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }
        if (!$this->setKeyName()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        if (!$this->rename()) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        if (!CFile::singleton()->rename($this->getDir(), $this->getName())) {
            return json_encode($this->getResponse(STATUS_FAILURE_INTERNAL, MESSAGE_ERROR));
        }

        return json_encode($this->getResponse());
    }

    public function edit($name = null)
    {
        if (!$this->setDir()) {
            return false;
        }

        if (!CFile::singleton()->delete($this->getDir(), $name)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function setDir()
    {
        $this->dir = PRODUCT_IMG;
        $this->_setType();

        return true;
    }

    /**
     * @return null
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @return bool
     */
    private function setName()
    {
        if (!isset($_REQUEST['name']) || empty($_REQUEST['name'])) {
            return false;
        }
        $this->name = $_REQUEST['name'];
        return true;
    }

    /**
     * @return bool
     */
    private function _setType()
    {
        $this->type = 'productos';
        return true;
    }

    /**
     * @return array
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @return null
     */
    public function _getType()
    {
        return $this->type;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $dir
     */
    public function scanDir($dir)
    {
        $this->dir = $dir;
        $this->log[] = 'Scan directory: ';
        $this->log[] = scandir($this->dir);
    }
}