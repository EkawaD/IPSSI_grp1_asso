<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login( AuthenticationUtils $authenticationUtils, $data=[]): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }        

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();


        if($data) {
            $email = $data[0];
            $pw = $data[1];
        }else {
            $email = '';
            $pw = '';
        }

        return $this->render('security/login.html.twig', ['email' => $email, 'error' => $error, 'current_password' => $pw]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        return $this->redirectToRoute('home');
    }
}
