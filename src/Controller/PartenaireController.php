<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Partenaire;
use App\Form\PartenaireType;
use App\Repository\CategorieRepository;
use App\Repository\PartenaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use  Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


/**
 * @Route("/partenaire")
 */
class PartenaireController extends AbstractController
{


    /**
     * @Route("/Showproduit", name="display_produit")
     */
    public function AfficherJSON(NormalizerInterface $Normalizer)
    {

        $partenaire = $this->getDoctrine()->getManager()->getRepository(Partenaire::class)->findAll();
        $jsonContent = $Normalizer->normalize($partenaire,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }



    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route ("/Addproduit/{nom}/{descri}/{image}/{local}",name="ajouterproduit")
     */
    function AjouterJSON(NormalizerInterface $Normalizer,Request $request,CategorieRepository $c,\Swift_Mailer $mailer)
    {

$ca=$c->find('50');
     //   $cat = $this->getDoctrine()->getManager()->getRepository(Categorie::class)->find($request->get('id'));

        $em=$this->getDoctrine()->getManager();
        $partenaire=new Partenaire();

        $partenaire->setNom($request->get('nom'));
        $partenaire->setLocal($request->get('local'));
        $partenaire->setDescri($request->get('descri'));
        $partenaire->setImage($request->get('image'));
        
        $time = new \DateTime();
        $partenaire->setDatef($time);
        $partenaire->setCategorie($ca);

        $em->persist($partenaire);
        $em->flush();
        $message = (new \Swift_Message('Nouveau Contact'))
            
        ->setFrom('aa@gmail.com')
        ->setTo('mohamedamine.eloudi@esprit.tn')
        ->setBody($partenaire->getNom()) ;
          // On envoie le message
          $mailer->send($message);

        $jsonContent = $Normalizer->normalize($partenaire,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));;



       // return $this->render('back/produits.html.twig');
    }

/**
     * @Route("/ShowproduitS", name="ShowproduitS")
     */
    public function ShowproduitS(NormalizerInterface $Normalizer,Request $request)
    {


        $partenaire = $this->getDoctrine()->getManager()->getRepository(Partenaire::class)->find($request->get('id'));
        $jsonContent = $Normalizer->normalize($partenaire,'json',['groups'=>'post:read']);
        return new Response(json_encode($jsonContent));
    }




    /**
     * @Route("/Deleteproduit/{id}", name="delete")
     */
    public function SupprimerJSON(Request $request,$id,NormalizerInterface $Normalizer,PartenaireRepository $p)
    {
        $em = $this->getDoctrine()->getManager();
        $partenaire= $p->find($id);
        $em->remove($partenaire);
        $em->flush();
        $jsonContent = $Normalizer->normalize($partenaire,'json',['groups'=>'post:read']);
        return new Response("partenaire Supprimé".json_encode($jsonContent));;

    }



    /**
     * @Route("/Updateproduit/{id}", name="update_produit")
     *
     */
    public function ModifierJSON(Request $request,$id,NormalizerInterface $Normalizer,PartenaireRepository $p) {
        $em = $this->getDoctrine()->getManager();
        $partenaire= $p->find($id);

    //    $partenaire->setCategorie($request->get('categorie'));
       
        $partenaire->setNom($request->get('nom'));
        $partenaire->setLocal("aa");
        $partenaire->setDescri($request->get('descri'));
        $time = new \DateTime();
        $partenaire->setDatef($time);
        $partenaire->setImage($request->get('image'));



        $em->flush();
        $jsonContent = $Normalizer->normalize($partenaire,'json',['groups'=>'post:read']);
        return new Response("produit modifié".json_encode($jsonContent));;

    }




/**
 * @Route("/pie", name="pie")
 */
    public function statAction()
    {
       
     
        $pieChart = new PieChart();
        $classes =  $this->getDoctrine()->getRepository(Partenaire::class)->findAll();
        $totallike=0;
        foreach($classes as $post) {

            $totallike=$totallike+$post->getCategorie()->getId();
        }

        $data= array();
        $stat=['Partenaire', 'nom'];
        $nb=0;
        array_push($data,$stat);
        foreach($classes as $post) {
            $stat=array();
            array_push($stat,$post->getNom(),(($post->getCategorie()->getId()) ));
            $nb=($post->getCategorie()->getId());
            $stat=[$post->getNom(),$nb];
            array_push($data,$stat);

        }

        $pieChart->getData()->setArrayToDataTable(
            $data
        );
        $pieChart->getOptions()->setTitle('partenaire par categorie ');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);


