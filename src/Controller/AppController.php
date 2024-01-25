<?php

namespace App\Controller;

use App\Repository\DrinkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Psr\Log\LoggerInterface;

class AppController extends AbstractController
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    #[Route('/index', name: 'indexApp')]

    public function index(DrinkRepository $drinkRepository,LoggerInterface $logger): Response
    {
        $data = $drinkRepository->findAllAsArray();
        $logger->info("Получены данные о товарах из базы");
        $html = $this->twig->render('index.html.twig', [
            'data' => $data
        ]);
        
        return new Response($html);
    }
}
