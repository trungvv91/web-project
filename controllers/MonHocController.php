<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once '../utils/MyUtil.php';
require '../models/MonHocModel.php';
require '../views/MonHocView.php';

/**
 * Description of MonHocModel
 *
 * @author TRUNG
 */
class MonHocController {

    private $model;
    private $view;
    private $strProcessPage;

    function __construct($strProcessPage) {
        $this->model = new MonHocModel();
        $this->view = new MonHocView($this->model);
        $this->strProcessPage = $strProcessPage;
    }

    function GetAction() {
        if (isset($_GET['action'])) {
            return test_input($_GET['action']);
        } else {
            return 'none';
        }
    }

    function ProcessData($action) {
        $strHeader = "Location: " . $this->strProcessPage;
        if ($action == 'delete') {
            $id = test_input($_GET['id']);
            $this->model->DeleteMonHoc($id);
        } else if (isset($_POST['submit'])) {
            $ma_mon = test_input($_POST['txtMaMon']);
            $ten_mon = test_input($_POST['txtTenMon']);
            $mo_ta = test_input($_POST['txtMoTa']);
            $mh = new MonHocEntity($ma_mon, $ten_mon, $mo_ta);
            if ($action == 'insert') {
                $this->model->InsertMonHoc($mh);
            } else if ($action == 'update') {
                $this->model->UpdateMonHoc($mh);
            }
        } else {
            return '';
        }
        return $strHeader;
    }

    function SetContent($action) {
        $content = $this->view->CreateMonHocTable()
                . "<br /><br />
           <a href='?action=insertform'>
                <image src='/images/add/add_16x16.png' alt='Thêm'> Thêm môn học</image>
           </a>
           <br /><br />";
        if (isset($_SESSION['user']['errors'])) {
            $content = $content . "<div class='error_msg'>" . $_SESSION['user']['errors']['msg'] . "</div>";
        }
        if (isset($_SESSION['user']['success'])) {
            $content = $content . "<div class='ok_msg'>" . $_SESSION['user']['success'] . "</div>";
        }

        if ($action == 'insertform') {
            $content = $content . $this->view->CreateAddForm();
        } else if ($action == 'updateform') {
            $content = $content . $this->view->CreateUpdateForm(test_input($_GET['id']));
        }

        return $content;
    }

    function Control() {
        $action = $this->GetAction();
        $strHeader = $this->ProcessData($action);
        if (strlen($strHeader) > 0) {
            header($strHeader);
            exit();
        }

        $content = $this->SetContent($action);
        unset($_SESSION['user']['errors']);
        unset($_SESSION['user']['success']);
        return $content;
    }

}
