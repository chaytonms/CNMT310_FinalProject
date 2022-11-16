<?php

session_start();
require_once("../FormWizard.php");
require_once("../ValidationWizard.php");
$FW = new FormWizard();
$VW = new ValidationWizard();


// check if user is admin
function session_error() {
    $_SESSION['errors'] = array("Session Error");
    //print var_dump($_SESSION);
    die(header("Location: index.php"));
}

if (!isset($_SESSION) || !isset($_SESSION['user'])) {
    session_error();
}
$user = json_decode($_SESSION['user']);

if (!isset($user->user_role)) {
    session_error();
}

if ($user->user_role != "admin") {
    $_SESSION['errors'] = array("Page Forbidden");
    die(header("Location: dashboard.php"));
}

// ### data fields: ###
//    "coursename": "Principles of Computing", 
//     "coursecode": "CNMT", 
//     "coursenum": "100", 
//     "coursecredits": "3", 
//     "coursedesc": "Exploring the principles of computing", 
//    "courseinstr": "Simkins", 
//     "meetingtimes": "MW 11:00a-12:15p", 
//     "maxenroll": "24"  

// make form
print $VW->checkSessionErrors($_SESSION);

print "<form action=\"submitAddClass.php\" method=\"POST\">
    <div class=\"container-fluid m-0 p-0\">";
print $FW->standardInput("Course Name:", "coursename");
print $FW->standardInput("Course Code:", "coursecode");
print $FW->standardInput("Course Number:", "coursenum", inputType:"number");
print $FW->standardInput("Course Credits:", "coursecredits", inputType:"number");
print $FW->standardInput("Course Description:", "coursedesc");
print $FW->standardInput("Course Instructor:", "courseinstr");
print $FW->standardInput("Meeting Times:", "meetingtimes");
print $FW->standardInput("Maximum Enrolls:", "maxenroll", inputType:"number");

print "        <div class=\"row m-0\">
            <div class=\"col m-0 p-0\">
                <input type=\"submit\" name=\"submitform\" value=\"Submit\"><br/>
            </div>
        </div>
    </div>
</form>";

?>