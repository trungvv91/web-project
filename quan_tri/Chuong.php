<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require '../controllers/ChuongController.php';

$controller = new ChuongController("Chuong.php");
$title = "Quản lý chương";
$content = $controller->Control();

include 'default.php';
