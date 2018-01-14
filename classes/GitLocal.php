<?php
class GitLocal{
  public function __construct(string $localGitDirectory){
    if(!file_exists($localGitDirectory)){
      mkdir($localGitDirectory, 0777, true);
    }

    $this->localPath = $localGitDirectory;
  }

  public function clone(string $cloneUrl, string $repoName)
  {
    if(file_exists($this->getLocalRepoPath($repoName))){
      return false; // If the repo already exists dont clone it.
    }

    $directoryBefore = getcwd();
    chdir($this->localPath); // Go into the local directory
    $escapedUrl = escapeshellarg($cloneUrl);
    exec("git clone $escapedUrl $repoName;"); // Clone
    chdir($directoryBefore);

    return true;
  }

  public function checkout(string $repoName, string $revisionNumber)
  {
    $previousDirectory = getcwd();
    
    chdir($this->getLocalRepoPath($repoName));  // Go into the repo
    $output = [];
    exec("git checkout $revisionNumber;", $ouput); // Checkout the latest revision

    chdir($previousDirectory);
    return $output;
  }

  public function fetch(string $repoName, $remote=null): array
  {
    $previousDirectory = getcwd();
    chdir($this->getLocalRepoPath($repoName));  // Go into the repo
    
    if($remote === null){
      $remote = '--all';
    }else{
      $remote = escapeshellargs($remote);
    }

    $ouput = [];
    exec("git fetch $remote;", $ouput); // Checkout the latest revision

    chdir($previousDirectory);
    return $ouput;
  }

  private function getLocalRepoPath(string $repoName): string
  {
    return $this->localPath. '/' . $repoName;
  }
}
?>
