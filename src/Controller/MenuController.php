<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Menu;
use App\Form\MenuType;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Snappy\Pdf;

class MenuController extends AbstractController
{
    /**
     * @Route("/display_menu", name="display_menu")
     */
    public function index(): Response
    {

        $menu = $this->getDoctrine()->getManager()->getRepository(Menu::class)->findAll();
        return $this->render('menu/index.html.twig',  ['m'=> $menu]);


    }
    /**
     * @Route("/menu/front", name="menu_front")
     */
    public function indexa(): Response
    {


        $menu = $this->getDoctrine()->getManager()->getRepository(Menu::class)->findAll();
        return $this->render('menu/front.html.twig',  ['m'=> $menu]);


    }
/**
     * @Route("/addMenu", name="addMenu")
     */
    public function addMenu(Request $r): Response
    {
        $menu = new Menu();

        $form = $this->createForm(MenuType::class,$menu);

        $form->handleRequest($r);

        if($form->isSubmitted() && $form->isValid()) {
            $ImageFile = $form->get('image')->getData();
            if ($ImageFile) {

                // this is needed to safely include the file name as part of the URL

                $newFilename = md5(uniqid()).'.'.$ImageFile->guessExtension();
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads/menu_images';
                // Move the file to the directory where brochures are stored
                try {
                    $ImageFile->move(
                        $destination,
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'ImageFilename' property to store the PDF file name
                // instead of its contents
                $menu->setImage($newFilename);

            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($menu);//Add
            $em->flush();

            return $this->redirectToRoute('display_menu');
        }
        return $this->render('menu/ajoutMenu.html.twig',['f'=>$form->createView()]);

    }


     /**
     * @Route("/supp_menu/{id}", name="supp_menu")
     */
    public function supp_menu(Menu  $menu): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($menu);
        $em->flush();

        return $this->redirectToRoute('display_menu');


    }


     /**
     * @Route("/modif_menu/{id}", name="modif_menu")
     */
    public function modif_menu(Request $request,$id): Response
    {
        $menu = $this->getDoctrine()->getManager()->getRepository(Menu::class)->find($id);

        $form = $this->createForm(MenuType::class,$menu);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $ImageFile = $form->get('image')->getData();
            if ($ImageFile) {

                // this is needed to safely include the file name as part of the URL

                $newFilename = md5(uniqid()).'.'.$ImageFile->guessExtension();
                $destination = $this->getParameter('kernel.project_dir').'/public/uploads/menu_images';
                // Move the file to the directory where brochures are stored
                try {
                    $ImageFile->move(
                        $destination,
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'ImageFilename' property to store the PDF file name
                // instead of its contents
                $menu->setImage($newFilename);

            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('display_menu');
        }
        return $this->render('menu/modifMenu.html.twig',['f'=>$form->createView()]);




    }
    /**
     * @Route("/pdf", name="pdf")
     */
    public function pdf()
    {
        $menu = $this->getDoctrine()->getManager()->getRepository(Menu::class)->findAll();

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'SourceSansPro-Regular');
        $pdfOptions->setTempDir('temp');
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('menu/pdf.html.twig', [
            'title' => "Welcome to our PDF Test",
            'm'=> $menu
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A3', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }
    /**
     * @Route("/pdff", name="pdff")
     */
    public function pdff()
    {
        $snappy = new Pdf('/path/to/binary');
        $snappy->generateFromHtml("pdf.html.twig", 'test.pdf');

    }
    /**
     * @Route("/modif_menu/{id}/{id1}", name="modif_menu_pref")
     */
    public function modif_menu_pref(Request $request,$id,$id1): Response
    {
        $menu = $this->getDoctrine()->getManager()->getRepository(Menu::class)->find($id1);
        $client = $this->getDoctrine()->getManager()->getRepository(Client::class)->find($id);

$menu->addClient($client);

        $this->addFlash('success', 'Menu préfèré ajouté! ');
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('menu_pref_front');
    }
    /**
     * @Route("/menu/preffront", name="menu_pref_front")
     */
    public function indexaa(): Response
    {

        $client=$this->getDoctrine()->getRepository(Client::Class)->get_menu(1);
        $menu=$client->getMenuPrefere();

        return $this->render('menu/pref.html.twig',  ['menu'=> $menu]);


    }
}
