<?php

namespace App\Controller;

use App\Entity\AppEasyAdminCinema;
use App\Form\AppEasyAdminCinemaType;
use App\Repository\AppEasyAdminCinemaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

#[Route('/app/easy/admin/cinema')]
final class AppEasyAdminCinemaController extends AbstractController
{
    #[Route(name: 'app_easy_admin_cinema_index', methods: ['GET'])]
    public function index(AppEasyAdminCinemaRepository $appEasyAdminCinemaRepository): Response
    {
        return $this->render('app_easy_admin_cinema/index.html.twig', [
            'app_easy_admin_cinemas' => $appEasyAdminCinemaRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_easy_admin_cinema_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $appEasyAdminCinema = new AppEasyAdminCinema();
        $form = $this->createForm(AppEasyAdminCinemaType::class, $appEasyAdminCinema);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($appEasyAdminCinema);
            $entityManager->flush();

            return $this->redirectToRoute('app_easy_admin_cinema_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('app_easy_admin_cinema/new.html.twig', [
            'app_easy_admin_cinema' => $appEasyAdminCinema,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_easy_admin_cinema_show', methods: ['GET'])]
    public function show(AppEasyAdminCinema $appEasyAdminCinema): Response
    {
        return $this->render('app_easy_admin_cinema/show.html.twig', [
            'app_easy_admin_cinema' => $appEasyAdminCinema,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_easy_admin_cinema_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AppEasyAdminCinema $appEasyAdminCinema, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AppEasyAdminCinemaType::class, $appEasyAdminCinema);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_easy_admin_cinema_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('app_easy_admin_cinema/edit.html.twig', [
            'app_easy_admin_cinema' => $appEasyAdminCinema,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_easy_admin_cinema_delete', methods: ['POST'])]
    public function delete(Request $request, AppEasyAdminCinema $appEasyAdminCinema, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appEasyAdminCinema->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($appEasyAdminCinema);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_easy_admin_cinema_index', [], Response::HTTP_SEE_OTHER);
    }
	
	/**
     * AbstractCrudController
     */
	public static function getEntityFqcn(): string {
		return AppEasyAdminCinema::class;
	}
}
