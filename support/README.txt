Version: 1.0
Info: 
 - This system is support system for any application which use PHP as either front-end Support or back-end support.





### 
#1> How to work with support lib?
    NOTE: With this configuration you can call upto parent 5 DIR's!
    a) Place Support dir in Home directory.
    b) Now Call set session variable in working dir for getting know from where are you calling support dir!
        EX: 
        -------------------------------------------
            @session_start();
            $_SESSION['from'] = "0,1,2,3...5";
        --------------------------------------------
        - If I want to call support lib from `app/dir1/dir2/dir3` and support lib is at `app/support`
            @session_start();
            $_SESSION['from'] = "3";
        --------------------------------------------
    c) After setting session now need to give actual path to support lib
        EX:
        --------------------------------------------
            0 = /
            1 = /../
            2 = /../../
            3 = /../../../
            4 = /../../../../
            5 = /../../../../../
            
            require_once __DIR__."(0,1,2...5)support/callme.php";
        --------------------------------------------
        - If I want to call support lib from `app/dir1/dir2/dir3` and support lib is at `app/support`
            require_once __DIR__."/../../../support/callme.php";
        --------------------------------------------       
    
    $$$ Yeh you are done with all setup!

Full EXAMPLE
            @session_start();
            $_SESSION['from'] = "3";
            require_once __DIR__."/../../../support/callme.php";


 Call this script

 Out Side Folder
 ## App/index.php
 ## require_once __DIR__."/support/callme.php";

 From Folder
 ## App/dir/index.php
 @session_start();
 $_SESSION['from'] = "dir";
 require_once __DIR__."/../support/callme.php";