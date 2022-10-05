<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ResController extends AbstractController
{
    /**
     * @Route("/res", name="app_res")
     */
    public function index(Request $request,reservationRepository $reservationRepository)
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->add($reservation);
     
    }
    return $this->render('res/index.html.twig', [
        'reservation' => $reservation,
        'form' =>$form->createView(),
    ]);
}
}