<?php

include 'classes/GitHubAPIResponse.php';
include 'classes/GitLocal.php';

if(!array_key_exists($_POST, 'payload')){
  echo 'This is not a Github Request';
  die();
}

$commit = new GitHubAPIResponse($_POST);
$localGit = new GitLocal(__DIR__.'/git');

$filename = 'testdata/'+time();
file_put_contents($filename, "Cloning ". $commit->getCloneUrl());

?>

This is the CI!
