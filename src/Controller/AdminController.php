<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use AppBundle\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class AdminController extends AbstractController
{


    /**
     *
     * @Route("/admin", name="admin_list")
     */
    public function admin(Request $request )
    {

        $users = $this->getDoctrine()->getRepository(User::class)->findAll();


        return $this->render('admin/index.html.twig', [
            'users' => $users

        ]);
    }
    /**
     * @Route("/admin/update/{id}" ,name="BLOCK_USER")
     *Method({"GET", "POST"})
     */
    public function Block(Request $request,$id)
    {
        $USER = new User();
        $USER = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $form = $this->createForm(RegistrationFormType::class, $USER);


        $form->handleRequest($request);
        if ($form->isSubmitted() ) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('admin_list');
        }
        return $this->render('admin/update.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/admin", name="app_admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/Delete/{id}" ,name="DELETE_USER")
     *Method({"DELETE"})
     */
    public function Delete(Request $request,$id)
    {
        $User = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($User);
        $entityManager->flush();


        return $this->redirectToRoute('admin_list');

    }

    /**
     * @Route("utilisateur/{id}/desactiver", name="desactiver-user")
     */
    public function desactiverUser($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $user->setIsVerified(0);
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('admin_list');
    }

    /**
     * @Route("utilisateur/{id}/activer", name="activer-user")
     */
    public function activerUser($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $user->setIsVerified(1);
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('admin_list');
    }
    /**
     * @Route("/addpersonnejson/new", name="addpersonnejson")
     */

    public function addpersonnejson(Request $request,NormalizerInterface $Normalizer,UserPasswordEncoderInterface $userPasswordEncoder)
    {

        $em = $this->getDoctrine()->getManager();
        $personne= new User();
        $personne->setNom($request->get('nom'));
        $personne->setPrenom($request->get('prenom'));
        $personne->setEmail($request->get('email'));
       
        $personne->setPassword(
            $userPasswordEncoder->encodePassword(
                $personne,
                $request->get('password')
            )
        );
        //$personne->setPassword($request->get('password'));
        $personne->setNumTel($request->get('num_tel'));
        $personne->setRoles(["ROLE_USER"]);
        $personne->setGenre('male');

        $em->persist($personne);
        $em->flush();
        $jsonContent = $Normalizer->normalize($personne, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));;

    }


    /**
     * @Route("/allpersonne", name="allpersonne")
     */
    public function allpersonne(NormalizerInterface $normalizer)
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $personne = $repository->findAll();
        $jsonContent = $normalizer->normalize($personne, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));
    }



    /**
     * @Route("/updatepersonnejson/{id}", name="updatepersonnejson")
     */
    public function updatepersonnejson(Request $request, NormalizerInterface $normalizer, $id,UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $em = $this->getDoctrine()->getManager();
        $personne = $em->getRepository(User::class)->find($id);
        $personne->setEmail($request->get('email'));
        $personne->setPassword(
            $userPasswordEncoder->encodePassword(
                $personne,
                $request->get('password')
            )
        );


        $personne->setNom($request->get('nom'));
        $personne->setPrenom($request->get('prenom'));
        $personne->setNumTel($request->get('num_tel'));




        $em->persist($personne);
        $em->flush();
        $jsonContent = $normalizer->normalize($personne, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }
    /**
     * @Route("/personneid/{id}", name="personneid")
     */
    public function personneid(Request $request, $id, NormalizerInterface $normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $personne = $em->getRepository(User::class)->find($id);
        $jsonContent = $normalizer->normalize($personne, 'json', ['groups' => 'post:read']);

        return new Response(json_encode($jsonContent));

    }

    /**
     * @Route("/deletepersonnejson/{id}", name="deletepersonnejson")
     */
    public function deletepersonnejson(Request $request, NormalizerInterface $normalizer, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $personne = $em->getRepository(User::class)->find($id);
        $em->remove($personne);
        $em->flush();
        $jsonContent = $normalizer->normalize($personne, 'json', ['groups' => 'post:read']);
        return new Response(json_encode($jsonContent));
    }


}
