<?php
// require '../../config.php';
require 'config.php';
include 'queries/db_creation_query.php';


try{
    $db = new \PDO("mysql:host=" .$config['db_host']. ";dbname=" .$config['db_name'].";charset=utf8",$config['db_user'],$config['db_pwd']);
    $db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

    $db->query($db_creation_query);

}
catch (\PDOException $e){
    echo 'La connexion à échoué.<br/>';
    echo 'Information : [', $e->getCode(), '] ', $e->getMessage();
}

