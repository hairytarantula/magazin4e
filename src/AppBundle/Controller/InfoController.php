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
     * @Route("/info/tos", name="app_info_tos")
     */
    public function indexAction(Request $request)
    {
        
        return $this->render(':info:tos.html.twig');
    }
    /**
     * @Route("/info/cookies", name="app_info_cookies")
     */
    public function cookiesAction(Request $request)
    {
        
        return $this->render(':info:cookies.html.twig');
    }
        /**
     * @Route("/info/privacy", name="app_info_privacy")
     */
    public function privacyAction(Request $request)
    {
        
        return $this->render(':info:privacy.html.twig');
    }


    
}
