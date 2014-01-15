<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../utils/MyUtil.php';
require '../models/DangBaiModel.php';
require '../views/DangBaiView.php';

/**
 * Description of DangBaiController
 *
 * @author TRUNG
 */
class DangBaiController {

    private $model;
    private $view;
    private $strProcessPage;

    function __construct($strProcessPage) {
        $this->model = new DangBaiModel();
        $this->view = new DangBaiView($this->model);
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

    function GetMaLop() {
        $ma_lop = -1;
        if (isset($_GET['ma_lop'])) {
            $ma_lop = test_input($_GET['ma_lop']);
            if (isset($_SESSION['user']['ma_lop']) && $_SESSION['user']['ma_lop'] != $ma_mon) {
                unset($_SESSION['user']['ma_chuong']);
            }
            $_SESSION['user']['ma_lop'] = $ma_lop;
        } else if (isset($_SESSION['user']['ma_lop'])) {
            $ma_lop = $_SESSION['user']['ma_lop'];
        }
        return $ma_lop;
    }

    function GetMaChuong() {
        $ma_chuong = -1;
        if (isset($_GET['ma_chuong'])) {
            $ma_chuong = test_input($_GET['ma_chuong']);
            $_SESSION['user']['ma_chuong'] = $ma_chuong;
        }
        return $ma_chuong;
    }

    function GetAction() {
        if (isset($_GET['action'])) {
            return test_input($_GET['action']);
        } else {
            return 'none';
        }
    }

    function ProcessData($ma_chuong, $action) {
        $strHeader = "Location: " . $this->strProcessPage . "?ma_chuong=$ma_chuong";
        if ($action == 'delete' && isset($_GET['id'])) {
            $id = test_input($_GET['id']);
            $this->model->DeleteDangBai($id);
        } else if (isset($_POST['submit'])) {
            $ma_dang = test_input($_POST['txtMaDang']);
//            $ma_chuong = test_input($_GET['ma_chuong']);
            $stt = test_input($_POST['dropdownMaDang']);
            $ten_dang = test_input($_POST['txtTenDang']);
            $mo_ta = test_input($_POST['txtMoTa']);
            $entity = new DangBaiEntity($ma_dang, $ma_chuong, $stt, $ten_dang, $mo_ta);
            if ($action == 'insert') {
                $this->model->InsertDangBai($entity);
            } else if ($action == 'update') {
                $this->model->UpdateDangBai($entity);
            }
        } else {
            return '';
        }
        return $strHeader;
    }

    function SetContent($ma_mon, $ma_lop, $ma_chuong, $action) {
        $content = $this->view->CreateMonHocDropDownList($ma_mon);
        if ($ma_mon > -1) {
            $content = $content . $this->view->CreateLopHocDropDownList($ma_mon, $ma_lop);
        }
        if ($ma_lop > -1) {
            $content = $content . "<br /><br />" . $this->view->CreateChuongDropDownList($ma_lop, $ma_chuong);
        }
        $content = $content . "<br /><br />" . $this->view->CreateDangBaiTable($ma_chuong);
        if ($ma_chuong > -1) {
            $content = $content . "<br /><br />
                        <a href='?ma_chuong=$ma_chuong&action=insertform'>
                             <image src='/images/add/add_16x16.png' alt='Thêm'> Thêm dạng</image>
                        </a>
                             <br /><br />";
            if (isset($_SESSION['user']['errors'])) {
                $content = $content . "<div class='error_msg'>" . $_SESSION['user']['errors']['msg'] . "</div>";
            }
            if (isset($_SESSION['user']['success'])) {
                $content = $content . "<div class='ok_msg'>" . $_SESSION['user']['success'] . "</div>";
            }
            if ($action == 'insertform') {
                $content = $content . $this->view->CreateAddForm($ma_chuong);
            } else if ($action == 'updateform') {
                $content = $content . $this->view->CreateUpdateForm(test_input($_GET['id']));
            }
        }

        return $content;
    }

    function Control() {
        $ma_chuong = $this->GetMaChuong();
        if ($ma_chuong > -1) {
            $ma_lop = $_SESSION['user']['ma_lop'];
            $ma_mon = $_SESSION['user']['ma_mon'];
        } else {
            $ma_lop = $this->GetMaLop();
            if ($ma_lop > -1) {
                $ma_mon = $_SESSION['user']['ma_mon'];
                if (isset($_SESSION['user']['ma_chuong'])) {
                    $ma_chuong = $_SESSION['user']['ma_chuong'];
                }
            } else {
                $ma_mon = $this->GetMaMon();                
            }
        }
        $action = $this->GetAction();
        $strHeader = $this->ProcessData($ma_chuong, $action);
        if (strlen($strHeader) > 0) {
            header($strHeader);
            exit();
        }

        $content = $this->SetContent($ma_mon, $ma_lop, $ma_chuong, $action);
        unset($_SESSION['user']['errors']);
        unset($_SESSION['user']['success']);

        return $content;
    }

}
