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

    if (!isset($_GET) || !isset($_GET['id'])) {
        $_SESSION['errors'] = array("Class id not recognized");
        die(header("Location: dashboard.php"));
    }

    $course_id = $_GET['id'];
    $_SESSION['deleteId'] = $course_id;

    // make form
    print $template->beginHTML() . "<div class=\"m-5\">";
    print "<form action=\"submitDeleteClass.php\" method=\"POST\">
        <p>Are you sure you want to delete the class?</p>
        <div class=\"container\"> 
            <div class=\"row m-0\">
                <div class=\"col m-0 p-0\">
                    <input type=\"submit\" name=\"submitform\" value=\"Submit\"><br/>
                </div>
                <div class=\"col m-0 p-0\">
                    <a href=\"dashboard.php\">BACK</a>
                </div>
            </div>
        </div>
    
    </form>
    "


?>