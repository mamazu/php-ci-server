<?php
class CIBuilder{
    public function __construct(string $buildFolder, string $repoName){
        $this->buildFolder = $buildFolder.'/'.$repoName;
        if(file_exists($this->buildFolder)){
            CIBuilder::delTree($this->buildFolder);
        }
        mkdir($this->buildFolder, 0777, true);
    }

    public function runBuildScript()
    {
        $currentDir = getcwd();
        chdir($this->buildFolder);
        $output = [];
        exec('cmake ../../GitRepos/Lpg -G "CodeBlocks - Unix Makefiles"', $output);
        exec('cmake --build .', $output);
        exec('./tests/tests 2>&1', $output);
        chdir($currentDir);

        return $output;
    }

    public static function delTree($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            if(is_dir("$dir/$file")){
                CIBuilder::delTree("$dir/$file");
            } else{
                unlink("$dir/$file");
            }
        }
        return rmdir($dir);
    } 
}
