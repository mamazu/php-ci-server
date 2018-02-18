<?php

namespace App\Controller;

use App\Repository\BuildJobRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{

    private $repo;

    public function __construct(BuildJobRepository $repo) {
        $this->repo = $repo;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $allJobs = $this->repo->findAll();
        $summary = [];
        foreach ($allJobs as $buildJob) {
            $state = $buildJob->getState()->getName();
            if (!isset($summary[$state])) {
                $summary[$state] = 0;
            }
            $summary[$state]++;
        }

        return $this->render('index.html.twig', [
            'summary' => $summary,
            'build_jobs' => $allJobs
        ]);
    }
}
