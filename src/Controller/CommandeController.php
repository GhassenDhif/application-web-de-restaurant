<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Repository\CommandeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Commande;
use App\Form\CommandeType;


use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/commande")
 */
class CommandeController extends AbstractController
{
    /**
     * @Route("/", name="app_commande")
     */
    public function index(SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);
        $panierElement =  [];
        foreach ($panier as $id =>$quantity){

            $panierElement []  =[
                'produit'  => $this->getDoctrine()->getRepository(Produit::class)->find($id),
                'quantity' =>$quantity

            ];
        }
        $total=0;
        foreach ($panierElement as $item)
        {
            $total+= $item['produit']->getPrix() ;
        }
        // dd($panierElement);
        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
//        dd($commandes);

        return $this->render('commande/createCommande.html.twig', [
            'commandes' => $commandes,
            'produits' => $produits,
            'items' =>$panierElement,
            'total' =>$total,
            'page'=>'Food menu'
        ]);
    }
    /**
     * @Route("/vv", name="aff_commande")
     */
    public function indexx(): Response
    {
        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();
//        dd($commandes);
        return $this->render('commande/home.html.twig',
            [
                'commandes'=>$commandes,
                'page'=>'Food menu'
            ]);
    }
    /**
     * @Route("/rechermbl/{id}", name="recherchembl")
     */
    public function recherhcemobl(CommandeRepository $repository,NormalizerInterface $Normalizer,$id,Request $request): Response
    {
//        $data=$request->get('search')
        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository(Commande::class)->find($id);



        $jsonContent = $Normalizer ->normalize($commande, 'json', ['groups'=> 'post:read']);
        return new Response(json_encode($jsonContent));

    }
    /**
 * @Route("/Deleteproduit/{id}", name="update_produit")
 */
    public function SupprimerJSON(Request $request,$id,NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $partenaire= $em->getRepository(Partenaire::class)->find($id);
        $em->remove($partenaire);
        $em->flush();
        $jsonContent = $Normalizer->normalize($partenaire,'json',['groups'=>'post:read']);
        return new Response("partenaire Supprimé".json_encode($jsonContent));;

    }
    /**
     * @Route("/Deletecommande/{id}", name="update_commande")
     */
    public function DeletecommandeSON(Request $request,$id,NormalizerInterface $Normalizer)
    {
        $em = $this->getDoctrine()->getManager();
        $commande= $em->getRepository(Commande::class)->find($id);
        $em->remove($commande);
        $em->flush();
        $jsonContent = $Normalizer->normalize($commande,'json',['groups'=>'post:read']);
        return new Response("commande Supprimé".json_encode($jsonContent));;

    }
    /**
     * @Route("/remoblCommande/{id}", name="moblsuppression")
     */
    public function suppressionmobCommande(Request $request,NormalizerInterface $Normalizer,$id): Response
    {

        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository(Commande::class)->find($id);

//        $em->persist($commande);
//        $em->flush();
        //  dd($commande);

        $em->remove($commande);
        $em->flush();

        $jsonContent = $Normalizer ->normalize($commande, 'json', ['groups'=> 'post:read']);
        return new Response(json_encode($jsonContent));


    }
    /**
     * @Route("/commandeaffmobl", name="all_commande", methods={"Get"})
     */
    public function indexmobl(NormalizerInterface $Normalizer): Response
    {
        $commandes = $this->getDoctrine()->getRepository(Commande::class)->findAll();
//        dd($commandes);
        $jsonContent = $Normalizer ->normalize($commandes, 'json', ['groups'=> 'post:read']);
        return new Response(json_encode($jsonContent));
    }

    /**
    //     * @Route("/clist", name="list_commande" , methods={"Get"})
     *  @Route("/clist", name="list_commande" )
     */
    public function listc(): Response
    {
//        require_once 'dompdf/autoload.Inc.php';
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $commande = $this->getDoctrine()->getRepository(Commande::class)->findAll();
//        dd($commande);


        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('commande/listcommande.html.twig',[
            'commandes'=>$commande,
            'page'=>'Food menu'
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Store PDF Binary Data
        $output = $dompdf->output();

        // In this case, we want to write the file in the public directory
        $publicDirectory = $this->getParameter('kernel.project_dir') . '/public/document';
        // e.g /var/www/project/public/mypdf.pdf
        $pdfFilepath =  $publicDirectory . '/mypdf.pdf';

        // Write file to the desired path
        file_put_contents($pdfFilepath, $output);

        return $this->redirectToRoute('aff_commande');
        // Send some text response
    //    return new Response("The PDF file has been succesfully generated !");

     
    }
    /**
     * @Route("/about", name="app_about")
     */
    public function indexAbout(): Response
    {

        return $this->render('about.html.twig',[
            'page'=> 'About us'
        ]);
    }

    /**
     * @Route("/addCommande", name="add_commande")
     */
    public function addCommande(Request $request,CommandeRepository $repository,SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);
        $panierElement =  [];
        foreach ($panier as $id =>$quantity){

            $panierElement []  =[
                'produit'  => $this->getDoctrine()->getRepository(Produit::class)->find($id),
                'quantity' =>$quantity

            ];
        }
        $total=0;
        foreach ($panierElement as $item)
        {
            $total+= $item['produit']->getPrix() ;
        }
        $session->clear();

        $commande = new Commande();
        $panier = $this->getDoctrine()->getRepository(Panier::class)->getPanierByUser();
        foreach($panier->getProduits() as $produit){
            $commande->addProduit($produit);
            $panier->removeProduit($produit);
        }
        $commande->setDateDeCommande(\DateTime::createFromFormat('Y-m-d', date('Y-m-d')));
        $commande->setPrix($total);
        $commande->setTitre('other');
        $commande->setPanier($panier);

        $em = $this->getDoctrine()->getManager();
        $em->persist($commande);
        $em->flush();
        $commande = $this->getDoctrine()->getRepository(Commande::class)->findAll();
//        $commande=$repository->OrderById();;

        return $this->redirectToRoute('app_commande');

    }

    /**
     * @Route("/modifCommande/{id}", name="modifCommande")
     */
    public function modifBlog(Request $request,$id): Response
    {
        $commande = $this->getDoctrine()->getManager()->getRepository(Commande::class)->find($id);

        $form = $this->createForm(CommandeType::class,$commande);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('app_commande');
        }
        return $this->render('commande/createCommande.html.twig',['f'=>$form->createView()]);




    }
    /**
     * @Route("/removeCommande/{id}", name="supp_commande")
     */
    public function suppressionCommande($id): Response
    {

        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository(Commande::class)->find($id);

        $em->persist($commande);
        $em->flush();
        //  dd($commande);

        $em->remove($commande);
        $em->flush();

        return $this->redirectToRoute('app_commande');
    
    
    }

    /**
     * @Route("/remoblComm/{id}", name="moblsuppression")
     */
    public function supprcommbl(Request $request,NormalizerInterface $Normalizer,$id): Response
    {

        $em = $this->getDoctrine()->getManager();
        $commande = $em->getRepository(Commande::class)->find($id);

//        $em->persist($commande);
//        $em->flush();
        //  dd($commande);

        $em->remove($commande);
        $em->flush();

        $jsonContent = $Normalizer ->normalize($commande, 'json', ['groups'=> 'post:read']);
        return new Response(json_encode($jsonContent));


    }




    /**
     * @Route("/recherche", name="recherche")
     */
    public function recherhce(CommandeRepository $repository,Request $request): Response
    {
        $data=$request->get('search');
        $commande=$repository->findby(['prix'=>$data]);



        return $this->render('commande/affiche.html.twig', [
            'commandes' => $commande,
            'page' => 'about'
        ]);}
    /**
     * @param CommandeRepository $repository
     * @return Response
     * @Route("/ListDQL")
     */
    function OrderByTitreSQL(CommandeRepository $repository){
        $commande=$repository->OrderByTitre();
        return $this->render('commande/home.html.twig', [
            'commandes' => $commande,
            'page' => 'about'
        ]);


    }



//    /**
//     * @Route("/addProduitCommande/{id}", name="ajouter_produit")
//     */
//    public function addProduitCommande(Request $request,$id): Response
//    {
//        $commande = $this->getDoctrine()->getRepository(Commande::class)->find($id);
//        $form = $this->createForm(AjoutProduitCommande::class,$commande);
//        $form->handleRequest($request);
//        if($form->isSubmitted() && $form->isValid()) {
//                $em = $this->getDoctrine()->getManager();
//                $em->persist($commande);//Add
//                $em->flush();
//                return $this->redirectToRoute('app_commande');
//        }
//        return $this->render('commande/ajoutProduit.html.twig',
//            ['f'=>$form->createView()]);
//
//    }

}
