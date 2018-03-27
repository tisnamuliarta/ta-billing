<?php

require 'connection.php';

$createAdmin = createAdmin($connect);
if($createAdmin) {
    echo 'Success';
}else {
    echo 'error when create admin';
}

function createAdmin($connect) {
    $query = "INSERT INTO tb_admin (username, password) VALUE(:username,:password)";
    $statement = $connect->prepare($query);
    $statement->execute([
        'username'  => 'admin',
        'password'  => password_hash('admin123', PASSWORD_DEFAULT)
    ]);
    return true;
}