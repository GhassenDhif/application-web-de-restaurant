<?php

namespace App\Controller;



use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface; 

/** 
 * @Route("/categorie")
 */
class CategorieController extends AbstractController
{
    
    /**
     * @Route("/pdf", name="liste", methods={"GET"})
     */

    public function indexx()
    {
        $activite= $this->getDoctrine()->getRepository(Categorie::class)->findAll();

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        
        

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('categorie/listep.html.twig', [
            'categories' => $activite
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("ListeDesCategories.pdf", [
            "categories" => false
        ]);
   
    }



     /**
     * @Route("/", name="app_categorie_index", methods={"GET"})
     */
    public function index(Request $request,CategorieRepository $categorieRepository,PaginatorInterface $paginator): Response
    {
      
        $donnes=$categorieRepository->findAll();
        $categories=$paginator->paginate(
            $donnes,
            $request->query->getInt('page',1),
            3
        );
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
  
        ]);
    }



    /**
     * @Route("/new", name="app_categorie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CategorieRepository $categorieRepository,\Swift_Mailer $mailer):Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->add($categorie);
            
            $message = (new \Swift_Message('Nouveau Contact'))
            
            ->setFrom('aa@gmaul.com')
            ->setTo('mohamedamine.eloudi@esprit.tn')
            ->setBody($categorie->getType()) ;
              // On envoie le message
              $mailer->send($message);

              $this->addFlash('message', 'Le message a bien été envoyé');
            // $this->addFlash('message','message a bien ete envoyee');
           
            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
   
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_categorie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorieRepository->add($categorie);
            return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_categorie_delete", methods={"POST"})
     */
    public function delete(Request $request, Categorie $categorie, CategorieRepository $categorieRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->request->get('_token'))) {
            $categorieRepository->remove($categorie);
        }

        return $this->redirectToRoute('app_categorie_index', [], Response::HTTP_SEE_OTHER);
    }


    /**
     * @param Request $request
     * 
     * @return Response
     * 
     * @Route("/search", name="searchAdmin")
     * 
     */
    public function searchAdmin(categorieRepository $repository, Request $request)
    {
        $requestString = $request->get('searchValue');
        $categorie = $repository->findAllMenubyDescription($requestString);
        

        return $this->render('categorie/index.html.twig', [
            'categorie' => $categorie,
       
        ]);
    }

    
    /**
     * @Route("/searchPostajax", name="ajaxPost", methods={"GET"})
     */
    public function searchPosteajax(Request $request,CategorieRepository $categorieRepository):Response
        {
          
            $requestString=$request->get('searchValue');
            $query=$categorieRepository->aa($requestString);

            return $this->render('categorie/index.html.twig', [
                'categories'=>$query,
            ]);
        }

    /**
     * @Route("TrierParDateDESC", name="Trierr")
     */
    public function TrierParNom(CategorieRepository $repository ): Response
    {
        $am = $repository->trier();

        return $this->render('categorie/index.html.twig', [
            'categories' => $am,
        ]);
    }


}
