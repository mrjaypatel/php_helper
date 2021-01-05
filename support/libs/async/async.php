<?php
function btnReq($BUTTON_ID,
                $BUTTON_VALUE,
                $BUTTON_TYPE,
                $REQ_TARGET,
                $LOAD_PAGE = "load.php", 
                $RESPONSE_ID= "home_with_icon_title",
                $BTN_LABEL="Click", 
                $TEST = false, 
                $ICON ="delete"){   
        echo '
        <button class="btn btn-'.$BUTTON_TYPE.' waves-effect" onclick="callMe'.$BUTTON_ID.'('.$BUTTON_VALUE.')"><i class="material-icons">'.$ICON.'</i>'.$BTN_LABEL.'</button>
            <script>
            function callMe'.$BUTTON_ID.'(id){
               
                 $.post("'.$REQ_TARGET.'",
                  {
                    id: id
                  },
                  function(data, status){                    
                    ';                 
                    if($TEST){
                        echo 'console.log("Error Response:[ "+data+" ]");';
                    }   
                    loadReq("NA", $RESPONSE_ID, $LOAD_PAGE, false); 
                    msg("Task success!","success",false);
                    echo '
                  });
            } 
            
            </script>';

               
    
}

function formReq($ID = array(), $VALIDATE = array(), $TARGET,$MODE="run",$REDIRECT = "NA"){
    $FORM_ID     = $ID['form_id']; 
    $RESPONSE_ID = $ID['response_id'];
    $LOADER_ID   = $ID['loader_id'];

    if ($MODE == "test") {
       echo '<script type="text/javascript">
            $("#'.$FORM_ID.'").on("submit", function(e) {
            e.preventDefault(); //Prevents default submit
            var errors = 0;
            ';

            if(count($VALIDATE) > 0){
            foreach ($VALIDATE as $ME) {
                echo 'if($("#'.$ME.'").val() == 0){
                    console.log("Empty: '.$ME.'");
                    errors++;
               }';
            }
            
            echo 'if(errors > 0){';
                msg("Please Fill All required data field!","warning",false);
            echo '
                $("#'.$RESPONSE_ID.'").text("All fields are required!");
                return false;
            }
            var form = $(this); 
            var post_url = form.attr(\'action\'); 
            var post_data = form.serialize(); //Serialized the form data for process.php
            $("#'.$LOADER_ID.'").html(\'<img src="../images/loader.gif"/>\');
            $.ajax({
                type: \'POST\',
                url: \''.$TARGET.'\', // Your form script
                data: post_data,
                success: function(msg) {
                    

                    $(\'#'.$RESPONSE_ID.'\').text("");
                    
                    console.log("Acync Response: [ "+ msg +" ]");
                     
                    ';       

                    if($REDIRECT != "NA"){
                        echo 'window.location="'.$REDIRECT.'"';
                    }
                    msg("Task Completed!","success",false);
                    echo '
                    
                    $("#'.$FORM_ID.'").trigger("reset");
                    $("#'.$RESPONSE_ID.'").html("");
                    $("#'.$LOADER_ID.'").html("");
                },
                error: function (jqXHR, exception) {
                    console.log(jqXHR);
                    getErrorMessage(jqXHR, exception);
                },
            });
        });
        </script>';
    }
}else{
            echo '<script type="text/javascript">
            $("#'.$FORM_ID.'").on("submit", function(e) {
            e.preventDefault(); //Prevents default submit
            var errors = 0;
            ';

        if(count($VALIDATE) > 0){
            foreach ($VALIDATE as $ME) {
                echo 'if($("#'.$ME.'").val() == 0){
                    console.log("Empty: '.$ME.'");
                    errors++;
               }';
            }
            
            echo 'if(errors > 0){';
                msg("Please Fill All required data field!","warning",false);
            echo '
                $("#'.$RESPONSE_ID.'").text("All fields are required!");
                return false;
            }
            var form = $(this); 
            var post_url = form.attr(\'action\'); 
            var post_data = form.serialize(); //Serialized the form data for process.php
            $("#'.$LOADER_ID.'").html(\'<img src="../images/loader.gif"/>\');
            $.ajax({
                type: \'POST\',
                url: \''.$TARGET.'\', // Your form script
                data: post_data,
                success: function(msg) {
                    console.log("Async Task complete!");
                    $(\'#'.$RESPONSE_ID.'\').text("");
                   
                    ';            
                     if($REDIRECT != "NA"){
                        echo 'window.location="'.$REDIRECT.'"';
                    }
                    msg("Task Completed!","success",false);
                    echo '
                    $("#'.$FORM_ID.'").trigger("reset");
                    $("#'.$RESPONSE_ID.'").html("");
                    $("#'.$LOADER_ID.'").html("");
                },
                error: function (jqXHR, exception) {
                    console.log(jqXHR);
                    getErrorMessage(jqXHR, exception);
                },
            });
        });
        </script>';
    }
    }



}

function loadReq($REQUEST_ID = "NA", $RESPONSE_ID, $TARGET,  $SCRIPT = true){
    if ($REQUEST_ID != "NA") {
        if($SCRIPT){
         echo '<script type="text/javascript">
                    $(\''.$REQUEST_ID.'\').click(function(){
                       console.log("Simple Req!");
                       ajaxReq_'.$RESPONSE_ID.'_();
                      });
                </script>';
                loadMe($TARGET, $RESPONSE_ID);
        }else{
             echo '
                    $(\''.$REQUEST_ID.'\').click(function(){
                       console.log("Simple Req!");
                       ajaxReq'.$RESPONSE_ID.'();
                      });
                ';
            loadMe($TARGET, $RESPONSE_ID, false);
        }       
    }else{
        if($SCRIPT){
         echo '<script type="text/javascript">                    
                   console.log("Simple Req!");
                   ajaxReq_'.$RESPONSE_ID.'_();                      
                </script>';
                loadMe($TARGET, $RESPONSE_ID);
        }else{
             echo '
                   console.log("Simple Req!");
                   ajaxReq_'.$RESPONSE_ID.'_();
                     ';
            loadMe($TARGET, $RESPONSE_ID, false);
        }       
    }

} 





