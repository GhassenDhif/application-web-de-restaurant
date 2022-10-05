<?php

namespace App\Controller;

use App\Entity\Cate;
use App\Form\CateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



class CateController extends AbstractController
{
    /**
     * @Route("/dashboard", name="display_cat")
     */
    public function index(): Response
    {
        $categorie = $this->getDoctrine()->getManager()->getRepository(Cate::class)->findAll();
        return $this->render('cate/index.html.twig',  ['c'=> $categorie]);

       
    }



     /**
     * @Route("/addCat", name="addCat")
     */
    public function addCat(Request $r): Response
    {
        $categorie = new Cate();

        $form = $this->createForm(CateType::class,$categorie);

        $form->handleRequest($r);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);//Add
            $em->flush();

            return $this->redirectToRoute('display_cat');
        }
        return $this->render('cate/ajoutCat.html.twig',['f'=>$form->createView()]);

    }


     /**
     * @Route("/supp_cat/{id}", name="supp_cat")
     */
    public function supp_cat(Cate  $categorie): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($categorie);
        $em->flush();

        return $this->redirectToRoute('display_cat');


    }


     /**
     * @Route("/modif_cat/{id}", name="modif_cat")
     */
    public function modif_cat(Request $request,$id): Response
    {

        $categorie = $this->getDoctrine()->getManager()->getRepository(Cate::class)->find($id);

        $form = $this->createForm(CateType::class,$categorie);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('display_cat');
        }
        return $this->render('cate/modifCat.html.twig',['f'=>$form->createView()]);
    }


}