        return $this->render('partenaire/stat.html.twig', array('pieChart' => $pieChart));
    }

    /**
     * @Route("/", name="app_partenaire_index", methods={"GET"})
     */
    public function index(PartenaireRepository $partenaireRepository): Response
    {
        $nombre = $partenaireRepository->nb();
        return $this->render('partenaire/index.html.twig', [
            'partenaires' => $partenaireRepository->findAll(),
            'nombre' =>$nombre,
        ]);
       /* $serializer= new Serializer([new ObjectNormalizer()]);
        $f=$serializer->normalize($partenaire);
        return new JsonResponse($f);
    
    */}

    /**
     * @Route("/new", name="app_partenaire_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PartenaireRepository $partenaireRepository,\Swift_Mailer $mailer): Response
    {
        $partenaire = new Partenaire();
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get("image")->getData();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
            $this->getParameter('$uploads'),
            $fileName);
            $partenaire->setImage($fileName);
            $em=$this->getDoctrine()->getManager();
            $em->persist($partenaire);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', 'partenaire  ajouté avec succs ');
            $message = (new \Swift_Message('Nouveau Contact'))
            
            ->setFrom('aa@gmail.com')
            ->setTo('mohamedamine.eloudi@esprit.tn')
            ->setBody($partenaire->getNom()) ;
              // On envoie le message
              $mailer->send($message);

              $this->addFlash('message', 'Le message a bien été envoyé');
            // $this->addFlash('message','message a bien ete envoyee');
           $partenaireRepository->add($partenaire);
            
       return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
            
          
        }

       /* $serializer= new Serializer([new ObjectNormalizer()]);
        $f=$serializer->normalize($partenaire);
        return new JsonResponse($f);
    */
        return $this->render('partenaire/new.html.twig', [
            'partenaire' => $partenaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_partenaire_show", methods={"GET"})
     */
    public function show(Partenaire $partenaire): Response
    {
        return $this->render('home/showp.html.twig', [
            'partenaire' => $partenaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_partenaire_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Partenaire $partenaire, PartenaireRepository $partenaireRepository,$id): Response
    {
        
        $partenaire=$partenaireRepository->find($id);
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->handleRequest($request);
        $file = $request->files->get('image');
        if ($form->isSubmitted() && $form->isValid()) {
              //   $partenaireRepository->add($partenaire);
         if (!empty($file)){
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('$uploads'),
                $fileName
            );
            $partenaire->setimage($fileName);
        }
        $em=$this->getDoctrine()->getManager();
        $em->flush();
        //$serializer= new Serializer([new ObjectNormalizer()]);
        //$f=$serializer->normalize($partenaire);
        //return new JsonResponse("modifierrr");

        $partenaireRepository->add($partenaire);
        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('partenaire/edit.html.twig', [
            'partenaire' => $partenaire,
            'form' => $form->createView(),
        ]);



    }

    /**
     * @Route("/{id}", name="app_partenaire_delete", methods={"POST"})
     */     
    public function delete(Request $request, Partenaire $partenaire, PartenaireRepository $partenaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partenaire->getId(), $request->request->get('_token'))) {
            $partenaireRepository->remove($partenaire);
        }
        return $this->redirectToRoute('app_partenaire_index', [], Response::HTTP_SEE_OTHER);
    }




    
    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

     /**
     * @Route("partenaire/count", name="count")
     */
    public function count(PartenaireRepository $repository ): Response
    {
        $nombre = $repository->nb();

        return $this->render('home/index.html.twig', [
            'nombre' => $nombre,
        ]);
    }
}
