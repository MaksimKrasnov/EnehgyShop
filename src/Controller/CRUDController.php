<?php

namespace App\Controller;

use App\Entity\Drink;
use App\Form\AddDrinkFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use App\Repository\DrinkRepository;

class CRUDController extends AbstractController
{
    // #[Route('/c/r/u/d', name: 'app_c_r_u_d')]
    // public function index(): Response
    // {
    //     return $this->render('crud/index.html.twig', [
    //         'controller_name' => 'CRUDController',
    //     ]);
    // }
    #[Route('/adminPanel', name: 'adminPanel')]
    public function showAdminPanel()
    {
        return $this->render('crud/admin.html.twig');
    }

    #[Route('/drink/add', name: 'addDrink')]
    public function addProduct(
        Request $request,
        EntityManagerInterface $manager,
        LoggerInterface $logger
    ): Response {
        $new_drink = new Drink();

        $form = $this->createForm(AddDrinkFormType::class, $new_drink);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $new_drink = $form->getData();

            // $logger->info("Добавлен товар  Название: " . $name . " Цена " . $price .
            //     " Объем " . $volume . ' Описание товара ' . $description);


            $manager->persist($new_drink);
            $manager->flush();
        }
        return $this->render('crud/index.html.twig', [
            'addForm' => $form,
        ]);
    }
    #[Route('/drink/delete', name: 'deleteDrink')]
    public function showDrink(
        DrinkRepository $drinkRepository,
        LoggerInterface $logger
    ) {
        $data = $drinkRepository->findAllAsArray();
        $logger->info("Получены данные о товарах из базы");
        $html = $this->render('crud/deleteDrink.html.twig', [
            'data' => $data
        ]);
        return new Response($html);
    }
    #[Route('/drink/delete/{id}', name: 'deleteDrinkById')]
    public function deleteDrink(Request $request, EntityManagerInterface $entityManager, int $id)
    {
        $drink = $entityManager->getRepository(Drink::class)->find($id);
        if (!$drink) {
            throw $this->createNotFoundException(
                'No drink found for id ' . $id
            );
        }

        $entityManager->remove($drink);
        $entityManager->flush();
        return $this->render('crud/admin.html.twig');
    }
}
