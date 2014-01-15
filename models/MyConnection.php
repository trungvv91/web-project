<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MyConnection {

    function GetConnection() {
        $server = "localhost";
        $username = "root";
        $password = "";
        $database_name = "studyonlinedb";
        $conn = mysqli_connect($server, $username, $password, $database_name) or die(mysqli_error($conn));
        return $conn;
    }

}
