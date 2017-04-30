<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Image;
use AppBundle\Form\ImageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class InfoController extends Controller
{
    /**
     * @Route("/", name="app_info_tos")
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
        return $this->render(':info:tos.html.twig', array('pagination' => $pagination, 'categories' => $categories));
    }

    
}
