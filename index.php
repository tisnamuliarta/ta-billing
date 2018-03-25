<?php

require_once('connection.php');

if(isset($_GET['controller']) && isset($_GET['action'])) {
    $ontroller = $_GET['controller'];
    $action = $_GET['action'];
}else {
    $controller = 'pages';
    $action = 'home';
}

require_once('views/layout.app');