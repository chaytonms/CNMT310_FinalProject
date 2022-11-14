<?php
session_start();
require_once(__DIR__.'/../SplitPageTemplate.php');
require_once(__DIR__.'/../TableTemplate.php');
require_once(__DIR__.'/../WebServiceClient.php');

if (!isset($_SESSION) || !isset($_SESSION['user'])) {
    $_SESSION['errors'] = array("Session Error");
    die(header("Location: index.php"));
}
$role = $_SESSION['user']->user_role;

$table = new TableTemplate();
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
print $template->closeLeftOpenRightPane();
print '<h4>TABLES</h4>';
//var_dump($_SESSION['user']);
$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);
//Default is to POST. If you need to change to a GET, here's how:


// TESTING USING ALL CLASSES SINCE STUDENTS AREN"T ENROLLED IN ANYTHING
$client->setMethod("GET");
$apihash = "fsfeguphgf"; // todo add into env
$apikey = "api6"; // todo add into env
$action = "listcourses";
$data = array("apikey" => $apikey,
             "apihash" => $apihash,
             "data" => array(),
             "action" => $action
             );
$client->setPostFields($data);
$json = (object) json_decode($client->send());
$allClasses = $json->data;

print $table->generateStudentEnrolledClasses($allClasses);
print $template->closeRightPane();
print $template->closeHTML();
?>