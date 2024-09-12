<?php

namespace App\Controller;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mime\DraftEmail;
use App\Service;
use Symfony\Component\Mime\Part\File as MimeFile;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

#[Route('/email')]
class EmailController extends AbstractController
{
    public function __construct(
        private readonly Service\TwigUtil $twigUtil,
        private readonly MailerInterface $mailer,
        //private readonly TransportInterface $mailer,
    ) {
    }

    #[Route('/send', methods: ['GET'])]
    public function send(): Response
    {
        $imgAbsPath = $this->twigUtil->getLocatedResource('@abs_img_dir/png.png');
        ;
        $imgDataPart = new DataPart(new MimeFile($imgAbsPath));

        $email = new TemplatedEmail();
        $email
            ->htmlTemplate('email/test/index.html.twig')
            ->addPart($imgDataPart)
        ;

        $this->mailer->send($email);

        return new Response('Email was sent to the default recipients.');
    }

    #[Route('/download', methods: ['GET'])]
    public function index(): Response
    {
        $path = $this->twigUtil->getLocatedResource('@abs_img_dir/png.png');
        $imgPart = new DataPart(new MimeFile($path, 'Картинка.png'));

        $content = (new DraftEmail())
            ->html(<<<'__EMAIL__'
			It's a simple html template with Image data part!!!
			__EMAIL__)
            ->addPart($imgPart)
            ->toString()
        ;

        $response = new Response($content);

        $contentType = 'message/rfc822';
        $contentDisposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'Email Template.eml',
        );

        $response->headers->set('Content-Type', $contentType);
        $response->headers->set('Content-Disposition', $contentDisposition);

        return $response;
    }
}
