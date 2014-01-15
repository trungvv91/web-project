<?php // defined('ABSPATH') or define('ABSPATH', dirname(__FILE__), '\\/');                 ?>
<!DOCTYPE html>
<html>
    <head>        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" type="text/css" href="/css/style.css" />
        <link rel="stylesheet" type="text/css" href="/css/ddsmoothmenu.css" />
        <link rel="stylesheet" type="text/css" href="/css/ddsmoothmenu-v.css" />

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
        <!--<script src="jquery.plugin.js"></script>-->
        <!--<script type="text/javascript" src="/js/autogrow.js"></script>-->
        <script type="text/javascript" src="/js/ddsmoothmenu.js">

            /***********************************************
             * Smooth Navigational Menu- (c) Dynamic Drive DHTML code library (www.dynamicdrive.com)
             * This notice MUST stay intact for legal use
             * Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
             ***********************************************/

        </script>

        <script type="text/javascript">

            ddsmoothmenu.init({
                mainmenuid: "smoothmenu1", //menu DIV id
                orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
                classname: 'ddsmoothmenu', //class added to menu's outer DIV
                //customtheme: ["#1c5a80", "#18374a"],
                contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
            });

            ddsmoothmenu.init({
                mainmenuid: "smoothmenu2", //Menu DIV id
                orientation: 'v', //Horizontal or vertical menu: Set to "h" or "v"
                classname: 'ddsmoothmenu-v', //class added to menu's outer DIV
                method: 'toggle', // set to 'hover' (default) or 'toggle'
                arrowswap: true, // enable rollover effect on menu arrow images?
                //customtheme: ["#804000", "#482400"],
                contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
            });

            function changeValue(val)
            {
                if (val === "vi") {
                    window.location.href = "?lang=vi";
                }
                else if (val === "en") {
                    window.location.href = "?lang=en";
                }
            }
            
//            $(document).ready(function() {
//                $('.myTextArea').autogrow();
//                <td><textarea readonly class='myTextArea'>$mh->mo_ta</textarea></td>
//            });

        </script>

    </head>

    <body>

        <div class="main">
            <!-- TOP : start -->
            <div class="top">
                <div class="languages">
                    <div> Languages: </div>
                    <a href="#" onclick="changeValue('vi')">Vn</a>
                    <a href="#" onclick="changeValue('en')">En</a>                    
                </div>
                <div class="search">
                    <div class="search_text">
                        <a href="#" >Advanced Search:</a>
                    </div>
                    <input type="text" class="search_input" name="search" />
                    <input type="image" src="/images/search.jpg" class="search_btn" />
                </div>
            </div>
            <!-- TOP : end -->  

            <!-- HEADER : start -->  
            <div class="header">
                <div id="smoothmenu1" class="ddsmoothmenu">
                    <ul>
                        <li><a href="/index.php">Trang chủ</a></li>
                        <li><a href="#">Làm bài trắc nghiệm</a></li> 
                        <li><a href="#">Học online</a></li>                                    
                        <li><a href="#">Đăng nhập</a></li>                                    
                        <li><a href="/quan_tri/default.php">Quản lý website</a></li>                                    
                    </ul>
                    <br style="clear: left" />
                </div>
            </div>
            <!-- HEADER : end -->  

            <!-- CONTENT : start -->  
            <br style="clear: left" />
            <div class="content">
                <table>
                    <tr>
                        <td style="width: 20%; vertical-align: text-top;">
                            <!-- LEFT CONTENT : start -->  
                            <div id="smoothmenu2" class="ddsmoothmenu-v">
                                <ul>
                                    <?php
                                    if (isset($leftmenu)) {
                                        foreach ($leftmenu as $name => $link) {
                                            ?>
                                            <li><a href="<?php echo $link; ?>"><?php echo $name; ?></a></li>      
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                                <br style = "clear: left" />
                            </div>
                            <!-- LEFT CONTENT : start -->  
                        </td>
                        <td style="width: 60%">
                            <div class="center_body">
                                <?php echo $content ?>                                
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <!--CONTENT : end -->


            <!--FOOTER : start -->
            <div class = "footer"></div>
            <!--FOOTER : end -->
        </div>
    </body>

    <!-- Enable support for the placeholder attribute in INPUT fields -->
    <script type="text/javascript">

        // ref: http://diveintohtml5.org/detect.html
        function supports_input_placeholder()
        {
            var i = document.createElement('input');
            return 'placeholder' in i;
        }

        if (!supports_input_placeholder()) {
            var fields = document.getElementsByTagName('INPUT');
            for (var i = 0; i < fields.length; i++) {
                if (fields[i].hasAttribute('placeholder')) {
                    fields[i].defaultValue = fields[i].getAttribute('placeholder');
                    fields[i].onfocus = function() {
                        if (this.value == this.defaultValue)
                            this.value = '';
                    }
                    fields[i].onblur = function() {
                        if (this.value == '')
                            this.value = this.defaultValue;
                    }
                }
            }
        }

    </script>
</html>

