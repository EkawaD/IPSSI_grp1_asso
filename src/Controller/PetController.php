<?php

namespace App\Controller;

use App\Entity\Pet;
use App\Form\PetType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class PetController extends AbstractController
{
    #[Route('/pets', name: 'adopt')]
    public function index(): Response
    {
        $pets = $this->getDoctrine()->getRepository(Pet::class)->findBy(["adoptedAt" => null]);
        $pets_last = $this->getDoctrine()->getRepository(Pet::class)->findPetLastMonth();
        return $this->render('pet/index.html.twig', [
            'pets' => $pets,
            'pets_last' => $pets_last,
        ]);
    }

    #[Route('/pet/new', name: 'new_pet'), IsGranted("ROLE_ADMIN")]
    public function newPet(Request $request): Response
    {
        $pet = new Pet();
        $form = $this->createForm(PetType::class, $pet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $pet = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $image = $pet->getImage();
            $imageName = md5(uniqid()).'.'.$image->guessExtension();
            $pet->setImage($imageName);
            $image->move($this->getParameter('upload_directory'), $imageName);

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

    #[Route('/pet/update/{id}', name: 'update_pet'), IsGranted("ROLE_ADMIN")]
    public function updatePet(Pet $pet, Request $request): Response
    {
        $form = $this->createForm(PetType::class, $pet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pet = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $image = $pet->getImage();
            $imageName = md5(uniqid()).'.'.$image->guessExtension();
            $pet->setImage($imageName);
            $image->move($this->getParameter('upload_directory'), $imageName);
            
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

    #[Route('/pet/delete/{id}', name: 'delete_pet'), IsGranted("ROLE_ADMIN")]
    public function deletePet(Pet $pet): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($pet);
        $em->flush();

        $this->addFlash("pet-danger", "Pet est dead :(");
    
        return $this->redirectToRoute("adopt");
    }

    #[Route('/pet/{id}', name: 'pet')]
    public function pet(Pet $pet)
    {
        return $this->render("pet/single.html.twig", [
            "pet" => $pet
        ]);
    }
}