function loadMe($TARGET, $RESPONSE_ID, $SCRIPT = true){
    if($SCRIPT){
    echo '
        <script>
        function ajaxReq_'.$RESPONSE_ID.'_(){
                 console.log("Reload Success! ");
                 $.ajax({
                    type: \'POST\',
                    url: \''.$TARGET.'\', 
                    data: {id: 1},
                    success: function(msg) {
                        $("#'.$RESPONSE_ID.'").html(msg);
                    },
                    error: function (jqXHR, exception) {
                        console.log(jqXHR);
                       var msg =  getErrorMessage(jqXHR, exception);
                       $("#'.$RESPONSE_ID.'").html(msg)
                    },
                });
            }
        </script>
    ';
    }else{
      echo '
            function ajaxReq_'.$RESPONSE_ID.'_(){
                     console.log("Reload Success! ");
                     $.ajax({
                        type: \'POST\',
                        url: \''.$TARGET.'\', 
                        data: {id: 1},
                        success: function(msg) {
                            $("#'.$RESPONSE_ID.'").html(msg);
                        },
                        error: function (jqXHR, exception) {
                            console.log(jqXHR);
                           var msg =  getErrorMessage(jqXHR, exception);
                           $("#'.$RESPONSE_ID.'").html(msg)
                        },
                    });
                }
        ';      
    }
    
}

function simplePost($REQ_PAGE, $PAYLOAD,$ACTION_CODE=""){
    echo ' 
<script type="text/javascript">
   // and remember the jqxhr object for this request
  var jqxhr = $.post( "'.$REQ_PAGE.'",'.$PAYLOAD.', function(response) {
    '.$ACTION_CODE.'
    console.log("Success");

  })
  .fail(function() {
    console.log("Fail");
    console.log(response);
  });
 
 </script>';
}
    //<script src="form.min.js"></script>
    //<link rel="stylesheet" type="text/css" href="form_style.css">
