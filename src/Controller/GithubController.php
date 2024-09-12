<?php

namespace App\Controller;

use App\Entity\User;
use App\Type\Note\NoteType;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Messenger\Command\Message\GitHubDeleteRepository;
use Symfony\Component\Messenger\MessageBusInterface;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Carbon\Carbon;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
* @codeCoverageIgnore
*/
#[Route('/github')]
#[IsGranted('IS_AUTHENTICATED')]
class GithubController extends AbstractController
{
    public function __construct(
        private readonly ClientRegistry $clientRegistry,
    ) {
    }

    #[Route('/', name: 'app_github_index')]
    public function index()
    {
        return $this->clientRegistry->getClient('github_main')
            ->redirect(
                scopes: [
                    'read:packages',
                    'read:project',
                ],
                options: [

                ],
            )
        ;
    }

    #[Route('/login', name: 'app_github_login')]
    public function login(
        Request $request,
    ) {
        $client = $this->clientRegistry->getClient('github_main');
        $user = $client->fetchUser();

        return $this->redirectToRoute('app_home_home');
    }

    #[Route('/app', name: 'app_github_app')]
    public function app(
        Request $request,
    ) {
        $client = $this->clientRegistry->getClient('github_main');
        $user = $client->fetchUser();
        \dump(__METHOD__);
        return $this->redirectToRoute('app_home_home');
    }

    #[Route('/delete/repo/{repoName?}', name: 'app_github_delete_repo')]
    public function deleteRepo(
        Request $request,
        MessageBusInterface $bus,
        $repoName,
        #[CurrentUser]
        ?User $user,
    ): Response {
        $repoName = 'delete';

        if (null === $user) {
            $this->addFlash(NoteType::NOTICE, 'Пользователь не аутентифицирован. Ничего не делалось.');
            return $this->redirectToRoute('app_home_home');
        }
        $userIdentifier = $user->getUserIdentifier();

        $bus->dispatch(new GitHubDeleteRepository(
            userIdentifier: $userIdentifier,
            repoName: $repoName,
        ));

        return $this->redirectToRoute('app_home_home');
    }
}
