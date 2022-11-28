<?php
session_start();
require_once(__DIR__.'/../SplitPageTemplate.php');
require_once(__DIR__.'/../TableTemplate.php');
require_once(__DIR__.'/../WebServiceClient.php');
require_once(__DIR__.'/../const.php');
require_once(__DIR__.'/../ValidationWizard.php');

$VW = new ValidationWizard();
$table = new TableTemplate();

$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);
$client->setMethod("GET");

function session_error() {
    $_SESSION['errors'] = array("Session Error");
    die(header("Location: index.php"));
}

if (!isset($_SESSION) || !isset($_SESSION['user'])) {
    session_error();
}
$user = json_decode($_SESSION['user']);

if (!isset($user->user_role)) {
    session_error();
}

$role = $user->user_role;

$template = new SplitPageTemplate("Auth");

print $template->beginHTML() . $template->openLeftPane();

print "<h4>Welcome!</h4><br/>";
print $VW->checkSessionErrors($_SESSION);
print $VW->checkSessionSuccesses($_SESSION);

print "<p>Detected role: $role</p>
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
if($role === "admin"){
    print '<h4>Course List <a href="addClass.php">Add Class</a></h4>';
    $action = "listcourses";
    $data = array("apikey" => APIKEY,
             "apihash" => APIHASH,
             "data" => array(),
             "action" => $action
             );
    $client->setPostFields($data);
    $json = (object) json_decode($client->send());
    if($json->result === "Success" && isset($json->data)){
        if(sizeof($json->data) == 0){
            print '<p> There are currently no classes.</p>';
        }else{
            print $table->createAdminDashboardClassTable($json->data);
        }
    }
} else if($role === "student"){
    print '<h4>Enrolled Coures</h4>';
    $action = "getstudentcourses";
    $data = array("apikey" => APIKEY,
             "apihash" => APIHASH,
             "data" => array("student_id" => $user->id),
             "action" => $action
             );
    $client->setPostFields($data);
    $json = (object) json_decode($client->send());
    if($json->result === "Success" && isset($json->data)){
        if(sizeof($json->data) == 0){
            print '<p>You currently are not enrolled in any courses.</p>';
        }else{
            print $table->createStudentDashboardClassTable($json->data, true);
        }
    }
}
print $template->closeRightPane();
print $template->closeHTML();
unset($_SESSION['errors']);
unset($_SESSION['successes']);
?>