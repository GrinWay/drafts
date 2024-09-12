<?php

namespace App\Controller;

use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Form\Type\Config as FormType;
use App\Service;

//#[IsGranted('ROLE_ADMIN')]
#[Route('/config')]
class ConfigController extends AbstractController
{
    public function __construct(
        private readonly Service\StringService $stringService,
        private readonly PropertyAccessorInterface $pa,
        private readonly string $absCustomConfigParametersDir,
        private readonly string $absConfigParametersDir,
    ) {
    }

    #[Route('/edit/mailer', name: 'app_config_edit_mailer')]
    public function index(
        Request $request,
    ): Response {
        $path = $this->stringService->getPath(
            $this->absCustomConfigParametersDir,
            'mailer.yaml',
        );
        $data = Yaml::parseFile($path);

        $options = [];

        $form = $this->createForm(FormType\MailerConfigFormType::class, $data, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (true === $form->get('default')->getData()) {
                $immutablePath = $this->stringService->getPath(
                    $this->absConfigParametersDir,
                    'mailer.yaml',
                );
                $immutableParametersData = $this->pa->getValue(Yaml::parseFile($immutablePath), '[parameters]');
                $parametersData = $this->pa->getValue($data, '[parameters]');
                $data = [
                    'parameters' => \array_intersect_key($immutableParametersData, $parametersData),
                ];
            } else {
                $data = $form->getData();
            }
            $content = Yaml::dump($data);
            \file_put_contents($path, $content);
            return $this->redirectToRoute($request->attributes->get('_route', 'app_home_home'));
        }

        return $this->render('config/edit/mailer.html.twig', [
            'form' => $form,
        ]);
    }
}
