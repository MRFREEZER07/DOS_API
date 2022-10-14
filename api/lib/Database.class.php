<?php
class Database
{
    public static $db;


    public static function get_connection()
    {
        $config_json = file_get_contents($_SERVER['DOCUMENT_ROOT'].'../../env.json');
        $config = json_decode($config_json, true);
        if (Database::$db != null) {
            return Database::$db;
        } else {
            Database::$db = mysqli_connect($config['server'], $config['username'], $config['password'], $config['database']);
            if (!Database::$db) {
                die("Connection failed: ".mysqli_connect_error());
            } else {
                return Database::$db;
            }
        }
    }
}
