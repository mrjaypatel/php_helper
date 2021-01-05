<?php
@session_start();
$PATH_PREFIX = $_SESSION['lib_prefix'];
require_once __DIR__.$PATH_PREFIX."callme.php";



function preloader(){
  echo '<!-- 
        <div id="preloader">
            <div class="jumper">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div> -->';

};

// Get Blog Details for home page
function getBlogListHome(){
    $table = "blog_data";
    $condition = "";
    $blog = getAllData($table, $condition);


    // $id =       getColData("id", $table, $condition);
    // $title =    getColData("title", $table, $condition);
    // $display =  getColData("display", $table, $condition);
    // $detail =   getColData("detail", $table, $condition);
    // $tag =      getColData("tag", $table, $condition);
    // $catId =    getColData("category", $table, $condition);
    // $subCatId = getColData("sub_category", $table, $condition);
    $totalBlogs = count($blog);
    $BLOG_DETAIL_URL = "blog-detail.php";

    foreach($blog as $blg){   
        $display_text = "";
        if (preg_match('/^.{1,260}\b/s', $blg['display'] , $match))
        {
            $display_text=$match[0];
        }        
        echo '    
            <div class="col-lg-12">
            <div class="blog-post">
            <div class="down-content">
          
                <a href="'.$BLOG_DETAIL_URL.'"><h4>'.$blg['title'].'</h4></a>
                <!--
                <ul class="post-info">
                <li><a href="#">May 24, 2020</a></li>
                </ul>-->
                <p>
                '.$display_text.'... <a href="'.$BLOG_DETAIL_URL.'">Read More</a>
                </p>
                <div class="post-options">
                <div class="row">
                    <div class="col-6">
                    <ul class="post-tags">
                        <li><i class="fa fa-tags"></i></li>
                        <li><a href="#">Best Templates</a>,</li>
                        <li><a href="#">TemplateMo</a></li>
                    </ul>
                    </div>
                    <div class="col-6">
                    <ul class="post-share">

                    
                        <li>
                        <i class="fa fa-share-alt" style="font-size: 20px;"></i></li>
                        <li>';
                        socialShare("<i class='fa fa-facebook' style='font-size: 20px; color: #3b5998;margin: 5px;' ></i>",  $blg['title']."<br>".$display_text,$BLOG_DETAIL_URL, "fb");
                        echo '</li>
                        <li>';
                        socialShare("<i class='fa fa-whatsapp' style='font-size: 20px; color: #075e54;margin: 5px;'></i>",  $blg['title']."<br>".$display_text,$BLOG_DETAIL_URL, "wa");
                        echo '</li>
                    </ul>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
        ';
    }







}






?>