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
class ChuongEntity {

    public $ma_chuong;
    public $ma_lop;
    public $hoc_ky;
    public $stt;
    public $ten_chuong;
    public $mo_ta;

    function __construct($ma_chuong, $ma_lop, $hoc_ky, $stt, $ten_chuong, $mo_ta) {
        $this->ma_chuong = $ma_chuong;
        $this->ma_lop = $ma_lop;
        $this->hoc_ky = $hoc_ky;
        $this->stt = $stt;
        $this->ten_chuong = $ten_chuong;
        $this->mo_ta = $mo_ta;
    }

}

class ChuongModel {

    private $myConn;

    function __construct() {
        $this->myConn = new MyConnection();
    }

    function CountChuongByLop($ma_lop) {
        $conn = $this->myConn->GetConnection();
        $result = mysqli_query($conn, "select COUNT(ma_chuong) as count from chuong where ma_lop=$ma_lop") or die(mysqli_error($conn));
        $count = mysqli_fetch_assoc($result);
        return $count['count'];
    }

    function GetChuongByLop($ma_lop) {
        $conn = $this->myConn->GetConnection();
        $result = mysqli_query($conn, "select * from chuong where ma_lop=$ma_lop order by stt") or die(mysqli_error($conn));
        $list = array();
        while ($row = mysqli_fetch_array($result)) {
            $ma_chuong = $row[0];
            $ma_lop = $row[1];
            $hoc_ky = $row[2];
            $stt = $row[3];
            $ten_chuong = $row[4];
            $mo_ta = $row[5];
            $entity = new ChuongEntity($ma_chuong, $ma_lop, $hoc_ky, $stt, $ten_chuong, $mo_ta);
            array_push($list, $entity);
        }

        mysqli_close($conn);
        return $list;
    }

    function GetChuongById($id) {
        $conn = $this->myConn->GetConnection();
        $result = mysqli_query($conn, "select * from chuong where ma_chuong=$id") or die(mysqli_error($conn));
        $entity = null;
        while ($row = mysqli_fetch_array($result)) {
            $ma_chuong = $row[0];
            $ma_lop = $row[1];
            $hoc_ky = $row[2];
            $stt = $row[3];
            $ten_chuong = $row[4];
            $mo_ta = $row[5];
            $entity = new ChuongEntity($ma_chuong, $ma_lop, $hoc_ky, $stt, $ten_chuong, $mo_ta);
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
    
    function InsertChuong(ChuongEntity $entity) {
        $query = sprintf("insert into chuong (ma_lop, hoc_ky, stt, ten_chuong, mo_ta) values ('$entity->ma_lop', '$entity->hoc_ky', '$entity->stt', '$entity->ten_chuong', '$entity->mo_ta')");
        $this->performQuery($query);
    }

    function UpdateChuong(ChuongEntity $entity) {
        $query = sprintf("update chuong set hoc_ky='$entity->hoc_ky', ten_chuong='$entity->ten_chuong', mo_ta='$entity->mo_ta' where ma_chuong=$entity->ma_chuong");
        $this->performQuery($query);
    }

    function DeleteChuong($id) {
        $query = "delete from chuong where ma_chuong=$id";
        if (!$this->performQuery($query)) {
            $_SESSION['user']['errors']['msg'] = "bạn cần xóa tất cả các dạng có liên quan";
        }
    }

    function SwapChuong($ma_lop, $stt) {
        if ($stt <= 1) {
            return;
        }

        $stt_1 = $stt - 1;
        $query1 = "update chuong set stt='-1' where ma_lop=$ma_lop and stt=$stt_1";
        $this->performQuery($query1);
        $query2 = " update chuong set stt=$stt_1 where ma_lop=$ma_lop and stt=$stt";
        $this->performQuery($query2);
        $query3 = " update chuong set stt=$stt where ma_lop=$ma_lop and stt='-1'";
        $this->performQuery($query3);
    }

}
