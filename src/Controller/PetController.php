<?php

namespace App\Controller;

use App\Entity\Pet;
use App\Form\PetType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PetController extends AbstractController
{
    #[Route('/pets', name: 'adopt')]
    public function index(): Response
    {
        $pets = $this->getDoctrine()->getRepository(Pet::class)->findAll();
        return $this->render('pet/index.html.twig', [
            'pets' => $pets,
        ]);
    }

    #[Route('/pet/new', name: 'new_pet')]
    public function newPet(Request $request): Response
    {
        $pet = new Pet();
        $form = $this->createForm(PetType::class, $pet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $pet = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($pet);
            $em->flush();
            
            return $this->redirectToRoute("pet", [
                "id" => $pet->getId(),
            ]);
        }


        return $this->render('pet/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/pet/{id}', name: 'pet')]
    public function pet ($id)
    {
        $pet = $this->getDoctrine()->getRepository(Pet::class)->find($id);

        return $this->render("pet/single.html.twig", [
            "pet" => $pet
        ]);
    }
}
