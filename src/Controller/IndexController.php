<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends Controller
{

    private $repo;

    public function __construct(
        \App\Repository\BuildJobRepository $repo
    ) {
        $this->repo = $repo;
    }

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $summary = $this->repo->getSummary();
        return $this->render('index.html.twig', [
            'summary' => $summary
        ]);
    }
}
