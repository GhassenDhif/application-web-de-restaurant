<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Form\PanierType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
/**
 * @Route("/panier")
 */
class PanierController extends AbstractController
{
    /**
     * @Route("/pan", name="app_panier")
     */
    public function index(): Response
    {
        $panier = $this->getDoctrine()->getRepository(Panier::class)->getPanierByUser();
//        dd($paniers);

        return $this->render('panier/affiche.html.twig', [
            'panier' => $panier,
        ]);
    }



    /**
     * @Route("/add_form", name="add_form")
     */
    public function addPanier(Request $request): Response
    {
        $panier = new Panier();

        $form = $this->createForm(PanierType::class,$panier);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($panier);//Add
            $em->flush();

            return $this->redirectToRoute('add_form');
        }
        return $this->render('panier/createPanier.html.twig',['f'=>$form->createView()]);

    }
    /**
     * @Route("/modifPanier/{id}", name="modifPanier")
     */
    public function modifPanier(Request $request,$id): Response
    {
        $panier = $this->getDoctrine()->getManager()->getRepository(Panier::class)->find($id);

        $form = $this->createForm(PanierType::class,$panier);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('app_panier');
        }
        return $this->render('panier/createpanier.html.twig',['f'=>$form->createView()]);




    }
    /**
     * @Route("/removePanier/{id}", name="supp_panier")
     */
    public function suppressionPanier($id): Response
    {

        $em = $this->getDoctrine()->getManager();
        $panier = $em->getRepository(Panier::class)->find($id);
        $em->remove($panier);
        $em->flush();

        return $this->redirectToRoute('app_panier');


    }

//    /**
//         * @Route("/addProduitPanier/{id}", name="ajouter_produit_panier")
//         */
//    public function addProduitPanier(Request $request,$id): Response
//    {   $session = $request->getSession();
//        $panier=$session->get('panier',[]);
//
//        $panier[$id]=1;
//        $session->set('panier',$panier);
//        dd($session->get('panier'));

//        try{

//            $produit = $this->getDoctrine()->getRepository(Produit::class)->find($id);
//            $panier = $this->getDoctrine()->getRepository(Panier::class)->getPanierByUser();
//        $panier = $this->getDoctrine()->getRepository(Panier::class)->getPanierByUser();
    // $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();

    // dd($panier);
//            $panier->addProduit($produit);
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($panier);//Add
//            $em->flush();

//            return  new RedirectResponse($this->generateUrl('app_commande'),301);
//        }
//        catch (\Exception $e)
//        {
//            return  new Response($e->getMessage(),$e->getCode());
//        }
    /**
     * @Route("/addProduitAuPanier/{id}", name="ajouter_produit_panier")
     */
    public function addProduitPanier($id,Request $request, SessionInterface $session): Response
    {

        $panier = $session->get('panier', []);
        $panier[$id]=$this->getDoctrine()->getRepository(Panier::class)->getPanierByUser();
        $session->set('panier',$panier);

        $panier = $this->getDoctrine()->getRepository(Panier::class)->getPanierByUser();

        $produit = $this->getDoctrine()->getRepository(Produit::class)->find($id);
//dd($produit);
        $panier->addProduit($produit);
        $em = $this->getDoctrine()->getManager();
        $em->persist($panier);//Add
        $em->flush();

        //  dd($panier = $session->get('panier'));

        return new RedirectResponse($this->generateUrl('app_commande'),301);


    }
    /** * @Route("/supprimer/{id}", name="supprimer_produit_panier") */
    public function supprimer($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []) ;
        if(!empty($panier[$id])) { unset($panier[$id]); }

        $session->set('panier', $panier);
        return new RedirectResponse($this->generateUrl('app_commande'),301);
    }



}
