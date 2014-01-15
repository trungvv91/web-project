<?php

/**
 * Hiển thị giao diện đa ngôn ngữ của website
 */
class LanguageHelper {
    
    /**
     * Kiểm tra xem user chọn hiển thị ngôn ngữ gì từ Dropdownlist
     * Nếu chọn Tiếng Việt thì hiển thị Tiếng Việt, ngược lại thì hiển thị Tiếng Anh
     * Mặc định ban đầu là Tiếng Việt
     */
    function checkLang() {
        if (isset($_GET['lang'])) {
            $lang = test_input($_GET['lang']);
            return "languages/$lang.lng.php";
        } else {
            return "languages/vi.lng.php";
        }
    }

}
?>