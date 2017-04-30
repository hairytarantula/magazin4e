<?php
/**
 * Created by PhpStorm.
 * User: baska
 * Date: 4/29/17
 * Time: 3:19 PM
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * Cart controller.
 *
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * List users
     *
     * @Route("/users", name="admin_list_users")
     * @Method("GET")
     */
    public function listUsersAction(Request $request)
    {
        $users = $this->getDoctrine()
          ->getRepository('UserBundle:User')
          ->findAll();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
          $users, /* query NOT result */
          $request->query->getInt('page', 1)/*page number*/,
          10    /*limit per page*/
        );

        return $this->render(
          'admin/users.html.twig',
          array('pagination' => $pagination)
        );
    }

    /**
     * Ban user.
     *
     * @Route("/user/ban/{id}", name="user_ban_username")
     * @Method("GET")
     */
    public function banUserAction($id) {
        $user = $this->getDoctrine()
          ->getRepository('UserBundle:User')
          ->find($id);
        $user->addRole('ROLE_BANNED');
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new JsonResponse(['success' => true, 'new_text' => 'Unban ' . $user->getUsername()]);
    }

    /**
     * Ban user ip.
     *
     * @Route("/user/ban/{id}/ip", name="user_ban_ip")
     * @Method("GET")
     */
    public function banUserIPAction($id)
    {
        $fs = new Filesystem();
        $user = $this->getDoctrine()
          ->getRepository('UserBundle:User')
          ->find($id);
        $fs->dumpFile('banned.txt', $user->getIpaddress());
        return new JsonResponse(['success' => true, 'new_text' => 'Unban ' . $user->getUsername() . '\'s IP']);
    }

    /**
     * Remove user
     *
     * @Route("/user/delete/{id}", name="user_delete")
     * @Method("GET")
     */
    public function removeAction($id)
    {
        $user = $this->getDoctrine()
          ->getRepository('UserBundle:User')
          ->find($id);
        $user->addRole('ROLE_BANNED');
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return new JsonResponse(['success' => true, 'new_text' => '', 'delete' => 'item-' . $id]);
    }

    /**
     * Edit user
     *
     * @Route("/user/{id}/editred", name="user_edit_red")
     * @Method("GET")
     */
    public function redirecToEditAction($id)
    {
        return new JsonResponse(['success' => true, 'new_text' => '', 'redirect' => $this->generateUrl('user_edit', ['id' => $id])]);
    }
}