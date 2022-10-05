<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PersonneController extends AbstractController
{
    /**
     * @Route("/", name="display_personne")
     */
    public function index(): Response
    {

        $personnes =$this->getDoctrine()->getManager()->getRepository(Personne::class)->findAll();
        return $this->render('Personne/index.html.twig', [
         'p'=>$personnes
        ]);
    }

    /**
     * @Route("/addPersonne",name="addPersonne")
     */
    public function addPersonne(Request $request):Response
    {
        $personne = new Personne();
        $form = $this->createForm(PersonneType::class,$personne);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($personne);
            $em->flush();

            return $this->redirectToRoute( 'display_personne');
        }
        return $this->render('Personne/createPersonne.html.twig',['f'=>$form->createView()]);
    }
  

}