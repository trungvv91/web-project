<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../utils/MyUtil.php';
require '../models/LopHocModel.php';
require '../views/LopHocView.php';

/**
 * Description of LopHocController
 *
 * @author TRUNG
 */
class LopHocController {

    private $model;
    private $view;
    private $strProcessPage;

    function __construct($strProcessPage) {
        $this->model = new LopHocModel();
        $this->view = new LopHocView($this->model);
        $this->strProcessPage = $strProcessPage;
    }

    function GetMaMon() {
        $ma_mon = -1;
        if (isset($_GET['ma_mon'])) {
            $ma_mon = test_input($_GET['ma_mon']);
            if (isset($_SESSION['user']['ma_mon']) && $_SESSION['user']['ma_mon'] != $ma_mon) {
                unset($_SESSION['user']['ma_lop']);
                unset($_SESSION['user']['ma_chuong']);
            }
            $_SESSION['user']['ma_mon'] = $ma_mon;
        } else if (isset($_SESSION['user']['ma_mon'])) {
            $ma_mon = $_SESSION['user']['ma_mon'];
        }
        return $ma_mon;
    }

    function GetAction() {
        if (isset($_GET['action'])) {
            return test_input($_GET['action']);
        } else {
            return 'none';
        }
    }

    function SetErrorSession($strMsg, LopHocEntity $entity) {
        $_SESSION['user']['errors']['msg'] = $strMsg;
        $_SESSION['user']['errors']['ten_lop'] = $entity->ten_lop;
        $_SESSION['user']['errors']['mo_ta'] = $entity->mo_ta;
    }

    function ProcessData($ma_mon, $action) {
        $strHeader = "Location: " . $this->strProcessPage . "?ma_mon=$ma_mon";
        if ($action == 'delete') {
            $id = test_input($_GET['id']);
            $this->model->DeleteLopHoc($id);
        } else if (isset($_POST['submit'])) {
            $ma_lop = test_input($_POST['txtMaLop']);
//            $ma_mon = test_input($_GET['ma_mon']);
            $ten_lop = test_input($_POST['txtTenLop']);
            $mo_ta = test_input($_POST['txtMoTa']);
            $lop = new LopHocEntity($ma_lop, $ma_mon, $ten_lop, $mo_ta);
            if ($action == 'insert') {
                if ($this->model->CheckLopHocExist($lop)) {
                    $strMsg = "Lỗi: không thể thêm vì lớp " . $lop->ten_lop . " đã tồn tại";
                    $this->SetErrorSession($strMsg, $lop);
                    $strHeader = $strHeader . "&action=insertform";
                } else {
                    $this->model->InsertLopHoc($lop);
                }
            } else if ($action == 'update') {
                if ($this->model->CheckLopHocExist($lop)) {
                    $strMsg = "Lỗi: không thể sửa vì lớp " . $lop->ten_lop . " đã tồn tại";
                    $this->SetErrorSession($strMsg, $lop);
                    $strHeader = $strHeader . "&action=updateform&id=$ma_lop";
                } else {
                    $this->model->UpdateLopHoc($lop);
                }
            }
        } else {
            return '';
        }
        return $strHeader;
    }

    function SetContent($ma_mon, $action) {
        require_once '../views/MonHocView.php';
        $content = $this->view->CreateMonHocDropDownList($ma_mon)
                . "<br /><br />"
                . $this->view->CreateLopHocTable($ma_mon);
        if ($ma_mon > -1) {
            $content = $content . "<br /><br />
                        <a href='?ma_mon=$ma_mon&action=insertform'>
                             <image src='/images/add/add_16x16.png' alt='Thêm'> Thêm lớp học</image>
                        </a>
                             <br /><br />";
            if (isset($_SESSION['user']['errors'])) {
                $content = $content . "<div class='error_msg'>" . $_SESSION['user']['errors']['msg'] . "</div>";
            }
            if (isset($_SESSION['user']['success'])) {
                $content = $content . "<div class='ok_msg'>" . $_SESSION['user']['success'] . "</div>";
            }
            if ($action == 'insertform') {
                $content = $content . $this->view->CreateAddForm($ma_mon);
            } else if ($action == 'updateform') {
                $content = $content . $this->view->CreateUpdateForm(test_input($_GET['id']));
            }
        }

        return $content;
    }

    function Control() {
        $ma_mon = $this->GetMaMon();
        $action = $this->GetAction();
        $strHeader = $this->ProcessData($ma_mon, $action);
        if (strlen($strHeader) > 0) {
            header($strHeader);
            exit();
        }

        $content = $this->SetContent($ma_mon, $action);
        unset($_SESSION['user']['errors']);
        unset($_SESSION['user']['success']);

        return $content;
    }

}
