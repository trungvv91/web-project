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
class DangBaiEntity {

    public $ma_dang;
    public $ma_chuong;
    public $stt;
    public $ten_dang;
    public $mo_ta;

    function __construct($ma_dang, $ma_chuong, $stt, $ten_dang, $mo_ta) {
        $this->ma_dang = $ma_dang;
        $this->ma_chuong = $ma_chuong;
        $this->stt = $stt;
        $this->ten_dang = $ten_dang;
        $this->mo_ta = $mo_ta;
    }

}

class DangBaiModel {

    private $myConn;

    function __construct() {
        $this->myConn = new MyConnection();
    }

    function CountDangByChuong($ma_chuong) {
        $conn = $this->myConn->GetConnection();
        $result = mysqli_query($conn, "select COUNT(ma_dang) as count from dang_bai where ma_chuong=$ma_chuong") or die(mysqli_error($conn));
        $count = mysqli_fetch_assoc($result);
        return $count['count'];
    }

    function GetDangByChuong($ma_chuong) {
        $conn = $this->myConn->GetConnection();
        $result = mysqli_query($conn, "select * from dang_bai where ma_chuong=$ma_chuong order by stt") or die(mysqli_error($conn));
        $list = array();
        while ($row = mysqli_fetch_array($result)) {
            $ma_dang = $row[0];
            $ma_chuong = $row[1];
            $stt = $row[2];
            $ten_dang = $row[3];
            $mo_ta = $row[4];
            $entity = new DangBaiEntity($ma_dang, $ma_chuong, $stt, $ten_dang, $mo_ta);
            array_push($list, $entity);
        }

        mysqli_close($conn);
        return $list;
    }

    function GetDangById($id) {
        $conn = $this->myConn->GetConnection();
        $result = mysqli_query($conn, "select * from dang_bai where ma_dang=$id") or die(mysqli_error($conn));
        $entity = null;
        while ($row = mysqli_fetch_array($result)) {
            $ma_dang = $row[0];
            $ma_chuong = $row[1];
            $stt = $row[2];
            $ten_dang = $row[3];
            $mo_ta = $row[4];
            $entity = new DangBaiEntity($ma_dang, $ma_chuong, $stt, $ten_dang, $mo_ta);
        }

        mysqli_close($conn);
        return $entity;
    }

//    private function performQuery($query) {
//        $conn = $this->myConn->GetConnection();
////        mysqli_query($conn, $query) or die(mysqli_error($conn));
//
//        mysqli_query($conn, $query);
//        if (mysqli_error($conn)) {
//            $_SESSION['user']['errors']['msg'] = mysqli_error($conn);
//            $ok = FALSE;
//        } else {
//            $ok = TRUE;
//        }
//
//        mysqli_close($conn);
//        return $ok;
//    }

    /**
     * Thêm dạng mới, đảm bảo số thứ tự dạng
     * @param DangBaiEntity $entity
     */
    function InsertDangBai(DangBaiEntity $entity) {
        $count = $this->CountDangByChuong($entity->ma_chuong);
        $conn = $this->myConn->GetConnection();
        mysqli_autocommit($conn, FALSE);
        if ($entity->stt <= count) {
            for ($i = $count; $i >= $entity->stt; $i--) {
                $i1 = $i + 1;
                $query = sprintf("update dang_bai set stt='$i1' where stt=$i and ma_chuong=$entity->ma_chuong");
                mysqli_query($conn, $query);
                if (mysqli_error($conn)) {
                    $_SESSION['user']['errors']['msg'] = mysqli_error($conn);
                    mysqli_rollback($conn);
                    mysqli_autocommit($conn, TRUE);
                    return;
                }
            }
        }
        $query = sprintf("insert into dang_bai (ma_chuong, stt, ten_dang, mo_ta) values ('$entity->ma_chuong', '$entity->stt', '$entity->ten_dang', '$entity->mo_ta')");
        mysqli_query($conn, $query);
        if (mysqli_error($conn)) {
            $_SESSION['user']['errors']['msg'] = mysqli_error($conn);
            mysqli_rollback($conn);
            mysqli_autocommit($conn, TRUE);
            return;
        }
        mysqli_commit($conn);
        mysqli_autocommit($conn, TRUE);
        $_SESSION['user']['success'] = "Cập nhật thành công";
    }

    function UpdateDangBai(DangBaiEntity $entity) {
        $oldEntity = $this->GetDangById($entity->ma_dang);
        $conn = $this->myConn->GetConnection();
        mysqli_autocommit($conn, FALSE);
        if ($oldEntity->stt > $entity->stt) {
            for ($i = $oldEntity->stt - 1; $i >= $entity->stt; $i--) {
                $i1 = $i + 1;
                $query = sprintf("update dang_bai set stt='$i1' where stt=$i and ma_chuong=$entity->ma_chuong");
                mysqli_query($conn, $query);
                if (mysqli_error($conn)) {
                    $_SESSION['user']['errors']['msg'] = mysqli_error($conn);
                    mysqli_rollback($conn);
                    mysqli_autocommit($conn, TRUE);
                    return;
                }
            }
        } else if ($oldEntity->stt < $entity->stt) {
            for ($i = $oldEntity->stt + 1; $i <= $entity->stt; $i++) {
                $i_1 = $i - 1;
                $query = sprintf("update dang_bai set stt='$i_1' where stt=$i and ma_chuong=$entity->ma_chuong");
                mysqli_query($conn, $query);
                if (mysqli_error($conn)) {
                    $_SESSION['user']['errors']['msg'] = mysqli_error($conn);
                    mysqli_rollback($conn);
                    mysqli_autocommit($conn, TRUE);
                    return;
                }
            }
        }
        $query = sprintf("update dang_bai set stt='$entity->stt', ten_dang='$entity->ten_dang', mo_ta='$entity->mo_ta' where ma_dang='$entity->ma_dang'");
        mysqli_query($conn, $query);
        if (mysqli_error($conn)) {
            $_SESSION['user']['errors']['msg'] = mysqli_error($conn);
            mysqli_rollback($conn);
            mysqli_autocommit($conn, TRUE);
            return;
        }
        mysqli_commit($conn);
        mysqli_autocommit($conn, TRUE);
        $_SESSION['user']['success'] = "Cập nhật thành công";
    }

    function DeleteDangBai($id) {
        $entity = $this->GetDangById($id);
        $count = $this->CountDangByChuong($entity->ma_chuong);
        $conn = $this->myConn->GetConnection();
        mysqli_autocommit($conn, FALSE);
        $query = "delete from dang_bai where ma_dang=$id";
        mysqli_query($conn, $query);
        if (mysqli_error($conn)) {
            $_SESSION['user']['errors']['msg'] = "bạn cần xóa tất cả các câu hỏi có liên quan";
            mysqli_rollback($conn);
            mysqli_autocommit($conn, TRUE);
            return;
        }
        for ($i = $entity->stt + 1; $i <= $count; $i++) {
            $i_1 = $i - 1;
            $query = sprintf("update dang_bai set stt='$i_1' where stt=$i and ma_chuong=$entity->ma_chuong");
            mysqli_query($conn, $query);
            if (mysqli_error($conn)) {
                $_SESSION['user']['errors']['msg'] = mysqli_error($conn);
                mysqli_rollback($conn);
                mysqli_autocommit($conn, TRUE);
                return;
            }
        }
        mysqli_commit($conn);
        mysqli_autocommit($conn, TRUE);
        $_SESSION['user']['success'] = "Cập nhật thành công";
    }

}
