<?php

declare(strict_types=1);

namespace App\Security\Auth2FA\CodeGenerator;

use function random_int;

use Scheb\TwoFactorBundle\Mailer\AuthCodeMailerInterface;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Scheb\TwoFactorBundle\Model\PersisterInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Email\Generator\CodeGeneratorInterface;

class CodeGenerator implements CodeGeneratorInterface
{
    public function __construct(
        private readonly PersisterInterface $persister,
        private readonly AuthCodeMailerInterface $mailer,
        private readonly int $digits,
    ) {
    }

    public function generateAndSend(TwoFactorInterface $user): void
    {
        $code = $this->generateCode();
        $user->setEmailAuthCode((string) $code);
        $this->persister->persist($user);
        $this->mailer->sendAuthCode($user);
    }

    public function reSend(TwoFactorInterface $user): void
    {
        $this->mailer->sendAuthCode($user);
    }

    protected function generateCode()
    {
        $set = '1234567890';
        $result = '';

        for ($i = 0; $i < $this->digits; ++$i) {
            $result .= \substr(\str_shuffle($set), 0, 1);
        }

        return $result;
    }
}
