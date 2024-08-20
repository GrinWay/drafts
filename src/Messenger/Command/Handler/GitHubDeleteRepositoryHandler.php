<?php

namespace App\Messenger\Command\Handler;

use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Repository\UserRepository;
use App\Messenger\Command\Message\GitHubDeleteRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Messenger\AbstractHandler;
use Symfony\Component\Process\Messenger\RunProcessMessage;

#[AsMessageHandler(
    bus: 'command.bus',
)]
class GitHubDeleteRepositoryHandler extends AbstractHandler
{
    public function __construct(
		private readonly UserRepository $userRepo,
		private readonly MessageBusInterface $bus,
	) {}
	
    public function __invoke(GitHubDeleteRepository $message): void {
		$user = $this->userRepo->findOneBy([
			'email' => $message->userIdentifier,
		]);
		
		
		$accessToken = $user->getGitHub()->getAccessToken();
		$username = $user->getGitHub()->getId();
		
		$owner = 'GrinWay';
		$repoName = $message->repoName;
		$uri = \sprintf(
			'https://api.github.com/repos/%s/%s',
			$owner,
			$repoName,
		);
		
		$process = [
			'curl',
			
			'-L',
			
			'-X',
			'DELETE',
			
			'-H',
			'Accept: application/vnd.github+json',
			/*
			'-H',
			'Authorization: token '.$accessToken,
			*/
			'-H',
			'Authorization: Bearer '.$accessToken,
			'-H',
			'X-GitHub-Api-Version: 2022-11-28',
			
			//'-u '.$username,
			
			$uri,
		];
		$answer = $this->bus->dispatch(new RunProcessMessage($process));
		\dump($answer->last(HandledStamp::class)->getResult());
	}
}
