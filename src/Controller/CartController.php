<?php

namespace App\Controller;

use App\Entity\CartProduct;
use App\Form\CartProductType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart'), IsGranted('ROLE_USER')]
    public function index(Request $request): Response
    {

        $cart = $this->getUser()->getCart();
        $cartUser = $cart->getCartProducts();
        $total = $this->getDoctrine()->getRepository(CartProduct::class)->findTotalPrice($cart);


        return $this->render('cart/index.html.twig', [
            'cart' => $cartUser,
            'total' => $total['total'],
        ]);
    }


    #[Route('/cart/update/{id}', name: 'update_qt'), IsGranted('ROLE_USER')]
    public function update_qt(CartProduct $cartProduct, Request $request): Response
    {
        $qt = $request->request->get('qt');

        if ($qt <= 0) { return $this->redirectToRoute('cart'); }
        // Block try/catch 
        // multiple form with EntityType ???

        $em = $this->getDoctrine()->getManager();

        $cartProduct->setQuantity($qt);
        $em->flush();

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/buy', name: 'buy'), IsGranted('ROLE_USER')]
    public function buySuccess(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $cart = $this->getUser()->getCart();
        // $cartUser = $this->getDoctrine()->getRepository(CartProduct::class)->deleteCart($cart); WHY NOT ?
        $cartUser = $this->getDoctrine()->getRepository(CartProduct::class)->findByCart($cart);

        foreach ($cartUser as $c) {
            $em->remove($c);
        }
        $em->flush();

        $this->addFlash('success_buy', 'Merci pour vos achats !');

        return $this->redirectToRoute('cart');
    }
}
