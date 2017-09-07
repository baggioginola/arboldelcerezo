<?php
/**
 * Created by PhpStorm.
 * User: ramon
 * Date: 11/02/16
 * Time: 03:56 PM
 */

require_once __MODEL__ . 'CLogsModel.class.inc.php';

class LogsController
{
    public static function store()
    {
        $data = array(
            'date'      => date('Y-m-d H:i:s'),
            'service'   => (isset($service) and !empty($service)) ? $service : 'log_default',
            'info'      => print_r(Debugger::get(), 1),
            'expiration_date' => date("Y-m-d H:i:s", time() + 50 * 86400),
        );
        CLogsModel::singleton()->add($data);
    }
}