<?php
class GitHubAPIResponse{
  public function __construct(string $payload){
    $this->payload = $payload;
    $this->data = json_decode($payload);
  }

  private function getData(string $index){
    if(array_key_exists($index, $this->data))
      return $this->data->$index;
    return NULL;
  }

  public function getCommit(){
    return $this->getData('head_commit');
  }

  public function getRepoName(){
    $repo = $this->getData('repository');
    if($repo !== null){
      return $repo->name;
    }
    return null;
  }

  public function getFullRepoName(){
    $repo = $this->getData('repository');
    if($repo !== null){
      return $repo->full_name;
    }
    return null;
  }

  public function getCloneUrl(){
    $repo = $this->getData('repository');
    if($repo !== null){
      return $repo->url;
    }
    return null;
  }

  public function secretMatches($gitSigniture, $key){
    $generatedKey = 'sha1=' . hash_hmac('sha1', $key, $this->payload);

    return $gitSigniture === $generatedKey;
  }

}
 ?>
