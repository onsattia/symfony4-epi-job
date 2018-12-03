<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Job;
use App\Entity\Image;
use Symfony\Component\Validator\Constraints\Date;


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
    public function accueil(Request $request)
    {
        //$url = $this->get('router')->generate('job');
        //return new RedirectResponse($url);
            //OR
        //return new RedirectResponse('job');

        $name = $request->query->get('name');;
        return $this->render('job/accueil.html.twig', [
            'name' => $name,
        ]);
    }

    /**
     * @Route("/voir/{id}", name="voir", requirements={"id"="\d+"})
     */
    public function voir($id)
    {
        $repositoty = $this->getDoctrine(Job::class)->getManager()->getRepository(Job::class);

        $job = $repositoty->find($id);

        if($job == null) {
            throw new NotFoundHttpException(("Le job ayant l'id ".$id." n'existe pas"));
        }
        return $this->render('job/voir.html.twig', [
            'job' => $job
        ]);

    }

    /**
     * @Route("/ajouter", name="ajouter")
     */
    public function ajouter(Request $request)
    {
        /*$date = "2020-01-01";
        $em = $this->getDoctrine()->getManager();
        $job = $em->getRepository(Job::class)->find(1);
        $job->setExpiresAt(new \DateTime($date));

        $job2 = new job();
        $job2->setTitle("Developpet Android");
        $job2->setCompany('Sumsung');
        $job2->setDescription("Nous cherchons un developpeur Android");
        $job2->setIsActivated(1);
        $job2->setExpiresAt(new \DateTime($date));
        $job2->setEmail('onsattia@gmail.com');

        $img = new Image();
        $img->setUrl("..\..\..\..\images\android.jpg");
        $img->setAlt("Developpeur Android");

        $job->setImage($img);

        $em->persist($job2);
        $em->flush();*/

        $date = "2020-01-01";
        $job = new job();
        $job->setExpiresAt(new \DateTime($date));

        $form = $this->createFormBuilder($job)
            ->add('title', TextType::class)
            ->add('company', TextType::class)
            ->add('description', TextareaType::class)
            ->add('is_activated', CheckboxType::class, array('required' => false))
            ->add('expires_at', DateType::class)
            ->add('email', TextType::class, array('required' => false))
            ->add('save', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($request->isMethod('Post')){
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($job);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Job bien enregistré');
                return $this->redirectToRoute('voir', array('id' => $job->getId()));
            }
        }

            return $this->render('job/ajouter.html.twig', array(
            'form' => $form->createView()));

    }

    /**
     * @Route("/modifier/{id}", name="modifier")
     */
    public function modifier($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $job = $em->getRepository(Job::class)->find($id);


        $form = $this->createFormBuilder($job)
            ->add('title', TextType::class)
            ->add('company', TextType::class)
            ->add('description', TextareaType::class)
            ->add('is_activated', CheckboxType::class, array('required' => false))
            ->add('expires_at', DateType::class)
            ->add('email', TextType::class, array('required' => false))
            ->add('save', SubmitType::class)
            ->getForm();;

        $form->handleRequest($request);

        if($request->isMethod('Post')){
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($job);
                $em->flush();
                $request->getSession()->getFlashBag()->add('notice', 'Job bien modifié');
                return $this->redirectToRoute('voir', array('id' => $job->getId()));
            }
        }

        $em->flush();
        return $this->render('job/modifier.html.twig', [
            'id' => $id,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/supprimer/{id}", name="supprimer")
     */
    public function supprimer($id)
    {
        return $this->render('job/supprimer.html.twig',[
            'id' => $id
        ]);

    }
}
