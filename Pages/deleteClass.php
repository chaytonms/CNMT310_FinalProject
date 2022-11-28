<?php
session_start();

require_once("../FormWizard.php");
require_once("../ValidationWizard.php");
require_once("../Template.php");

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

if (!isset($_POST) || !isset($_POST['Delete'])) {
    $_SESSION['errors'] = array("Class id not recognized");
    //die(header("Location: dashboard.php"));
    var_dump($_POST);
}

$course_id = $_POST['Delete'];
$_SESSION['deleteId'] = $course_id;

// make form
print $template->beginHTML() . "<div class=\"m-5\">";
print "<form action=\"submitDeleteClass.php\" method=\"POST\">
    <h1>DELETE CLASS</h1>
    <p>Are you sure you want to delete the class? It cannot be undone</p>
    <div class=\"container\"> 
        <div class=\"row m-0\">
            <div class=\"col m-0 p-0\">
                <input type=\"submit\" name=\"submitform\" value=\"Delete\"><br/>
            </div>
            <div class=\"col m-0 p-0\">
                <a href=\"dashboard.php\">BACK</a>
            </div>
        </div>
    </div>

</form>
"


?>