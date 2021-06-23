<?php

namespace App\Controller;

use App\Entity\Pet;
use App\Entity\Donation;
use App\Form\DonationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $pets_last = $this->getDoctrine()->getRepository(Pet::class)->findPetLastMonth();
        $total = $this->getDoctrine()->getRepository(Donation::class)->findAllAmount();

        return $this->render('home/index.html.twig', [
            'pets_last' => $pets_last,
            'total' => $total["total"],
        ]);
    }

    #[Route('/donate', name: 'donate'), IsGranted("ROLE_USER")]
    public function donate(Request $request): Response
    {
        $donation = new Donation();
        $form = $this->createForm(DonationType::class, $donation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $donation = $form->getData();
            $donation->setDonatedAt(new \DateTimeImmutable());
            $em = $this->getDoctrine()->getManager();

            $em->persist($donation);
            $em->flush();

            $this->addFlash("donation-success", "Merci pour les ".$donation->getAmount()." € !😤🥵🤬👺💀");
            
            return $this->redirectToRoute("home");
        }

        return $this->render('donation/form.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
