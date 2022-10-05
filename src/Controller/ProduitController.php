<?php

namespace App\Controller;

use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("/", name="app_produit")
     */
    public function index(): Response
    {
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        return $this->render('produit/affiche.html.twig', [
            'produits' => $produits,
        ]);
    }

    /**
     * @Route("/produit-search/{name}", name="search_produit")
     */
    public function searchProduit($name): Response
    {
        $produits = $this->getDoctrine()->getRepository(Produit::class)->searchByName($name);
//        dd(new Response($produits));
        return $this->render("produit/produit-search.html.twig",
            ['produits' => $produits]);
    }
}
