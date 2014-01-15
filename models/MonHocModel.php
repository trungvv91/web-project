<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'MyConnection.php';

/**
 * Description of MonHoc
 *
 * @author TRUNG
 */
class MonHocEntity {

    public $ma_mon;
    public $ten_mon;
    public $mo_ta;

    function __construct($ma_mon, $ten_mon, $mo_ta) {
        $this->ma_mon = $ma_mon;
        $this->ten_mon = $ten_mon;
        $this->mo_ta = $mo_ta;
    }

}

class MonHocModel {

    private $myConn;

    function __construct() {
        $this->myConn = new MyConnection();
    }

    function GetMonHocAll() {
        $conn = $this->myConn->GetConnection();
        $result = mysqli_query($conn, "select * from mon_hoc") or die(mysqli_error($conn));
        $list = array();
        while ($row = mysqli_fetch_array($result)) {
            $ma_mon = $row[0];
            $ten_mon = $row[1];
            $mo_ta = $row[2];
            $entity = new MonHocEntity($ma_mon, $ten_mon, $mo_ta);
            array_push($list, $entity);
        }

        mysqli_close($conn);
        return $list;
    }

    function GetMonHocById($id) {
        $conn = $this->myConn->GetConnection();
        $result = mysqli_query($conn, "select * from mon_hoc where ma_mon=$id") or die(mysqli_error($conn));
        $entity = null;
        while ($row = mysqli_fetch_array($result)) {
            $ma_mon = $row[0];
            $ten_mon = $row[1];
            $mo_ta = $row[2];
            $entity = new MonHocEntity($ma_mon, $ten_mon, $mo_ta);
        }

        mysqli_close($conn);
        return $entity;
    }

    /**
     * lấy ra môn học đầu tiên chọn cho dropdown box
     * @param type $id
     * @return \MonHocEntity
     */
    function GetMonHocFirst() {
        $conn = $this->myConn->GetConnection();
        $result = mysqli_query($conn, "select * from mon_hoc limit 1") or die(mysqli_error($conn));
        $entity = null;
        while ($row = mysqli_fetch_array($result)) {
            $ma_mon = $row[0];
            $ten_mon = $row[1];
            $mo_ta = $row[2];
            $entity = new MonHocEntity($ma_mon, $ten_mon, $mo_ta);
        }

        mysqli_close($conn);
        return $entity;
    }

    private function performQuery($query) {
        $conn = $this->myConn->GetConnection();
//        mysqli_query($conn, $query) or die(mysqli_error($conn));

        mysqli_query($conn, $query);
        if (mysqli_error($conn)) {
            $_SESSION['user']['errors']['msg'] = mysqli_error($conn);
            $ok = FALSE;
        } else {
            $_SESSION['user']['success'] = "Cập nhật thành công";
            $ok = TRUE;
        }

        mysqli_close($conn);
        return $ok;
    }

    function InsertMonHoc(MonHocEntity $mh) {
        $query = sprintf("insert into mon_hoc (ten_mon, mo_ta) values ('$mh->ten_mon', '$mh->mo_ta')");
        $this->performQuery($query);
    }

    function UpdateMonHoc(MonHocEntity $mh) {
        $query = sprintf("update mon_hoc set ten_mon='$mh->ten_mon', mo_ta='$mh->mo_ta' where ma_mon=$mh->ma_mon");
        $this->performQuery($query);
    }

    function DeleteMonHoc($id) {
//        require 'LopHocModel.php';
//        $lopModel = new LopHocModel();
//        $lopArray = $lopModel->GetLopByMon($id);
//        foreach ($lopArray as $key => $lop) {
//            $lopModel->DeleteLopHoc($lop->ma_lop);
//        }

        $query = "delete from mon_hoc where ma_mon=$id";
        if (!$this->performQuery($query)) {
            $_SESSION['user']['errors']['msg'] = "bạn cần xóa tất cả các lớp học có liên quan";
        }
    }

}
