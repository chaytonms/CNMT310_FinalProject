<?php
/*
Page Description: Handles Jquery search dropdown functionality.
*/

require_once(__DIR__.'/../WebServiceClient.php');
require_once(__DIR__.'/../const.php');

// Validation
if (!isset($_POST['term']) || empty($_POST['term'])) {
  $_SESSION['errors'] = array("Page not accessible.");
  die(header("Location: dashboard.php"));
}

$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);
$data = array("apikey" => APIKEY,
              "apihash" => APIHASH,
              "action" => "searchcourses",
              "data" => array("term" => trim($_POST['term'])),
            );
$client->setPostFields($data);
$result = $client->send();
$jsonResult = json_decode($result);
$finalResult = array();
if ($jsonResult->result == "Success" && is_array($jsonResult->data) && count($jsonResult->data) > 0) {
  $count = 0;
  foreach ($jsonResult->data as $row) {
    $finalResult[$count]["label"] = $row->coursecode . " " . $row->coursenum . ": " . $row->coursename;
    $finalResult[$count]["name"] = $row->coursename;
    $finalResult[$count]["id"] = $row->id;
    $count++;
  }
  print json_encode($finalResult);
}
?>