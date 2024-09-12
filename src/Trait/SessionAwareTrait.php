<?php

namespace App\Trait;

use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

//trait SessionAwareTrait + write test when service uses this method with (pass request is needless) and without autowiring (need to pass request or throw)
trait SessionAwareTrait
{
    protected ?RequestStack $sessionAwareTraitRequestStack = null;

    #[Required]
    public function _setRequiredOfSessionAwareTrait(
        RequestStack $sessionAwareTraitRequestStack,
    ): void {
        $this->sessionAwareTraitRequestStack = $sessionAwareTraitRequestStack;
    }


    //###> API ###

    /*
     * Passed Reques has a higher priority than Autowiring
     *
     * @throws \LogicException When you created service without Symfony Autowiring and didn't pass the Request object
     * @throws SessionNotFoundException When session is not set properly
     */
    public function getSession(?Request $request = null): SessionInterface
    {
        if (null === $request && null === $this->sessionAwareTraitRequestStack) {
            $message = \sprintf(
                'You have to pass "%1$s" object using "%2$s" method, cuz you created "%3$s" on your own but not relying on Symfony Autowiring',
                Request::class,
                __CLASS__ . '::' . __FUNCTION__,
                __CLASS__,
            );
            throw new \LogicException($message);
        }

        return null === $request ? $this->sessionAwareTraitRequestStack->getSession() : $request->getSession();
    }

    //###< API ###
}
