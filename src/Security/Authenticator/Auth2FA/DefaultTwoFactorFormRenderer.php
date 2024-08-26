<?php

namespace App\Security\Authenticator\Auth2FA;

use function Symfony\component\string\u;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\ValidationException;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Totp\TotpAuthenticatorInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorFormRendererInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Environment;
use Symfony\Component\Form\FormFactory;
use App\Form\Type\Auth2FAFormType;
use Symfony\Component\Form\FormFactoryInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticator;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class DefaultTwoFactorFormRenderer implements TwoFactorFormRendererInterface {
	public function __construct(
        private readonly Environment $twigEnvironment,
		private readonly TokenStorageInterface $tokenStorage,
		private readonly FormFactoryInterface $formFactory,
		private readonly PropertyAccessorInterface $pa,
		private readonly ?TotpAuthenticatorInterface $totpAuthenticator = null,
		private readonly ?GoogleAuthenticator $googleAuthenticator = null,
		#[Autowire('%app.abs_img_dir%')]
		private readonly string $absImgDir,
	) {}
	
	public function renderForm(Request $request, array $templateVars): Response
    {
		$data = [];
		
		$form = $this->formFactory->create(Auth2FAFormType::class, $data, options: [
			'csrf_protection' => $this->pa->getValue($templateVars, '[isCsrfProtectionEnabled]'),
			'csrf_field_name' => $this->pa->getValue($templateVars, '[csrfParameterName]'),
			'csrf_token_id' => $this->pa->getValue($templateVars, '[csrfTokenId]'),
		]);
		
		$qrCodeUri = $this->getQRCodeUri($templateVars);
        
		$providerName = $this->pa->getValue($templateVars, '[twoFactorProvider]');
		
		$vars = \array_merge(
			$templateVars,
			[
				'qr_code_uri' => $qrCodeUri,
				'form' => $form->createView(),
				'provider_name' => $providerName,
			],
		);
		$content = $this->twigEnvironment->render('2fa/2fa.thml.twig', $vars);
		
        $response = new Response();
		$response->setContent($content);
		
		return $response;
    }
	
	private function getQRCodeUri(array $templateVars): ?string {
		$user = $this->tokenStorage->getToken()?->getUser();
		
		if (null === $user) {
			throw new \LogicException('User must exist in the token storage.');
		}
		
		$providerName = $this->pa->getValue($templateVars, '[twoFactorProvider]');
		
		$qrCodeContent = null;
		
		if ('google' === $providerName) {
			$qrCodeContent = $this->googleAuthenticator->getQRContent($user);
		} elseif ('totp' === $providerName) {
			$qrCodeContent = $this->totpAuthenticator->getQRContent($user);			
		} else {
			return null;
		}
		
		if (null === $qrCodeContent) {
			throw new \LogicException('Содержимое QR-Code не может быть пустым.');
		}

		$writer = new PngWriter();

		$qrCode = QrCode::create($qrCodeContent)
			->setEncoding(new Encoding('UTF-8'))
			->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
			->setSize(300)
			->setMargin(10)
			->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
			->setForegroundColor(new Color(0, 0, 0))
			->setBackgroundColor(new Color(255, 255, 255))
		;
		/*
		*/
		$logo = Logo::create($this->absImgDir.'/symfony.png')
			->setResizeToWidth(50)
			->setPunchoutBackground(true)
		;

		// Create generic label
		$label = Label::create(u($providerName)->title()->ensureEnd(' Auth').'')
			->setTextColor(new Color(0, 0, 0))
		;			
		
		$result = $writer->write(qrCode: $qrCode, logo: $logo);
		
		$qrCodeUri = $result->getDataUri();
		
		return $qrCodeUri;
	}
}