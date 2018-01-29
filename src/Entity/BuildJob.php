<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BuildJobRepository")
 */
class BuildJob
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string $commitI
     * @ORM\Column(type="string", length=40)
     */
    private $commitId;

    /**
     * @var string $repositoryName
     * @ORM\Column(type="string")
     */
    private $repositoryName;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $status;

    public function __construct(
        string $commitId,
        string $repoName,
        $status = null
    ) {
        $this->commitId = $commitId;
        $this->repositoryName = $repoName;
        $this->status = is_null($status) ? 'pending' : $status;
    }
}
