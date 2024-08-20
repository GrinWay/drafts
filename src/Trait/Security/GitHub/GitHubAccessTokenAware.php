<?php

namespace App\Trait\Security\GitHub;

use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\Request;

trait GitHubAccessTokenAware {
	
	public function getGitHubAccessToken(Request $request): ?AccessToken {
		return $request->getSession()?->get('app.security.github.access_token');
	}
	
	public function setGitHubAccessToken(Request $request, AccessToken $accessToken): static {
		$request->getSession()?->set('app.security.github.access_token', $accessToken);
		return $this;
	}
	
	public function removeGitHubAccessToken(Request $request): static {
		$request->getSession()?->remove('app.security.github.access_token');
		return $this;
	}
}