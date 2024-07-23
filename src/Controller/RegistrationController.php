<?php

namespace App\Controller;

use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Validation;
use App\Entity\User;
use App\Form\Type\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Security\Authenticator\FormLoginAuthenticator;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
		Request $request,
		UserPasswordHasherInterface $userPasswordHasher,
		EntityManagerInterface $entityManager,
		Security $security,
	): Response {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $plainPassword = $form->get('plainPassword')->getData();
			$hashedPassword = $userPasswordHasher->hashPassword(
				$user,
				$plainPassword,
			);
			
			$user->setPassword($hashedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

			$security->login(
				$user,
				FormLoginAuthenticator::class,
			);
			
            return $this->redirectToRoute('app_home_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
