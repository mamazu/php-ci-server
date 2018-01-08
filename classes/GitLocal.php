<?php
class GitLocal{
  public function __construct($gitPath){
    if(!file_exists($gitPath)){
      mkdir($gitPath, '777', true);
    }
  }
}
?>
