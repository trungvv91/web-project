<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require '../controllers/DangBaiController.php';

$controller = new DangBaiController("DangBai.php");
$title = "Quản lý dạng";
$content = $controller->Control();

include 'default.php';
