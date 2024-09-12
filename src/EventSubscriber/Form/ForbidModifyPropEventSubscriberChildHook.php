<?php

namespace App\EventSubscriber\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\PostSetDataEvent;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormEvents;
use App\Contract\Form\PreventModifyingPropsOfEntity;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use App\Service\Form\FormEventHelper;

// TODO: PreventModifyingPropsOfEntity
class ForbidModifyPropEventSubscriberChildHook implements EventSubscriberInterface
{
    public function __construct(
        private readonly PropertyAccessorInterface $pa,
        private readonly PreventModifyingPropsOfEntity $forbiddenPropsAware,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
            FormEvents::SUBMIT => 'onSubmit',
            FormEvents::POST_SUBMIT => 'onPostSubmit',
        ];
    }

    public function onPreSubmit(PreSubmitEvent $e): void
    {
        \dump('child pre submit');
        //return;
    }

    public function onSubmit(SubmitEvent $e): void
    {
        \dump('child submit');
        //return;
    }

    public function onPostSubmit(PostSubmitEvent $e): void
    {
        \dump('child post submit');
    }
}
