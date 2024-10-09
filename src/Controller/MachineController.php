<?php

namespace App\Controller;

use App\Type\Note\NoteType;
use App\Entity\Machine;
use App\Form\MachineType;
use App\Repository\MachineRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Mercure\Authorization;
use Symfony\Component\Mercure\Discovery;

#[Route('/machine', name: 'app_machine_')]
final class MachineController extends AbstractController
{
	public function __construct(
		private readonly HubInterface $hub,
		private readonly Authorization $authorization,
	) {
	}
	
    #[Route(name: 'index', methods: ['GET'])]
    public function index(MachineRepository $machineRepository, Discovery $discovery, Request $request): Response
    {
		if (true) {
			//$discovery->addLink($request);
			//$this->authorization->setCookie($request, ['app_machine']);
		}
		
        return $this->render('machine/index.html.twig', [
            'machines' => $machineRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
		Request $request,
		EntityManagerInterface $entityManager,
	): Response {
        $machine = new Machine();
        $form = $this->createForm(MachineType::class, $machine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($machine);
            $entityManager->flush();
			
			$this->addFlash(NoteType::NOTICE, 'Новый machine был добавлен');
			
            return $this->redirectToRoute('app_machine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('machine/new.html.twig', [
            'machine' => $machine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Machine $machine): Response
    {
        return $this->render('machine/show.html.twig', [
            'machine' => $machine,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Machine $machine, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MachineType::class, $machine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_machine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('machine/edit.html.twig', [
            'machine' => $machine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Machine $machine, EntityManagerInterface $entityManager): Response
    {
		$id = $machine->getId();
        if ($this->isCsrfTokenValid('delete'.$id, $request->getPayload()->getString('_token'))) {
            $entityManager->remove($machine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_machine_index', [], Response::HTTP_SEE_OTHER);
    }
	
	private function publish(string $block, array $templateVars = [], string $topic = 'AppEntityMachine'): void {
		$streamContent = $this->renderBlockView(
			'machine/Machine.stream.html.twig',
			$block,
			$templateVars,
		);
		$this->hub->publish(new Update($topic, $streamContent));
	}
}
