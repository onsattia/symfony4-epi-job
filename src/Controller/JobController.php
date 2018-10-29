<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class JobController extends AbstractController
{
    /**
     * @Route("/job", name="job")
     */
    public function index()
    {
        return $this->render('job/index.html.twig', [
            'controller_name' => 'JobController',
        ]);
    }

    /**
     * @Route("/accueil", name="accueil")
     */
    public function accueil()
    {
        return $this->render('job/accueil.html.twig');
    }

    /**
     * @Route("/voir/{id}", name="voir", requirements={"id"="\d+"})
     */
    public function voir($id)
    {
        return new Response(
            '<html><body>Detail du job ayant l\'id: '.$id.'</body></html>'
        );

    }
}
