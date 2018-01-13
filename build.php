<?php

include 'classes/GitHubAPIResponse.php';
include 'classes/GitLocal.php';
include 'classes/CIBuilder.php';

function getNextCIJob(string $directory): string
{
  if(!file_exists($directory) || !is_dir($directory)){
    return '';
  }

  // Getting files in queue
  $nextFiles = array_diff(scandir($directory), ['.','..']);
  if(count($nextFiles) > 0){
    $fileName = $directory.'/'.$nextFiles[array_keys($nextFiles)[0]];
    return file_get_contents($fileName);
  }

  return '';
}

$config = json_decode(file_get_contents('files/config.json'));

$nextJob = getNextCIJob('todo');
if($nextJob === ''){die();}
$apiResponse = new GitHubAPIResponse($nextJob);

// Checks if the repo is allowed to be build
if(!in_array($apiResponse->getFullRepoName(), $config->whitelisted_repos)){
  http_response_code(401);
  echo 'The repo is not whitelisted';
  die();
}

$repoName = $apiResponse->getRepoName();
// Clones the repository
$localGit = new GitLocal('/home/ci/Documents/GitRepos');
$newRepo = $localGit->clone($apiResponse->getCloneUrl(), $repoName);
if($newRepo){
  $localGit->checkout($repoName, $apiResponse->getCommit()->id);
}else{
  $localGit->fetch($repoName);
}

// Builds the tests
$ciBuilder = new CIBuilder('/home/ci/Documents/Build', $repoName);
$ciResult = $ciBuilder->runBuildScript();

file_put_contents('/var/www/html/files/ouput'.time().'.txt', join(PHP_EOL, $ciResult));
?>

This is the CI working!