function fileUpload(){    
echo "
<script type=\"text/javascript\">
$(document).ready(function() { 
   $('#uploadForm').submit(function(e) {  
    if($('#userImage').val()) {
      e.preventDefault();
      $('#loader-icon').show();
      $(this).ajaxSubmit({ 
        target:   '#targetLayer', 
        beforeSubmit: function() {
          $(\"#progress-bar\").width('0%');
        },
        uploadProgress: function (event, position, total, percentComplete){ 
          $('#progress-bar').width(percentComplete + '%');
          $('#progress-bar').html('<div id=\"progress-status\">' + percentComplete +' %</div>')
        },
        success:function (){
          $('#loader-icon').hide();
        },
        resetForm: true 
      }); 
      return false; 
    }
  });
});
</script>";
echo '    
<form id="uploadForm" action="upload.php" method="post">
  <div class="row">
    <div class="col-md-6">
      <div class="form-line">
        <label class="form-label">Select Task</label>
        <select name="taskId" class="form-control" required="true">
        <option>--Select Task--</option>';
          $APP_USER = $_SESSION['app_user'];
          $taskId = array();
          $taskId = getColData("task_id", "worklist", "WHERE `user_id`=\"".$APP_USER."\" AND `status` =\"Taken\" ");
          $TOTAL_TASKS = count($taskId);

          $taskTitle = array();
          for($i = 0; $i < $TOTAL_TASKS; $i++){ 
            $taskTitle = getColData("task_title", "tasks", "WHERE `id`=\"".$taskId[$i]."\" AND `status` =\"TODO\" OR `status` = \"TAKEN\" ");
            echo '<option value="'.$taskId[$i].'">'.$taskTitle[0]."</option>";          
          }         
        echo '
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group form-line">
        <label class="form-label">Comments</label>
        <textarea class="form-control" name="comments" required="true"></textarea>
      </div>
    </div>
    <div class="col-md-12">
      <div class="form-group form-line">
        <label class="form-label">Upload File</label>
        <input name="file" id="userImage" type="file" class="form-control" required="true"/>
      </div>
      <input type="submit" id="btnSubmit" value="Push Task" class="btn btn-success" />
      <div id="progress-div">
          <div id="progress-bar"></div>
      </div>
      <div id="targetLayer"></div>
    </div>
  </div>
</form>
<div id="loader-icon" style="display:none;">
    <img src="LoaderIcon.gif" alt="Processing Please Wait..." />
</div>
';
}


  //FileUpload API
  function _fileUpload($FILE_CONFIG = array()){
    $FILE_ID       = $FILE_CONFIG['file_id'];
    $FILE_NAME     = $FILE_CONFIG['file_name'];
    $UPLOAD_BTN_ID = $FILE_CONFIG['upload_btn_id']; 
    $UPLOAD_PATH   = $FILE_CONFIG['dest'];
    $UPLOAD_PAGE   = $FILE_CONFIG['createPage'];

    echo '
    <script type="text/javascript">
      const inputFiles = document.getElementById("'.$FILE_ID.'");
      const btnUp = document.getElementById("'.$UPLOAD_BTN_ID.'");
      btnUp.addEventListener("click", function() {
        const xhr = new XMLHttpRequest();
        const formData = new FormData();
        for(const file of '.$FILE_ID.'.files){
          formData.append("'.$FILE_NAME.'[]", file);
        }
        formData.append("fileName","'.$FILE_NAME.'");
        formData.append("uploadPath","'.$UPLOAD_PATH.'");
        ';
    echo '
        xhr.open("post", "'.$UPLOAD_PAGE.'");
        xhr.send(formData);
      });

    </script>
    ';

  }


  


?>