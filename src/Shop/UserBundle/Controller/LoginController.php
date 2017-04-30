<?php

namespace Shop\UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LoginController extends Controller
{
  /**
   * @Cache(maxage=0)
   * @Route("/login", name="login")
   */
  public function LoginAction($route)
  {
    $last_username = $this->get('security.authentication_utils')->getLastUsername();

    return $this->render('UserBundle:Security:login.html.twig', [
      'last_username' => $last_username,
      'route'         => $route,
    ]);
  }
}
