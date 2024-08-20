<?php

namespace App\Controller;

use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Validation;
use App\Entity\User;
use App\Entity\UserPassport;
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
use App\Contract\Util\IpUtilsInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * @codeCoverageIgnore
 */
class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
		Request $request,
		UserPasswordHasherInterface $userPasswordHasher,
		EntityManagerInterface $entityManager,
		Security $security,
		CsrfTokenManagerInterface $csrfTokenManager,
		PropertyAccessorInterface $propertyAccessor,
	): Response {
        $user = new User(
			passport: new UserPassport(
				name: 's',
				lastName: 's',
			),
			email: 'ss',
			//_hiddenPoly: '_hidden',
		);
        $form = $this->createForm(
			RegistrationFormType::class,
			// $user,
		);
		//\dump($form->get('_csrf_token')?->getData());		
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			/*
			// encode the plain password
            $plainPassword = $form->get('plainPassword')->getData();
			$hashedPassword = $userPasswordHasher->hashPassword(
				$user,
				$plainPassword,
			);
			$user->setPassword($hashedPassword);
			*/
			$user = $form->getData();

            $entityManager->persist($user);
			//\dd($user);
            $entityManager->flush();

			$response = $security->login(
				$user,
				'form_login',//FormLoginAuthenticator::class,
			);
			
            return $response ?? $this->redirectToRoute('app_home_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
