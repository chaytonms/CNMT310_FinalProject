<?php
session_start();
require_once(__DIR__.'/../SplitPageTemplate.php');

if (!isset($_SESSION) || !isset($_SESSION['role'])) {
    $_SESSION['errors'] = array("Session Error");
    die(header("Location: index.php"));
}
$role = $_SESSION['role'];

$template = new SplitPageTemplate("Auth");

print $template->beginHTML() . $template->openLeftPane();

print "<h4>Welcome!</h4><br/>
<p>Detected role: $role</p>
<form action=\"search.php\" method=\"POST\">
    <div class=\"container-fluid m-0 p-0\">
        <div class=\"row m-0 p-0\">
            <div class=\"col m-0 p-0\">
                <p>Class:</p>
            </div>
            <div class=\"col m-0 p-0\">
                <input class=\"float-end\" name=\"class\" for=\"class\"/>
            </div>
        </div>
        <div class=\"row m-0 p-0\">
            <div class=\"col m-0 p-0\">
                <p>Major:</p>
            </div>
            <div class=\"col m-0 p-0\">
                <input class=\"float-end\" name=\"major\" for=\"major\"/>
            </div>
        </div>
        <div class=\"row m-0\">
            <div class=\"col m-0 p-0\">
                <input type=\"submit\" name=\"submitform\" value=\"Submit\"><br/>
            </div>
        </div>
    </div>
</form>

";
print $template->closeLeftOpenRightPane() . '<h4>TABLES</h4>' . $template->closeRightPane();
print $template->closeHTML();
?>