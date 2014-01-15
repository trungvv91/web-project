<script>
    function showConfirmDelete(ma_chuong, id)
    {
        var c = confirm("Bạn có chắc chắn muốn xóa dạng này?");
        if (c)
            window.location = "?ma_chuong=" + ma_chuong + "&action=delete&id=" + id;
    }

    function onChangeMonHoc(ma_mon) {
        window.location = "?ma_mon=" + ma_mon;
    }

    function onChangeLopHoc(ma_lop) {
        window.location = "?ma_lop=" + ma_lop;
    }

    function onChangeChuong(ma_chuong) {
        window.location = "?ma_chuong=" + ma_chuong;
    }
</script>

<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DangBaiView
 *
 * @author TRUNG
 */
class DangBaiView {

    private $model;

    function __construct(DangBaiModel $model) {
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

    function CreateChuongDropDownList($ma_lop, $selected_ma_chuong) {
        require_once '../models/ChuongModel.php';

        $chuongModel = new ChuongModel();
        $result = "<label for='ma_chuong' class='myFormLabel'>Chọn chương:</label>
                   <select id='ma_chuong' name='ma_chuong' onchange='onChangeChuong(this.value)' class='myFormDropDown'>"
                . "<option value='-1' selected></option>";
        foreach ($chuongModel->GetChuongByLop($ma_lop) as $chuong) {
            $result = $result . "<option value='$chuong->ma_chuong'";
            if ($chuong->ma_chuong == $selected_ma_chuong) {
                $result = $result . " selected ";
            }
            $result = $result . ">$chuong->ten_chuong</option>";
        }
        $result = $result . "</select>";

        return $result;
    }

    function CreateDangBaiTable($ma_chuong) {
        $list = $this->model->GetDangByChuong($ma_chuong);
        $result = "<table class='tblOverview'>
                        <tr>                        
                            <th style='width: 10%'></th>
                            <th style='width: 10%'></th>
                            <th style='width: 10%'>Dạng</th>
                            <th style='width: 30%'>Tên</th>
                            <th style='width: 40%'>Mô tả</th>
                        </tr>";

        foreach ($list as $key => $entity) {
            $result = $result . "<tr>
                        <td>
                            <a href='?ma_chuong=$ma_chuong&action=updateform&id=$entity->ma_dang'>
                                <image src='/images/edit/edit_16x16.png' alt='Sửa'>Sửa</image>
                            </a>
                        </td>
                        <td>
                            <a href='#' onclick='showConfirmDelete($ma_chuong,$entity->ma_dang)'>
                                <image src='/images/delete/delete_16x16.png' alt='Xóa'>Xóa</image>
                            </a>
                        </td>
                        <td>" . str_pad($entity->stt, 2, '0', STR_PAD_LEFT) . "</td>
                        <td>$entity->ten_dang</td>
                        <td>$entity->mo_ta</td>
                    </tr>";
        }
        $result = $result . "</table>";
        return $result;
    }

    function CreateSttDangDropDownList($ma_chuong, $selected_stt) {
        $result = "<select id='dropdownMaDang' name='dropdownMaDang' class='myFormDropDown'>";
        $count = $this->model->CountDangByChuong($ma_chuong);
        if (-1 == $selected_stt) {
            $count = $count + 1;
            $selected_stt = $count;
        }
        for ($i = 1; $i <= $count; $i++) {
            $result = $result . "<option value='$i'";
            if ($i == $selected_stt) {
                $result = $result . " selected ";
            }
            $result = $result . ">$i</option>";
        }
        $result = $result . "</select>";
        return $result;
    }

    function CreateForm($actionStr, $title, DangBaiEntity $entity) {
        $result = "<form action='?ma_chuong=$entity->ma_chuong&action=$actionStr' method='post' class='myFormContainer'>
                <input type='hidden' name='txtMaDang' value='$entity->ma_dang' />
                <fieldset>
                    <legend class='myFormTitle'>$title</legend>
                    <table class='myFormTable'>
                        <tr>
                            <td><label for='dropdownMaDang' class='myFormLabel'>Số thứ tự:</label></td>
                            <td>"
                . $this->CreateSttDangDropDownList($entity->ma_chuong, $entity->stt) . "</td>
                        </tr>

                        <tr>
                            <td><label for='txtTenDang' class='myFormLabel'>Tên dạng:</label></td>
                            <td><input type='text' id='txtTenDang' name='txtTenDang' class='myFormField' required value='$entity->ten_dang' /></td>
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
    
    function CreateAddForm($ma_chuong) {
        return $this->CreateForm('insert', 'Thêm dạng bài mới', new DangBaiEntity(-1, $ma_chuong, -1, '', ''));
    }

    function CreateUpdateForm($id) {
        $entity = $this->model->GetDangById($id);
        return $this->CreateForm('update', 'Chỉnh sửa dạng bài', $entity);
    }

}
