<?php

try {
	$connect = new PDO('mysql:host=127.0.0.1;dbname=diatmika','root','',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
	session_start();
} catch (PDOException $e) {
	echo $e->getMessage();
}