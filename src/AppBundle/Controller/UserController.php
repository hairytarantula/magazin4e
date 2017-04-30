<?php
/**
 * Created by PhpStorm.
 * User: baska
 * Date: 4/29/17
 * Time: 3:19 PM
 */

namespace AppBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Cart controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * List users
     *
     * @Route("/{id}", name="user_view")
     * @Method("GET")
     */
    public function showAction(Request $request, $id)
    {
        $user = $this->getDoctrine()
          ->getRepository('UserBundle:User')
          ->find($id);
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('@FOSUser/Profile/show.html.twig', array(
          'user' => $user,
        ));
    }

    /**
     * view inventoty users
     *
     * @Route("/{id}/inventory", name="user_view_inventory")
     * @Method("GET")
     */
    public function inventoryAction(Request $request, $id)
    {
        $user = $this->getDoctrine()
          ->getRepository('UserBundle:User')
          ->find($id);
        if (!is_object($user) || !$user instanceof UserInterface || $user->getId() != $id || $user->hasRole('ROLE_EDITOR')) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return new JsonResponse($user->getProducts());
    }

    /**
     * Edit user
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method("GET")
     */
    public function editAction(Request $request, $id)
    {
        $user = $this->getDoctrine()
          ->getRepository('UserBundle:User')
          ->find($id);
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $current_user = $this->getUser();
        if($current_user == $user || $user->hasRole('ROLE_ADMIN')) {
            /** @var $dispatcher EventDispatcherInterface */
            $dispatcher = $this->get('event_dispatcher');

            $event = new GetResponseUserEvent($user, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

            if (null !== $event->getResponse()) {
                return $event->getResponse();
            }

            /** @var $formFactory FactoryInterface */
            $formFactory = $this->get('fos_user.profile.form.factory');

            $form = $formFactory->createForm();
            $form->setData($user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var $userManager UserManagerInterface */
                $userManager = $this->get('fos_user.user_manager');

                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->generateUrl('fos_user_profile_show');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }

            return $this->render('@FOSUser/Profile/edit.html.twig', array(
              'form' => $form->createView(),
            ));
        } else {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
    }
}