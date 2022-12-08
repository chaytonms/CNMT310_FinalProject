<?php
session_start();
require_once("../FormWizard.php");
require_once("../ValidationWizard.php");
require_once("../Template.php");

// Ensures an admin can't naviagte to manageclass.php, then to this page, and then back to manageclass.php
unset($_SESSION['manage']);

$FW = new FormWizard();
$VW = new ValidationWizard();
$template = new Template("Add Class");

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
print $template->beginHTML() . "<div class=\"m-5\">";
print $VW->checkSessionErrors($_SESSION);

print "<form action=\"submitAddClass.php\" method=\"POST\">
    <div class=\"container-fluid m-0 p-0\">";
print $FW->standardInput("Course Name:", "coursename", classes:"m-10");
print $FW->standardInput("Course Code:", "coursecode", classes:"m-10");
print $FW->standardInput("Course Number:", "coursenum", inputType:"number", classes:"m-10");
print $FW->standardInput("Course Credits:", "coursecredits", inputType:"number", classes:"m-10");
print $FW->standardInput("Course Description:", "coursedesc", classes:"m-10");
print $FW->standardInput("Course Instructor:", "courseinstr", classes:"m-10");
print $FW->standardInput("Meeting Times:", "meetingtimes", classes:"m-10");
print $FW->standardInput("Maximum Enrolls:", "maxenroll", inputType:"number", classes:"m-10");

print "        <div class=\"row m-0\">
            <div class=\"col m-0 p-0\">
                <input type=\"submit\" name=\"submitform\" value=\"Submit\"><br/>
            </div>
            <div class=\"col m-0 p-0\">
                <a href=\"dashboard.php\">BACK</a>
            </div>
        </div>
    </div>
</form>";
print '</div>' . $template->closeHTML();
?>