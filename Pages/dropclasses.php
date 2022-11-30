<?php

    session_start();
    require_once("../FormWizard.php");
    require_once("../ValidationWizard.php");
    require_once("../Template.php");
    require_once(__DIR__.'/../WebServiceClient.php');
    require_once(__DIR__.'/../const.php');

    $FW = new FormWizard();
    $VW = new ValidationWizard();
    $template = new Template("Add Class");


    // check if user is admin
    function session_error() {
        $_SESSION['errors'] = array("Session Error");
        die(header("Location: index.php"));
    }

    if (!isset($_POST) || !isset($_POST['code'])) {
        $_SESSION['errors'] = array("Select a class to drop");
        die(header("Location: dashboard.php"));
    }

    if (!isset($_SESSION) || !isset($_SESSION['user'])) {
        session_error();
    }
    $user = json_decode($_SESSION['user']);

    if (!isset($user->user_role)) {
        session_error();
    }

    if ($user->user_role != "student") {
        $_SESSION['errors'] = array("Page Forbidden");
        die(header("Location: dashboard.php"));
    }

    if (!is_array($_POST['code']) || count($_POST['code']) <= 0) {
        $_SESSION['errors'] = array("Select classes to drop using the checkboxes");
        die(header("Location: dashboard.php"));
    }

    $url = "http://cnmt310.classconvo.com/classreg/";
    $client = new WebServiceClient($url);

    $postData = array("apikey" => APIKEY,
             "apihash" => APIHASH,
             "data" => array(),
             "action" => "listcourses"
             );


    $client->setPostFields($postData);
    $json = (object) json_decode($client->send());

    if ($json == null || !isset($json->result) || $json->result != "Success") { // might need more checks
        $_SESSION['errors'] = array("Error with fetching class", json_encode($json));
        die(header("Location: addClass.php"));
    } 

    $classArray = $_POST['code'];
    $classes = $json->data;
    $html = "";
    print (var_dump($_POST));
    foreach ($classes as $c) {
        var_dump($c);
        if (in_array("".$c->id, $classArray)) {
            $html .= "<p>" . $c->coursename . "</p><br />";
        }
    }

    $closer = (count($classArray) > 1) ? ("es?</h2>") : ("?</h2>");
    print $template->beginHTML() . "<div class=\"m-5\"><h2>Are you sure you want to drop the following class" . $closer;

    print $VW->checkSessionErrors($_SESSION);
    print "<div class=\"row\">" . $html . "</div>";
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