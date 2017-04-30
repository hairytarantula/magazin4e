<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Form\CategoryType;
/**
 * Category controller.
 *
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * Lists all Category entities.
     *
     * @Route("/", name="category_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')->findAll();
        return $this->render('category/index.html.twig', array(
          'categories' => $categories,
        ));
    }

    /**
     * Autocomplete Category entities.
     *
     * @Route("/autocomplete", name="category_autocomplete")
     * @Method("GET")
     */
    public function autocompleteAction(Request $request)
    {
        $term = trim(strip_tags($request->get('term')));
        $names = array();
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppBundle:Category')->createQueryBuilder('c')
          ->where('c.title LIKE :name')
          ->setParameter('name', '%'.$term.'%')
          ->getQuery()
          ->getResult();
        foreach ($entities as $entity)
        {
            array_push($names, array('label'=>$entity->getTitle(), 'value'=>$entity->getTitle()));
        }
        return new JsonResponse($names,200);

    }
    /**
     * Creates a new Category entity.
     *
     * @Route("/new", name="category_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $category = new Category();
        $form = $this->createForm('AppBundle\Form\CategoryType', $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('category_show', array('id' => $category->getId()));
        }
        return $this->render('category/new.html.twig', array(
          'category' => $category,
          'form' => $form->createView(),
        ));
    }
    /**
     * Finds and displays a Category entity.
     *
     * @Route("/{id}", name="category_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);
        $products = $this->getDoctrine()
          ->getRepository('AppBundle:Product')
          ->findByCategory($category->getTitle());
          $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
          $products, /* query NOT result */
          $request->query->getInt('page', 1)/*page number*/,
          10    /*limit per page*/
        );
        return $this->render('category/show.html.twig', array(
          'category' => $category,
          'pagination_products' => $pagination,
          'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Displays a form to edit an existing Category entity.
     *
     * @Route("/{id}/edit", name="category_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Category $category)
    {
        $deleteForm = $this->createDeleteForm($category);
        $editForm = $this->createForm('AppBundle\Form\CategoryType', $category);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('category_edit', array('id' => $category->getId()));
        }
        return $this->render('category/edit.html.twig', array(
          'category' => $category,
          'edit_form' => $editForm->createView(),
          'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Category entity.
     *
     * @Route("/{id}", name="category_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Category $category)
    {
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }
        return $this->redirectToRoute('category_index');
    }
    /**
     * Creates a form to delete a Category entity.
     *
     * @param Category $category The Category entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Category $category)
    {
        return $this->createFormBuilder()
          ->setAction($this->generateUrl('category_delete', array('id' => $category->getId())))
          ->setMethod('DELETE')
          ->getForm()
          ;
    }
}