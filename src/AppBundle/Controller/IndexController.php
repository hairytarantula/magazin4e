<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Form\ImageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
    /**
     * @Route("/", name="app_index_index")
     */
    public function indexAction(Request $request)
    {
        $products = $this->getDoctrine()
          ->getRepository('AppBundle:Product')
          ->findAllInStock();
        $categories = $this->getDoctrine()
          ->getRepository('AppBundle:Category')
          ->findAll();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
          $products, /* query NOT result */
          $request->query->getInt('page', 1)/*page number*/,
          10    /*limit per page*/
        );
        return $this->render(':index:index.html.twig', array('pagination' => $pagination, 'categories' => $categories));
    }

    /**
     * @Route("/upload", name="app_index_upload")
     */
    public function uploadAction(Request $request)
    {
        $p = new Image();
        $form = $this->createForm(ImageType::class, $p);

        if ($request->getMethod() == Request::METHOD_POST) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $m = $this->getDoctrine()->getManager();
                $m->persist($p);
                $m->flush();

                return $this->redirectToRoute('app_index_index');
            }
        }

        return $this->render(':index:upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
