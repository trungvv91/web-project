<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'MyConnection.php';

/**
 * Description of LopHocModel
 *
 * @author TRUNG
 */
class LopHocEntity {

    public $ma_lop;
    public $ma_mon;
    public $ten_lop;
    public $mo_ta;

    function __construct($ma_lop, $ma_mon, $ten_lop, $mo_ta) {
        $this->ma_lop = $ma_lop;
        $this->ma_mon = $ma_mon;
        $this->ten_lop = $ten_lop;
        $this->mo_ta = $mo_ta;
    }

}

class LopHocModel {

    private $myConn;

    function __construct() {
        $this->myConn = new MyConnection();
    }

    function GetLopByMon($ma_mon) {
        $conn = $this->myConn->GetConnection();
        $result = mysqli_query($conn, "select * from lop_hoc where ma_mon=$ma_mon") or die(mysqli_error($conn));
        $list = array();
        while ($row = mysqli_fetch_array($result)) {
            $ma_lop = $row[0];
            $ma_mon = $row[1];
            $ten_lop = $row[2];
            $mo_ta = $row[3];
            $entity = new LopHocEntity($ma_lop, $ma_mon, $ten_lop, $mo_ta);
            array_push($list, $entity);
        }

        mysqli_close($conn);
        return $list;
    }

    function GetLopHocById($id) {
        $conn = $this->myConn->GetConnection();
        $result = mysqli_query($conn, "select * from lop_hoc where ma_lop=$id") or die(mysqli_error($conn));
        $entity = null;
        while ($row = mysqli_fetch_array($result)) {
            $ma_lop = $row[0];
            $ma_mon = $row[1];
            $ten_lop = $row[2];
            $mo_ta = $row[3];
            $entity = new LopHocEntity($ma_lop, $ma_mon, $ten_lop, $mo_ta);
        }

        mysqli_close($conn);
        return $entity;
    }

    function CheckLopHocExist(LopHocEntity $entity) {
        $conn = $this->myConn->GetConnection();
        $result = mysqli_query($conn, "select * from lop_hoc where ma_mon=$entity->ma_mon and ten_lop=$entity->ten_lop") or die(mysqli_error($conn));
        $isExist = false;
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);
            if ($entity->ma_lop != $row[0]) {
                $isExist = true;
            }
        }

        mysqli_close($conn);
        return $isExist;
    }

    /**
     * lấy ra lớp học đầu tiên chọn cho dropdown box
     * @param type $id
     * @return \MonHocEntity
     */
    function GetLopHocFirst($ma_mon) {
        $conn = $this->myConn->GetConnection();
        $result = mysqli_query($conn, "select * from lop_hoc where ma_mon=$ma_mon limit 1") or die(mysqli_error($conn));
        $entity = null;
        while ($row = mysqli_fetch_array($result)) {
            $ma_lop = $row[0];
            $ma_mon = $row[1];
            $ten_lop = $row[2];
            $mo_ta = $row[3];
            $entity = new LopHocEntity($ma_lop, $ma_mon, $ten_lop, $mo_ta);
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

    function InsertLopHoc(LopHocEntity $lop) {
        $query = sprintf("insert into lop_hoc (ma_mon, ten_lop, mo_ta) values ('$lop->ma_mon', '$lop->ten_lop', '$lop->mo_ta')");
        $this->performQuery($query);
    }

    function UpdateLopHoc(LopHocEntity $lop) {
        $query = sprintf("update lop_hoc set ma_mon='$lop->ma_mon', ten_lop='$lop->ten_lop', mo_ta='$lop->mo_ta' where ma_lop=$lop->ma_lop");
        $this->performQuery($query);
    }

    function DeleteLopHoc($id) {
//        require 'ChuongModel.php';
//        $chuongModel = new ChuongModel();
//        $chuongArray = $chuongModel->GetChuongByLop($id);
//        foreach ($chuongArray as $key => $chuong) {
//            $chuongModel->DeleteChuong($chuong->ma_chuong);
//        }        
        
        $query = "delete from lop_hoc where ma_lop=$id";
        if (!$this->performQuery($query)) {
            $_SESSION['user']['errors']['msg'] = "bạn cần xóa tất cả các chương có liên quan";
        }
    }

}
