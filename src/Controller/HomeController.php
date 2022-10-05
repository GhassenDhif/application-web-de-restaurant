<?php

namespace App\Controller;

use App\Repository\PartenaireRepository;
use App\Repository\EvenementRepository;
use App\Repository\ReservationRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="app_home")
     */
    public function index(PartenaireRepository $partenaireRepository,CategorieRepository $ca): Response
    {
        $nombre = $partenaireRepository->nb();
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'partenaires' => $partenaireRepository->findAll(),
            'categorie' => $ca->findall(),
            'nombre' =>$nombre,
            'categories' => $ca->findAll(),
        
          
        ]);
    }

    /**
     * @Route("/home/{id}/amine", name="amine")
     */
    public function rendrepar(PartenaireRepository $repository,$id): Response
    {
       
        $amine= $repository->listepartenaireparcateg($id);
        return $this->render('home/index.html.twig', [
            'partenaires' => $amine,
            'categories' => $amine
            
           
        ]);
    }
    /**
     * @Route("/EV", name="eventee")
     */
    public function indexxx(EvenementRepository $ev , ReservationRepository $reser,Request $request ): Response
    {
        return $this->render('home/index1.html.twig', [
            'evenements'=> $ev->findAll(),
         
        ]);
    }
}



