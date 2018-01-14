<?php

declare(strict_types=1);

class JobManager{
    /** @var array $files */
    private $files = [];

    /** @var array $paths */
    private $paths = [];

    public function __construct(string $queueFolder)
    {
        $this->paths['todo'] = $queueFolder.'/todo/';
        $this->paths['working'] = $queueFolder.'/working/';
        $this->paths['output'] = $queueFolder.'/output';
    }

    public function reload(){
        foreach($this->paths as $type => $path){
            $this->files[$type] = $this->getJobsFromDirectory($path);
        }
    }

    private function getJobsFromDirectory(string $directory): array
    {
        if(!file_exists($directory) || !is_dir($directory)){
            return [];
        }
        
        $allFiles = array_filter(scandir($directory), function (string $fileName){
            return !is_dir($fileName);
        });
        return array_values($allFiles);
    }

    public function ciIsWorking(): bool
    {
        $this->reload();
        return count($this->files['working']) > 0;
    }

    public function hasNextJob(): bool
    {
        $this->reload();
        return count($this->files['todo']) > 0;
    }

    public function getNextJob():string
    {
        // Gets the next movable job file
        $nextMove = $this->getMoveOperation();
        $tries = 0;
        while(count($nextMove) === 2 && $tries < 100){
            $success = rename($nextMove[0], $nextMove[1]);
            if($success){
                return $nextMove[1];
            }
            $this->reload();
            $nextMove = $this->getMoveOperation();
        }
        throw new Exception('Could not find next job file');
    }

    private function getMoveOperation():array
    {
        $jobName = $this->files['todo'][0];

        $oldName = $this->paths['todo'].$jobName;
        $newName = $this->paths['working'].$jobName;

        return [$oldName, $newName];
    }

    public function markDone(string $job): bool
    {
        $fileName = $this->paths['working'].'/'.$job;
        return unlink($fileName);
    }

    public function writeJobOutput(string $output, string $jobName): bool
    {
        $jobName = str_replace('.json','.log', $jobName);
        $fileName = $this->paths['output'].'/'.$jobName;
        return file_put_contents($fileName, $output) ? true : false;
    }
}