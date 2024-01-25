<?php

namespace App\Controller;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]

    public function index(AuthenticationUtils $authenticationUtils, LoggerInterface $logger): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        $logger->info("Вошел юзер" . $lastUsername);
        return $this->render('login/index.html.twig', [

            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
        //return $this->redirectToRoute('indexApp');
    }

}
