<?php

include 'classes/GitHubAPIResponse.php';
include 'classes/GitLocal.php';

if(!array_key_exists('payload', $_POST)){
  echo 'This is not a Github Request';
  throw new Exception('Not a github request');
}

$config = json_decode(file_get_contents('config.json'));

$apiResponse = new GitHubAPIResponse($_POST['payload']);
$secretToken = getallheaders()['X-Hub-Signature'];

if(!$apiResponse->secretMatches($secretToken, 'something')){
  http_response_code(401);
  echo 'The secret key does not match';
  die();
}


if(!in_array($apiResponse->getFullRepoName(), $config->whitelisted_repos)){
  http_response_code(401);
  echo 'The repo is not whitelisted';
  die();
}

$repoName = $apiResponse->getRepoName();

$localGit = new GitLocal('/home/ci/Documents/GitRepos');

$newRepo = $localGit->clone($apiResponse->getCloneUrl(), $repoName);
if(!$newRepo){
  $localGit->fetch($repoName);
}
$localGit->checkout($repoName, $apiResponse->getCommit()->id);
?>

This is the CI working!
