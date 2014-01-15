<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../utils/MyUtil.php';
require '../models/ChuongModel.php';
require '../views/ChuongView.php';

/**
 * Description of ChuongController
 *
 * @author TRUNG
 */
class ChuongController {

    private $model;
    private $view;
    private $strProcessPage;

    function __construct($strProcessPage) {
        $this->model = new ChuongModel();
        $this->view = new ChuongView($this->model);
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
            if (isset($_SESSION['user']['ma_lop']) && $_SESSION['user']['ma_lop'] != $ma_lop) {
                unset($_SESSION['user']['ma_chuong']);
            }
            $_SESSION['user']['ma_lop'] = $ma_lop;
        }
        return $ma_lop;
    }

    function GetAction() {
        if (isset($_GET['action'])) {
            return test_input($_GET['action']);
        } else {
            return 'none';
        }
    }

    function ProcessData($ma_lop, $action) {
        $strHeader = "Location: " . $this->strProcessPage . "?ma_lop=$ma_lop";
        if ($action == 'delete' && isset($_GET['id'])) {
            $id = test_input($_GET['id']);
            $this->model->DeleteChuong($id);
        } else if (isset($_POST['submit'])) {
            $ma_chuong = test_input($_POST['txtMaChuong']);
//            $ma_lop = test_input($_GET['ma_lop']);
            $hoc_ky = test_input($_POST['dropdownHocKy']);
            $stt = $this->model->CountChuongByLop($ma_lop) + 1;
            $ten_chuong = test_input($_POST['txtTenChuong']);
            $mo_ta = test_input($_POST['txtMoTa']);
            $entity = new ChuongEntity($ma_chuong, $ma_lop, $hoc_ky, $stt, $ten_chuong, $mo_ta);
            if ($action == 'insert') {
                $this->model->InsertChuong($entity);
            } else if ($action == 'update') {
                $this->model->UpdateChuong($entity);
            }
        } else if ($action == 'swap' && isset($_GET['stt'])) {
            $stt = test_input($_GET['stt']);
            $this->model->SwapChuong($ma_lop, $stt);
        } else {
            return '';
        }
        return $strHeader;
    }

    function SetContent($ma_mon, $ma_lop, $action) {
        $content = $this->view->CreateMonHocDropDownList($ma_mon);
        if ($ma_mon > -1) {
            $content = $content . $this->view->CreateLopHocDropDownList($ma_mon, $ma_lop);
        }
        $content = $content . "<br /><br />" . $this->view->CreateChuongTable($ma_lop);
        if ($ma_lop > -1) {
            $content = $content . "<br /><br />
                        <a href='?ma_lop=$ma_lop&action=insertform'>
                             <image src='/images/add/add_16x16.png' alt='Thêm'> Thêm chương</image>
                        </a>
                             <br /><br />";
            if (isset($_SESSION['user']['errors'])) {
                $content = $content . "<div class='error_msg'>" . $_SESSION['user']['errors']['msg'] . "</div>";
            }
            if (isset($_SESSION['user']['success'])) {
                $content = $content . "<div class='ok_msg'>" . $_SESSION['user']['success'] . "</div>";
            }
            if ($action == 'insertform') {
                $content = $content . $this->view->CreateAddForm($ma_lop);
            } else if ($action == 'updateform') {
                $content = $content . $this->view->CreateUpdateForm(test_input($_GET['id']));
            }
        }

        return $content;
    }

    function Control() {
        $ma_lop = $this->GetMaLop();
        if ($ma_lop > -1) {
            $ma_mon = $_SESSION['user']['ma_mon'];
        } else {
            $ma_mon = $this->GetMaMon();
            if ($ma_mon > -1 && isset($_SESSION['user']['ma_lop'])) {
                $ma_lop = $_SESSION['user']['ma_lop'];
            }
        }
        $action = $this->GetAction();
        $strHeader = $this->ProcessData($ma_lop, $action);
        if (strlen($strHeader) > 0) {
            header($strHeader);
            exit();
        }

        $content = $this->SetContent($ma_mon, $ma_lop, $action);
        unset($_SESSION['user']['errors']);
        unset($_SESSION['user']['success']);

        return $content;
    }

}
