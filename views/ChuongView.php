<script>
    function showConfirmDelete(ma_lop, id)
    {
        var c = confirm("Bạn có chắc chắn muốn xóa chương này?");
        if (c)
            window.location = "?ma_lop=" + ma_lop + "&action=delete&id=" + id;
    }

    function onChangeMonHoc(ma_mon) {
        window.location = "?ma_mon=" + ma_mon;
    }

    function onChangeLopHoc(ma_lop) {
        window.location = "?ma_lop=" + ma_lop;
    }
</script>


<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ChuongView
 *
 * @author TRUNG
 */
class ChuongView {

    private $model;

    function __construct(ChuongModel $model) {
        $this->model = $model;
    }

    function CreateMonHocDropDownList($selected_ma_mon) {
        require_once '../models/MonHocModel.php';

        $mhModel = new MonHocModel();
        $result = "<label for='ma_mon' class='myFormLabel'>Chọn môn học:</label>
                   <select id='ma_mon' name='ma_mon' onchange='onChangeMonHoc(this.value)' class='myFormDropDown'>"
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

    function CreateLopHocDropDownList($ma_mon, $selected_ma_lop) {
        require_once '../models/LopHocModel.php';

        $lopModel = new LopHocModel();
        $result = "<label for='ma_lop' class='myFormLabel'>Chọn lớp học:</label>
                   <select id='ma_lop' name='ma_lop' onchange='onChangeLopHoc(this.value)' class='myFormDropDown'>"
                . "<option value='-1' selected></option>";
        foreach ($lopModel->GetLopByMon($ma_mon) as $lop) {
            $result = $result . "<option value='$lop->ma_lop'";
            if ($lop->ma_lop == $selected_ma_lop) {
                $result = $result . " selected ";
            }
            $result = $result . ">$lop->ten_lop</option>";
        }
        $result = $result . "</select>";

        return $result;
    }

    function CreateChuongTable($ma_lop) {
        $list = $this->model->GetChuongByLop($ma_lop);
        $result = "<table class='tblOverview'>
                        <tr>                        
                            <th style='width: 10%'></th>
                            <th style='width: 10%'></th>
                            <th style='width: 5%'>HK</th>
                            <th style='width: 10%'>Chương</th>
                            <th style='width: 20%'>Tên</th>
                            <th style='width: 40%'>Mô tả</th>
                            <th style='width: 5%'></th>
                        </tr>";

        foreach ($list as $key => $entity) {
            $result = $result . "<tr>
                        <td>
                            <a href='?ma_lop=$ma_lop&action=updateform&id=$entity->ma_chuong'>
                                <image src='/images/edit/edit_16x16.png' alt='Sửa'>Sửa</image>
                            </a>
                        </td>
                        <td>
                            <a href='#' onclick='showConfirmDelete($ma_lop,$entity->ma_chuong)'>
                                <image src='/images/delete/delete_16x16.png' alt='Xóa'>Xóa</image>
                            </a>
                        </td>
                        <td>$entity->hoc_ky</td>
                        <td>" . str_pad($entity->stt, 2, '0', STR_PAD_LEFT) . "</td>
                        <td><a href='DangBai.php?ma_chuong=$entity->ma_lop'>$entity->ten_chuong</a></td>
                        <td>$entity->mo_ta</td>
                        <td>";
            if ($entity->stt > 1) {
                $result = $result . "<a href='?ma_lop=$ma_lop&action=swap&stt=$entity->stt'>"
                        . "<image src='/images/up/up_16x16.png' alt='Lên trên' /></a>";
            }
            $result = $result . "</td></tr>";
        }
        $result = $result . "</table>";
        return $result;
    }

    function CreateForm($actionStr, $title, ChuongEntity $entity) {
        $dropdownHocKy = "<select id='dropdownHocKy' name='dropdownHocKy' style='width:30px'>";
        for ($i = 1; $i <= 2; $i++) {
            $dropdownHocKy = $dropdownHocKy . "<option value='$i'";
            if ($i == $entity->hoc_ky) {
                $dropdownHocKy = $dropdownHocKy . " selected ";
            }
            $dropdownHocKy = $dropdownHocKy . ">$i</option>";
        }
        $result = "<form action='?ma_lop=$entity->ma_lop&action=$actionStr' method='post' class='myFormContainer'>
                <input type='hidden' name='txtMaChuong' value='$entity->ma_chuong' />
                <fieldset>
                    <legend class='myFormTitle'>$title</legend>
                    <table class='myFormTable'>
                        <tr>
                            <td><label for='dropdownHocKy' class='myFormLabel'>Học kỳ:</label></td>
                            <td>"
                . $dropdownHocKy . "</td>
                        </tr>

                        <tr>
                            <td><label for='txtTenChuong' class='myFormLabel'>Tên chương:</label></td>
                            <td><input type='text' id='txtTenChuong' name='txtTenChuong' class='myFormField' required value='$entity->ten_chuong' /></td>
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

    function CreateAddForm($ma_lop) {
        return $this->CreateForm('insert', 'Thêm chương mới', new ChuongEntity(-1, $ma_lop, 1, -1, '', ''));
    }

    function CreateUpdateForm($id) {
        $entity = $this->model->GetChuongById($id);
        return $this->CreateForm('update', 'Chỉnh sửa chương', $entity);
    }

}
