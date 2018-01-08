 <?php
class GitHubAPIResponse{
  public function __construct($payload){
    $this->data = json_decode($payload);
  }

  public function getCommit(){
    if(array_key_exists($this->data, 'head_commit')){
      return $this->data['head_commit'];
    }
    return null;
  }

  public function getCloneUrl(){
    if(array_key_exists($this->data, 'repository')){
      return $this->data['repository']['url'];
    }
    return null;
  }
}
 ?>
