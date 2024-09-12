<?php

namespace App\Security\Authenticator\LoginLink;

use Symfony\Component\Security\Core\Signature\SignatureHasher;
use Symfony\Component\Security\Core\Signature\ExpiredSignatureStorage;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class LoginLinkSignatureHasher extends SignatureHasher
{
    public function __construct(PropertyAccessorInterface $propertyAccessor, array $signatureProperties, #[\SensitiveParameter] string $secret, ?ExpiredSignatureStorage $expiredSignaturesStorage = null, ?int $maxUses = null)
    {
        parent::__construct(
            propertyAccessor: $propertyAccessor,
            signatureProperties: $signatureProperties,
            secret: $secret,
            expiredSignaturesStorage: $expiredSignaturesStorage,
            maxUses: $maxUses,
        );
    }
}
