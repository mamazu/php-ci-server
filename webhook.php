<?php
include 'classes/GitHubAPIResponse.php';

// Checks if the request was a github request
if(empty($_POST) || !array_key_exists('payload', $_POST)){
  echo 'This is not a Github Request';
  throw new Exception('Not a github request');
}

// Checking the GitHub token
$config = json_decode(file_get_contents('files/config.json'));
$secretToken = getallheaders()['X-Hub-Signature'];

if(!GitHubAPIResponse::secretMatches($secretToken, $config->secret_key)){
  http_response_code(401);
  echo 'The secret key does not match';
  die();
}

$fileName = 'buildjob'.time().'.json';
$fileSaved = file_put_contents("todo/$fileName", $_POST);

if($fileSaved){
    echo "The event was registered in the event queue. We'll get back to you.";
}else{
    echo "Could not save the file. Please contact the administrator.";
}