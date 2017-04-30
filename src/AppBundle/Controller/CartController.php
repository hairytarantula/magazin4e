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

/**
 * Cart controller.
 *
 * @Route("/cart")
 */
class CartController extends Controller
{
    /**
     * Add to cart.
     *
     * @Route("/add", name="cart_add")
     * @Method("POST")
     */
    public function addToCartAction(Request $request)
    {
        $session = $request->getSession();
        $current_total = $session->get('current_total', 0);
        $product_id = $request->request->get('product_id');
        $product_qty = $request->request->get('product_qty');
        if($product_qty < 0) {
            return new JsonResponse(
              ['success' => false, 'new_total' => $current_total]
            );
        }
        $product = $this->getDoctrine()
          ->getRepository('AppBundle:Product')
          ->find($product_id);
          
        $products = $session->get('products', []);
        if(!isset($products[$product_id])) {
            $products[$product_id] = 0;
        }
        if($products[$product_id] + $product_qty > $product->getInStock()) {
            return new JsonResponse(
              ['success' => false, 'new_total' => $current_total, 'path' => $this->generateUrl('view_cart')]
            );
        }
        $products[$product_id] += $product_qty;
        $current_total += $product->getPrice() * $product_qty;
        $session->set('current_total', $current_total);
        $session->set('products', $products);

        return new JsonResponse(
          ['success' => true, 'new_total' => $current_total, 'remaining' => $product->getInStock() - $products[$product_id]]
        );
    }

    /**
     * View cart.
     *
     * @Route("/view", name="view_cart")
     * @Method("GET")
     */
    public function viewCartAction(Request $request)
    {
        $session = $request->getSession();
        $products = $session->get('products', []);
        $products_renderable = [];
        foreach ($products as $product_id => $qty) {
            $products_renderable[$product_id] = [
              'product' =>
                $this->getDoctrine()
                  ->getRepository('AppBundle:Product')
                  ->find($product_id),
              'qty' =>
                $qty,
            ];
        }

        return $this->render(
          'cart/view.html.twig',
          array(
            'products' => $products_renderable,
            'total' => $session->get('current_total', 0),
          )
        );
    }

    /**
     * Remove from cart.
     *
     * @Route("/change", name="change_cart")
     * @Method("POST")
     */
    public function changeCartAction(Request $request)
    {
        $session = $request->getSession();
        $current_total = $session->get('current_total', 0);
        $products = $session->get('products', []);
        $product_id = $request->request->get('product_id');
        $product_qty = $request->request->get('product_qty');
        if($product_qty < 0) {
            return new JsonResponse(
              ['success' => false, 'new_total' => $current_total]
            );
        }
        if(isset($products[$product_id])) {
            $product = $this->getDoctrine()
              ->getRepository('AppBundle:Product')
              ->find($product_id);
            $current_total += ($product_qty - $products[$product_id]) * $product->getPrice(
              );
            if($product_qty != 0) {
                $products[$product_id] = $product_qty;
            } else {
                unset($products[$product_id]);
            }

            $session->set('current_total', $current_total);
            $session->set('products', $products);
            $ret = true;

        } else {
            $ret = false;
        }
        $count = 0;
        if (!empty($products)) {
            foreach ($products as $qty) {
                $count += $qty;
            }
        }

        return new JsonResponse(
          [
            'success' => $ret,
            'new_total' => $current_total,
            'total_count' => $count,
          ]
        );
    }
    /**
     * Count cart.
     *
     * @Route("/count", name="cart_count")
     * @Method("GET")
     */
    public function countAction(Request $request)
    {
        $session = $request->getSession();
        $current_total = $session->get('current_total', 0);
        $products = $session->get('products', []);
        $count = 0;
        if(!empty($products)) {
            foreach($products as $qty) {
                $count += $qty;
            }
        }

        return new JsonResponse(['success' => true, 'count' => count($products), 'total_price' => $current_total, 'total_count' => $count]);
    }
}