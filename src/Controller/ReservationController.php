<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\EvenementRepository;
use App\Repository\ReservationRepository;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{
    /**
     * @Route("/", name="app_reservation", methods={"GET"})
     */
    public function index(ReservationRepository $reservationRepository,EvenementRepository $evenementRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
        'reservations' => $reservationRepository->findAll(),
            ]);
    }



    /**
     * @Route("/new", name="app_reservation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReservationRepository $reservationRepository): Response
    {
        
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->add($reservation);


            $mail = new PHPMailer;
//Enable SMTP debugging.
            $mail->SMTPDebug = 1;
//Set PHPMailer to use SMTP.
            $mail->isSMTP();
//Set SMTP host name
            $mail->Host = "smtp.gmail.com";
//Set this to true if SMTP host requires authentication to send email
            $mail->SMTPAuth = true;
//Provide username and password
            $mail->Username = "raoudha.bettaibi@esprit.tn";
            $mail->Password = "213JMT6619";
//If SMTP requires TLS encryption then set it
            $mail->SMTPSecure = "tls";
//Set TCP port to connect to
            $mail->Port = 587;
            $mail->From = "pidev_web@esprit.tn";
            $mail->FromName = "Pi dev Web";
            $mail->addAddress("raoudha.bettaibi@esprit.tn");
            $mail->isHTML(true);
            $mail->Subject = "Ajout d'une reservation";
            $mail->Body = "<i>Une résérvation a été ajouté avec succes !</i>";
            $mail->AltBody = "Une résérvation a été ajouté avec succes !";
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                echo "Message has been sent successfully";
            }


            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/new.html.twig',[
            'f'=>$form->createView()]);
    }

    /**
     * @Route("/{id}", name="app_reservation_show", methods={"GET"})
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation\show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_reservation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->add($reservation);
            return $this->redirectToRoute('app_reservation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'f'=>$form->createView()]);
    }

    /**
     * @Route("/{id}", name="app_reservation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation);
        }

        return $this->redirectToRoute('app_reservation', [], Response::HTTP_SEE_OTHER);
    }
}
