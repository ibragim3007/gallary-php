<?php
require "config.php";

$connect = mysqli_connect(
    $config['hostname'],
    $config['name'],
    $config['password'],
    $config['database']);

if($connect == false){
    echo "Подкоючение к базе данных не произошло";
}

?>