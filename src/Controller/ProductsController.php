<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class ProductsController extends AbstractController
{
    #[Route('/products', name: 'products')]
    public function index(): Response
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/new', name: 'new_product'), IsGranted("ROLE_ADMIN")]
    public function newProduct(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $product = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            
            $image = $product->getImage();
            $imageName = md5(uniqid()).'.'.$image->guessExtension();
            $product->setImage($imageName);
            $image->move($this->getParameter('upload_directory'), $imageName);

            $em->persist($product);
            $em->flush();
            
            return $this->redirectToRoute("product", [
                "id" => $product->getId(),
            ]);
        }

        return $this->render('product/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    
    #[Route('/product/{id}', name: 'product')]
    public function product(Product $product)
    {
        return $this->render("product/single.html.twig", [
            "product" => $product
        ]);
    }

    #[Route('/product/update/{id}', name: 'update_product'), IsGranted("ROLE_ADMIN")]
    public function updateProduct(Product $product, Request $request): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $image = $product->getImage();
            $imageName = md5(uniqid()).'.'.$image->guessExtension();
            $product->setImage($imageName);
            $image->move($this->getParameter('upload_directory'), $imageName);
            
            $em->persist($product);
            $em->flush();
            
            return $this->redirectToRoute("product", [
                "id" => $product->getId(),
            ]);
        }


        return $this->render('product/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/product/delete/{id}', name: 'delete_product'), IsGranted("ROLE_ADMIN")]
    public function deleteProduct(Product $product): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        $this->addFlash("product-danger", "Produit supprimé :(");
    
        return $this->redirectToRoute("products");
    }
}