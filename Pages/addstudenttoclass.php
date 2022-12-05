<?php
    session_start();

    require_once("../FormWizard.php");
    require_once("../Template.php");
    require_once(__DIR__.'/../WebServiceClient.php');
    require_once(__DIR__.'/../const.php');


    $FW = new FormWizard();
    $tem = new Template("Add Class");

    $url = "http://cnmt310.classconvo.com/classreg/";
    $client = new WebServiceClient($url);

    if (!isset($_POST, $_POST['id'], $_SESSION['user'], json_decode($_SESSION['user'])->user_role)) {
        $_SESSION['errors'] = array("Session Error");
        die(header("Location: dashboard.php"));
    }   
    
	if(json_decode($_SESSION['user'])->user_role != "admin") {
        $_SESSION['errors'] = array("Page forbidden");
        unset($_SESSION["user"]); // this maims everything within the system except the login. Prevents everything essencially
        die(header("Location: index.php"));
    }


    $course_id = $_POST['id'];

    // Get Students In class (to be omitted)
    $postData = array("apikey" => APIKEY,
                 "apihash" => APIHASH,
                 "data" => array( "course_id" => $course_id ),
                 "action" => "getstudentlistbycourse"
                 );

    $client->setPostFields($postData);
    $json = (object) json_decode($client->send());

    if($json == null || !isset($json->result) || $json->result != "Success"){
        if((!is_array($json->data) && !isset($json->data->message)) || $json->data->message != "No students found"){
            deletionError();
            if ($json->result == "Error") {
                $_SESSION['errors'][] = $json->data->message;
            }
            die(header("Location: manageclass.php"));
        }
    }

    $studentsInClass = $json->data;

    $postData = array("apikey" => APIKEY,
                 "apihash" => APIHASH,
                 "data" => array(),
                 "action" => "getstudentlist"
                 );

    $client->setPostFields($postData);
    $json = (object) json_decode($client->send());

    if($json == null || !isset($json->result) || $json->result != "Success"){
        if((!is_array($json->data) && !isset($json->data->message)) || $json->data->message != "No students found"){
            deletionError();
            if ($json->result == "Error") {
                $_SESSION['errors'][] = $json->data->message;
            }
            die(header("Location: manageclass.php"));
        }
    }



    // PRINT PAGE

	print $tem->beginHTML();
	
	print '
	<h2>Manage Course</h2>
	<form method="POST" action="submitEnrollClass.php">
        <label for="student_id">Select a student</input>
        <select name="student_id">
        ';
    
    foreach ($studentsNotInClass as $s) {
        $sid = $s->student_id;
        print "<option value=$sid>$sid</option>";
    }
    
    print '</select>
        <input type="hidden" name="id" value="'. $course_id .'/>
		' . $FW->standardSubmit("dashboard.php", "Add Student to Course") . ' />
	</form>';
	
	print $tem->closeHTML();
?>