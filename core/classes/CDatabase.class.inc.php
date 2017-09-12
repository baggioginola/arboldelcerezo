<?php
/**
 * Created by PhpStorm.
 * User: mario
 * Date: 03/ene/2017
 * Time: 20:27
 */

class Database
{
    private $link = null;

    protected function connect()
    {
        $this->link = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);

        if (!$this->link) {
            return false;
        } else {
            return self::query('USE ' . DBNAME);
        }
    }

    protected function insert($data = array(), $table = '')
    {
        if (empty($data) || empty($table)) {
            return false;
        }

        $addString = $this->queryAddString($data);

        if (empty($addString)) {
            return false;
        }

        $sql = 'INSERT INTO ' . $table . ' ' . $addString . ';';

        return self::query($sql);
    }

    protected function update($data = array(), $id = '', $table = '')
    {
        if (empty($data) || empty($table) || empty($id)) {
            return false;
        }

        $updateString = $this->queryUpdateString($data);

        if (empty($updateString)) {
            return false;
        }

        $sql = 'UPDATE ' . $table . ' SET ' . $updateString . ' WHERE id = "' . $id . '" ;';

        return self::query($sql);
    }

    protected function remove($table = '', $where = '')
    {
        if (empty($table) || empty($where)) {
            return false;
        }

        $sql = 'DELETE FROM ' . $table . ' WHERE ' . $where;

        return self::query($sql);

    }

    protected function query($query = '')
    {
        if (is_null($this->link)) {
            return false;
        }
        if (empty($query)) {
            return false;
        }

        $result = mysqli_query($this->link, $query);

        if (!$result) {
            self::close_connection();
        }
        return $result;
    }

    protected function fetch_array($result)
    {
        $result = mysqli_fetch_array($result);
        return $result;
    }

    protected function fetch_assoc($result)
    {
        $result = mysqli_fetch_assoc($result);
        return $result;
    }

    protected function getLastId()
    {
        return mysqli_insert_id($this->link);
    }

    protected function close_connection()
    {
        mysqli_close($this->link);
        $this->link = null;
    }

    private function queryAddString($data = array())
    {
        $addString = '';

        $columns = '';
        $values = '';
        $counter = 0;
        foreach ($data as $key => $value) {
            if($counter == sizeof($data) - 1){
                $columns .= $key;
                $values .= "'$value'";
            }
            else {
                $columns .= $key . ',';
                $values .= "'$value'" . ',';
            }
            $counter++;
        }

        if(!empty($columns) && !empty($values)) {
            $addString = '(' . $columns . ') VALUES (' . $values . ')';
        }

        return $addString;
    }

    private function queryUpdateString($data = array())
    {
        $updateString = '';
        $counter = 0;
        foreach($data as $key => $value) {
            if($counter == sizeof($data) - 1) {
                $updateString .= $key . '="' . $value . '"';
            }
            else {
                $updateString .= $key . ' = "' . $value . '", ';
            }
            $counter++;
        }

        return $updateString;
    }
}