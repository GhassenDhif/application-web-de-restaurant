<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;



/**
 * @Route("/evenement")
 */
class EvenementController extends AbstractController
{


  /**
     * @Route("/Allevent", name="Allevent")
     */
    public function AfficherJSON(NormalizerInterface $Normalizer)
    {

        $evenements = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();
        $jsonContent = $Normalizer->normalize($evenements,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }




    /**
     * @Route("/", name="app_evenement", methods={"GET"})
     */
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/index.html.twig',[
            'evenements' => $evenementRepository->findAll(),
        ]);

  }

    /**
     * @Route("/new", name="app_evenement_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EvenementRepository $evenementRepository): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get("image")->getData();
            $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('$uploads'),
                $fileName);
            $evenement->setImage($fileName);
            //$evenementRepository->add($evenement);
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();


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
            $mail->From = "raoudha.bettaibi@esprit.tn";
            $mail->FromName = "Raoudha";
            $mail->addAddress("raoudha.bettaibi@esprit.tn", "Raoudha");
            $mail->isHTML(true);
            $mail->Subject = "Ajout d'un event";
            $mail->Body = "<i>Un evenement a été ajouté avec succes !</i>";
            $mail->AltBody = "Un evenement a été ajouté avec succes !";
            if (!$mail->send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            } else {
                echo "Message has been sent successfully";
            }



            return $this->redirectToRoute('app_evenement', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('evenement/new.html.twig', [
            'f' => $form->createView()]);
    }


    /**
     * @Route("/{id}", name="app_evenement_show", methods={"GET"})
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);

    }

    /**
     * @Route("/{id}/edit", name="app_evenement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Evenement $evenement, EvenementRepository $evenementRepository,$id): Response
    {
        $evenement=$evenementRepository->find($id);
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        $file = $request->files->get('image');
        if ($form->isSubmitted() && $form->isValid()) {
            if (!empty($file)){
                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
                $file->move(
                    $this->getParameter('$uploads'),
                    $fileName
                );
                $evenement->setimage($fileName);
            }
            $em=$this->getDoctrine()->getManager();
            $em->flush();



           // $evenementRepository->add($evenement);
            return $this->redirectToRoute('app_evenement', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/edit.html.twig', [
            'f'=>$form->createView()]);
    }

    /**
     * @Route("/{id}", name="app_evenement_delete", methods={"POST"})
     */
    public function delete(Request $request, Evenement $evenement, EvenementRepository $evenementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $evenementRepository->remove($evenement);
        }

        return $this->redirectToRoute('app_evenement', [], Response::HTTP_SEE_OTHER);
    }

     /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }


    public function search(Request $request, $evenement, EvenementRepository $evenementRepository)
    {
        $em=$this->getDoctrine()->getManager();
        $requestString=$request->get('q');
        $evenement= $evenementRepository->findEntitiesByString($requestString);
        if( !$evenement )
        {
            $result['evenement']['error']="Not found :( !";

        }else{
            $result['evenement']=$this->getRealEntities($evenement);

        }
        return new Response(json_encode($result));
    }
public function getRealEntities($evenement)
{
    foreach ($evenement as $evenement){
        $realEntities[$evenement->getId()]=[$evenement->getNom(), $evenement->getImage()];
    }
    return $realEntities;
}










    public function affEventId(Request $request,$id, NormalizerInterface $Normalizer)
    {

        $em = $this->getDoctrine()->getManager();
        $evenement = $em->getRepository(Evenement::class)->find($id);
        $jsonContent = $Normalizer->normalize($evenement, 'json', ['groups' => 'post:read']);

        return new Response(json_encode ($jsonContent));

    }

    /**
     * @Route("/addEventJSON/new", name="addEventJSON")
     */
    public function addEventJSON(Request $request, NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $evenement = new Evenement();
        $evenement->setNom($request->get('nom'));
        $evenement->SetDate($request->get('date'));
        $evenement->SetImage($request->get('image'));
        $em->persist($evenement);
        $em->flush();
        $jsonContent = $Normalizer->normalize($evenement, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }
/**
 * @Route ("/updateEventJSON/{id}", name="updateeventJSON")
 */

    public function updateEventJSON (Request $request, NormalizerInterface $Normalizer,$id){
        $em=$this->getDoctrine()->getManager();
        $evenement=$em->getRepository(Evenement::class)->find($id);
        $evenement->setNom ($request->get('nom'));
        $evenement->SetDate($request->get('date'));
        $evenement->SetImage($request->get('image'));
        $em->flush();
        $jsonContent=$Normalizer->normalize($evenement, 'json',['groups'=>'post:read']);
        return new Response("Information updated successfully".json_encode($jsonContent));
    }
/**
 * @Route("/deleteEventJSON/{id}", name="deleteEventJSON")
 */
    public function deleteEventJSON (Request $request, NormalizerInterface $Normalizer, $id)
    {
        $em=$this->getDoctrine()->getManager ();
        $evenement=$em->getRepository(Evenement::class)->find($id);
        $em->remove ($evenement);
        $em->flush();
        $jsonContent=$Normalizer->normalize($evenement, 'json',['groups'=>'post:read']);
        return new Response("Event deleted successfully".json_encode($jsonContent));
    }
}
