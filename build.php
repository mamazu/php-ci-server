<?php

include 'classes/GitHubAPIResponse.php';
include 'classes/GitLocal.php';
include 'classes/CIBuilder.php';
include 'classes/JobManager.php';

$BASEDIR = __DIR__;

function echoMessage($message){
  $timeStamp = date('Y-m-d H:i:s');
  echo "[$timeStamp] $message\n";
}

// Check if the CI is currently working
$jobManager = new JobManager($BASEDIR.'/files');
if($jobManager->ciIsWorking()){
  echoMessage('CI is currently busy. Please try again later.');
  die();
}

if(!$jobManager->hasNextJob()){
  echoMessage('There are no build jobs queued.');
  die();
}

// Gets the next job
$nextJobFileName = $jobManager->getNextJob();
$nextJob = file_get_contents($nextJobFileName);
$jobName = basename($nextJobFileName);

$config = json_decode(file_get_contents($BASEDIR.'/files/config.json'));
$apiResponse = new GitHubAPIResponse($nextJob);

// Checks if the repo is allowed to be build
if(!in_array($apiResponse->getFullRepoName(), $config->whitelisted_repos)){  
  $jobManager->writeJobOutput('Repo is not whitelisted.', $jobName);
  $jobManager->markDone($jobName);
  die();
}

$repoName = $apiResponse->getRepoName();
// Clones the repository
$localGit = new GitLocal($config->paths->git_directory);
$newRepo = $localGit->clone($apiResponse->getCloneUrl(), $repoName);

if(!$newRepo) {
  $localGit->fetch($repoName);
}

$localGit->checkout($repoName, $apiResponse->getCommit()->id);

echoMessage("Starting to compile build job $jobName");

// Builds the tests
$ciBuilder = new CIBuilder($config->paths->build_directory, $repoName);
$ciResult = $ciBuilder->runBuildScript();

$jobManager->writeJobOutput(join(PHP_EOL, $ciResult), $jobName);
$success = $jobManager->markDone($jobName);
if($success){
  echoMessage("Successfully processed $jobName");
}else{
  echoMessage("Could not mark $jobName as done");
}
?>