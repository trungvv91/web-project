<script>
    function showConfirmDelete(id)
    {
        var c = confirm("Bạn có chắc chắn muốn xóa môn học này?")
        if (c)
            window.location = "?action=delete&id=" + id;
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
 * Description of MonHocController
 *
 * @author TRUNG
 */
class MonHocView {

    private $model;

    function __construct(MonHocModel $model) {
        $this->model = $model;
    }

    function CreateMonHocTable() {
        $mhArray = $this->model->GetMonHocAll();
        $result = "<table class='tblOverview'>
                        <tr>                        
                            <th style='width: 10%'></th>
                            <th style='width: 10%'></th>
                            <th style='width: 10%'>Mã</th>
                            <th style='width: 30%'>Tên môn học</th>
                            <th style='width: 40%'>Mô tả</th>
                        </tr>";

        foreach ($mhArray as $key => $entity) {
            $result = $result . "<tr>
                        <td>
                            <a href='?action=updateform&id=$entity->ma_mon'>
                                <image src='/images/edit/edit_16x16.png' alt='Sửa'>Sửa</image>
                            </a>
                        </td>
                        <td>
                            <a href='#' onclick='showConfirmDelete($entity->ma_mon)'>
                                <image src='/images/delete/delete_16x16.png' alt='Xóa'>Xóa</image>
                            </a>
                        </td>
                        <td>" . str_pad($entity->ma_mon, 2, '0', STR_PAD_LEFT) . "</td>
                        <td><a href='LopHoc.php?ma_mon=$entity->ma_mon'>$entity->ten_mon</a></td>
                        <td>$entity->mo_ta</td>
                     </tr>";
        }
        $result = $result . "</table>";
        return $result;
    }

    function CreateForm($actionStr, $title, MonHocEntity $entity = null) {
        if (isset($entity)) {
            $ma_mon = $entity->ma_mon;
            $ten_mon = $entity->ten_mon;
            $mo_ta = $entity->mo_ta;
        } else {
            $ma_mon = '';
            $ten_mon = '';
            $mo_ta = '';
        }
        $result = "<form action='?action=$actionStr' method='post' class='myFormContainer'>
                <input type='hidden' name='txtMaMon' value='$ma_mon' />
                <fieldset>
                    <legend class='myFormTitle'>$title</legend>
                    <table class='myFormTable'>
                        <tr>
                            <td><label for='txtTenMon' class='myFormLabel'>Tên môn học:</label></td>
                            <td><input type='text' id='txtTenMon' name='txtTenMon' class='myFormField' required value='$ten_mon' /></td>
                        </tr>

                        <tr>
                            <td><label for='txtMoTa' class='myFormLabel'>Mô tả:</label></td>
                            <td>
                                <textarea cols='70' rows='5' id='txtMoTa' name='txtMoTa' class='myFormField' required placeholder='mô tả chi tiết về môn học' >$mo_ta</textarea>
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

    function CreateAddForm() {
        return $this->CreateForm('insert', 'Thêm môn học mới');
    }

    function CreateUpdateForm($id) {
        $entity = $this->model->GetMonHocById($id);
        return $this->CreateForm('update', 'Chỉnh sửa môn học', $entity);
    }

}
