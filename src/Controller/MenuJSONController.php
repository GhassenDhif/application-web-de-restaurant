<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Cate;
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



/**
 * @Route("/menuu")
 */

class MenuJSONController extends AbstractController
{
    /**
     * @Route("/allmenus", name="app_menu")
     */
    public function index(): Response
    {
        $menus = $this->getDoctrine()->getManager()
            ->getRepository(Menu::class)
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
        $formatted = $serializer->normalize($menus);
        return new JsonResponse($formatted);
    }
    /**
     * @Route("/detailmenu", name="detail_menu")
     */

    public function detailMenu(Request $request,NormalizerInterface $normalizer)
    {
        $id=$request->get("id");
        $em=$this->getDoctrine()->getManager();
        $menu=$em->getRepository(Menu::class)->find($id);
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($menu) {
            return $menu->getId();
        });
        $encoders = [new JsonEncoder()];
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers,$encoders);
        $formatted = $serializer->normalize($menu);
        return new JsonResponse($formatted);
    }


    /**
     * @Route("/addmenu", name="add_menu")
     */

    public function ajoutermenuAction(Request $request){

        $menu = new Menu();


        //$x = $jsonContent->getContent();


        $em = $this->getDoctrine()->getManager();
        $menu->setNom($request->get("nom"));
        $menu->setDescp($request->get("descp"));
        $menu->setImage($request->get("image"));
        $menu->setPrix($request->get("prix"));



        $em->persist($menu);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($menu,"json",['groups'=>'menu']);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/deletemenu", name="delete_menu", methods={"DELETE"})
     */

    public function deleteMenuAction(Request $request){

        $id = $request->get("id");
        $em = $this->getDoctrine()->getManager();
        $menu = $em->getRepository(Menu::class)->find($id);

        if ($menu != null) {
            $em->remove($menu);
            $em->flush();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formated = $serializer->normalize("Menu  supprimee avec succees ");
            return new JsonResponse($formated);
        }
        return new JsonResponse("id menu  est invalide !");
    }



    /**
     * @Route("/updateMenu", name="update_menu")
     */

    public function modifierMenuAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $menu = $this->getDoctrine()->getManager()
            ->getRepository(Menu::class)
            ->find($request->get("id"));

        $menu->setNom($request->get("nom"));
        $menu->setDescp($request->get("descp"));
        $menu->setImage($request->get("image"));
        $menu->setPrix($request->get("prix"));

        $em->persist($menu);
        $em->flush();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
        $normalizer->setCircularReferenceHandler(function ($menu) {
            return $menu->getId();
        });
        $encoders = [new JsonEncoder()];
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers,$encoders);
        $formatted = $serializer->normalize($menu);
        return new JsonResponse($formatted);

    }

    /**
     * @Route("/delMenu", name="delmenu")
     */
    public function delMenu(Request $request,NormalizerInterface $normalizer)
    {
        $em=$this->getDoctrine()->getManager();
        $menu=$this->getDoctrine()->getRepository(Menu::class)
            ->find($request->get('idF'));
        $em->remove($menu);
        $em->flush();
        $jsonContent = $normalizer->normalize($menu,'json',['groups'=>'menu']);
        return new Response(json_encode($jsonContent));
    }


}
