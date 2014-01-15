<?php

isset($title) or $title = 'Quản lý website';
isset($content) or $content = 'chưa có';

$leftmenu = array("Quản lý môn học" => 'MonHoc.php',
    "Quản lý lớp" => 'LopHoc.php',
    "Quản lý chương" => 'Chuong.php',
    "Quản lý dạng bài" => 'DangBai.php',
);

include '../template.php';
?>

