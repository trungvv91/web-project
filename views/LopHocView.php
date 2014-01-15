<script>
    function showConfirmDelete(ma_mon, id)
    {
        var c = confirm("Bạn có chắc chắn muốn xóa lớp học này?");
        if (c)
            window.location = "?ma_mon=" + ma_mon + "&action=delete&id=" + id;
    }

    function onChangeMonHoc(ma_mon) {
        window.location = "?ma_mon=" + ma_mon;
    }
</script>


<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LopHocView
 *
 * @author TRUNG
 */
class LopHocView {

    private $model;

    function __construct(LopHocModel $model) {
        $this->model = $model;
    }

    function CreateMonHocDropDownList($selected_ma_mon) {
        require_once '../models/MonHocModel.php';

        $mhModel = new MonHocModel();
        $result = "<label for='ma_mon' class='myFormLabel'>Chọn môn học:</label>
                   <select name='ma_mon' onchange='onChangeMonHoc(this.value)' class='myFormDropDown'>"
                . "<option value='-1' selected></option>";

        foreach ($mhModel->GetMonHocAll() as $mh) {
            $result = $result . "<option value='$mh->ma_mon'";
            if ($mh->ma_mon == $selected_ma_mon) {
                $result = $result . " selected ";
            }
            $result = $result . ">$mh->ten_mon</option>";
        }
        $result = $result . "</select>";

        return $result;
    }

    function CreateLopHocTable($ma_mon) {
        $list = $this->model->GetLopByMon($ma_mon);
        $result = "<table class='tblOverview'>
                        <tr>                        
                            <th style='width: 10%'></th>
                            <th style='width: 10%'></th>
                            <th style='width: 10%'>Mã lớp</th>
                            <th style='width: 30%'>Tên lớp học</th>
                            <th style='width: 40%'>Mô tả</th>
                        </tr>";

        foreach ($list as $key => $entity) {
            $result = $result . "<tr>
                        <td>
                            <a href='?ma_mon=$ma_mon&action=updateform&id=$entity->ma_lop'>
                                <image src='/images/edit/edit_16x16.png' alt='Sửa'>Sửa</image>
                            </a>
                        </td>
                        <td>
                            <a href='#' onclick='showConfirmDelete($ma_mon,$entity->ma_lop)'>
                                <image src='/images/delete/delete_16x16.png' alt='Xóa'>Xóa</image>
                            </a>
                        </td>
                        <td>$entity->ma_lop</td>
                        <td><a href='Chuong.php?ma_lop=$entity->ma_lop'>" . str_pad($entity->ten_lop, 2, '0', STR_PAD_LEFT) . "</a></td>
                        <td>$entity->mo_ta</td>
                     </tr>";
        }
        $result = $result . "</table>";
        return $result;
    }

    function CreateForm($actionStr, $title, LopHocEntity $entity) {
        $result = "<form action='?ma_mon=$entity->ma_mon&action=$actionStr' method='post' class='myFormContainer'>
                <input type='hidden' name='txtMaLop' value='$entity->ma_lop' />
                <fieldset>
                    <legend class='myFormTitle'>$title</legend>
                    <table class='myFormTable'>
                        <tr>
                            <td><label for='txtTenLop' class='myFormLabel'>Tên lớp học:</label></td>
                            <td><input type='number' max='12' min='1' id='txtTenLop' name='txtTenLop' class='myFormField' required value='$entity->ten_lop' /></td>
                        </tr>

                        <tr>
                            <td><label for='txtMoTa' class='myFormLabel'>Mô tả:</label></td>
                            <td>
                                <textarea cols='70' rows='5' id='txtMoTa' name='txtMoTa' class='myFormField' required >$entity->mo_ta</textarea>
                            </td>
                        </tr>

                        <tr>
                            <td></td>
                            <td><input type='submit' name='submit' value='Submit' class='myFormButton' /></td>
                        </tr>
                    </table>
                </fieldset>
            </form>";

        return $result;
    }

    function CreateAddForm($ma_mon) {
        $ten_lop = '';
        $mo_ta = '';
        if (isset($_SESSION['user']['errors'])) {
            $ten_lop = $_SESSION['user']['errors']['ten_lop'];
            $mo_ta = $_SESSION['user']['errors']['mo_ta'];
        }
        return $this->CreateForm('insert', 'Thêm lớp học mới', new LopHocEntity(-1, $ma_mon, $ten_lop, $mo_ta));
    }

    function CreateUpdateForm($id) {
        $lop = $this->model->GetLopHocById($id);
        if (isset($_SESSION['user']['errors'])) {
            $lop->ten_lop = $_SESSION['user']['errors']['ten_lop'];
            $lop->mo_ta = $_SESSION['user']['errors']['mo_ta'];
        }
        return $this->CreateForm('update', 'Chỉnh sửa lớp học', $lop);
    }

}
