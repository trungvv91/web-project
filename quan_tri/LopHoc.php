<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require '../controllers/LopHocController.php';

$controller = new LopHocController("LopHoc.php");
$title = "Quản lý lớp học";
$content = $controller->Control();

include 'default.php';
