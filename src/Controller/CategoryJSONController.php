<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * @Route("/categorie")
 */

class CategoryJSONController extends AbstractController
{
    /**
     * @Route("/allCategories", name="app_categorie")
     */
    public function index(NormalizerInterface $normalizer): Response
    {
        $categories = $this->getDoctrine()->getManager()
            ->getRepository(Categorie::class)
            ->findAll();

        // dd($reclamations);
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($categories) {
            return $categories->getId();
        });
        $encoders = [new JsonEncoder()];
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers,$encoders);
        $formatted = $serializer->normalize($categories);
        return new JsonResponse($formatted);    }


    /**
     * @Route("/detailCategorie", name="detail_categorie")
     */

    public function detailCategorie(Request $request,NormalizerInterface $normalizer)
    {
        $id=$request->get("id");
        $em=$this->getDoctrine()->getManager();
        $categorie=$em->getRepository(Categorie::class)->find($id);
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($categorie) {
            return $categorie->getId();
        });
        $encoders = [new JsonEncoder()];
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers,$encoders);
        $formatted = $serializer->normalize($categorie);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/addCategorie", name="add_categorie")
     */

    public function ajouterCategorieAction(Request $request){

        $categorie = new Categorie();


        //$x = $jsonContent->getContent();


        $em = $this->getDoctrine()->getManager();
        $categorie->setNom($request->get("nom"));

        $em->persist($categorie);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($categorie,"json",['groups'=>'category']);
        return new JsonResponse($formatted);
    }


    /**
     * @Route("/deleteCategorie", name="delete_categorie", methods={"DELETE"})
     */

    public function deleteCategoryAction(Request $request){

        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $categorie = $em->getRepository(Categorie::class)->find($id);

        if ($categorie != null) {
            $em->remove($categorie);
            $em->flush();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formated = $serializer->normalize("categorie  supprimee avec succees ");
            return new JsonResponse($formated);
        }
            return new JsonResponse("id category est invalide !");
    }

    /**
     * @Route("/updateCategorie", name="update_categorie")
     */

    public function modifierCategorieAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categorie = $this->getDoctrine()->getManager()
            ->getRepository(Categorie::class)
            ->find($request->get("id"));
        $menu = $this->getDoctrine()->getManager()
            ->getRepository(Menu::class)
            ->find($request->get("idmenu"));

        $categorie->setNom($request->get("nom"));
        $categorie->addMenu($menu);

        $em->persist($categorie);
        $em->flush();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($categorie) {
            return $categorie->getId();
        });
        $encoders = [new JsonEncoder()];
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers,$encoders);
        $formatted = $serializer->normalize($categorie);
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/delCategorie", name="delcategorie")
     */
    public function delcategorie(Request $request,NormalizerInterface $normalizer)
    {           $em=$this->getDoctrine()->getManager();
        $cat=$this->getDoctrine()->getRepository(Categorie::class)
            ->find($request->get('id'));
        $em->remove($cat);
        $em->flush();
        $jsonContent = $normalizer->normalize($cat,'json',['groups'=>'category']);
        return new Response(json_encode($jsonContent));
    }

}
