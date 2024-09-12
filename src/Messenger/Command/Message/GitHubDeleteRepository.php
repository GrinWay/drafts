<?php

namespace App\Messenger\Command\Message;

class GitHubDeleteRepository
{
    public function __construct(
        public readonly string|int $userIdentifier,
        public readonly string $repoName,
    ) {
    }
}
