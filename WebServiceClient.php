<?php

/* Woefully incomplete cURL class
 * that can be used as a web service client
 * for CNMT 310
 */

class WebServiceClient {

  private $_ch;
  private $_method = "POST";
  private $_url = null;
  private $_reqHeaders = array();
  private $_returnxfer = 1;
  private $_postFields;
  private $_contentLength;
  private $_contentType;
  private $_curlOptions = array(
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_HTTPHEADER => array(),
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => null,
  );

  public function __construct($url = null) {
    $this->_url = $url;
    $this->_curlOptions[CURLOPT_URL] = $url;
    $this->_ch = curl_init($url);
  } //end function construct

  public function setReturnTransfer($xfer) {
    $this->_curlOptions[CURLOPT_RETURNTRANSFER] = $xfer;
    $this->returnxfer = $xfer;
  }

  public function getReturnTransfer() {
    return $this->returnxfer;
  }

  public function setMethod($method) {
    $this->_curlOptions[CURLOPT_CUSTOMREQUEST] = $method;
    $this->_method = $method;
  }  //end function setMethod

  public function getMethod() {
    return $this->_method;
  }

  public function setURL($url) {
    $this->_curlOptions[CURLOPT_URL] = $url;
    $this->_url = $url;
  }

  public function getURL() {
    return $this->_url;
  }

  public function ignoreSSL() {
    $this->_curlOptions[CURLOPT_SSL_VERIFYPEER] = false;
  }

  public function setReqHeaders($header) {
    if (is_array($header)) {
      foreach ($header as $value) {
        array_push($this->_reqHeaders,$value);
      }
    } else {
      array_push($this->_reqHeaders,$header);
    }
  }

  public function getReqHeaders() {
    return $this->_reqHeaders;
  }

  public function send() {
    //this returns boolean, should check it
    $this->_curlOptions[CURLOPT_HTTPHEADER] = $this->_reqHeaders;
    foreach ($this->_curlOptions as $key => $value) {
      curl_setopt($this->_ch,$key,$value);
    }
    if (is_null($this->_url)) {
      return "Error: URL is not set";
    }
    $return = curl_exec($this->_ch);
    if ($return === false) {
      $return = "Error: " . curl_error($this->_ch);
    }
    $this->_reqHeaders = array();
    return $return;
  }

  public function setOption($option,$value) {
    $this->_curlOptions[$option] = $value;
    curl_setopt($this->_ch,$option,$value);
  }

  public function setPostFields($fields,$type="json") {
    if (!is_array($fields)) {
      return "Error: Send an array into setPostFields";
    }
    if ($type == "json") {
      $this->_postFields = json_encode($fields);
      $this->_contentType = "application/json";
    } else {
      $this->_postFields = $fields;
    }
    $this->_contentLength = strlen($this->_postFields);
    $header = "Content-Length: " . $this->_contentLength;
    $this->setReqHeaders($header);
    $this->setReqHeaders("Content-Type: " . $this->_contentType);
    $this->_curlOptions[CURLOPT_POSTFIELDS] = $this->_postFields;
  } //end setPostFields

  // Expects the return to contain  properties of "status" and "details"
  public function sendPostWrapper($data) {
    if (defined(DEBUG_WSADMIN) && DEBUG_WSADMIN === true) {
      error_log("Web Service Client Debug. data follows: ");
      var_error_log($data);
    }
    $this->setPostFields($data);
    $this->ignoreSSL();
    $result = $this->send();
    $decodedResult = json_decode($result);
    if (is_object($decodedResult)) {
      $errorCondition = json_last_error();
      if ($errorCondition === JSON_ERROR_NONE && property_exists($decodedResult,"status")
            && $decodedResult->status === "noerror" && property_exists($decodedResult,"details")) {
      return $decodedResult->details;
      } else {
        error_log("Web Service Client JSON Error: " . $errorCondition);
        var_error_log($data);
        var_error_log("Dump of Web Service Client Result: " . $result);
        return false;
      }
    } else {
      error_log("Web Service Client error: decodedResult is not an object");
      var_error_log("Dump of Web Service Client Result: " . $result);
      return false;
    }
  }

} //end class WebServiceClient

/**
* Example Usage:
*/
//<?php
//require_once("WebServiceClient.php");
//$url = "http://cnmt310.classconvo.com/classreg/";
//$client = new WebServiceClient($url);

// Default is to POST. If you need to change to a GET, here's how:
//$client->setMethod("GET");

//$apihash = "xxx";
//$apikey = "apixx";
//$action = "authenticate";
//$data = array("apikey" => $apikey,
//             "apihash" => $apihash,
//             "data" => array("username" => "user", "password" => "yourpass"),
//             "action" => $action
//             );
//$client->setPostFields($data);

//For Debugging:
//var_dump($client);

//print $client->send();
