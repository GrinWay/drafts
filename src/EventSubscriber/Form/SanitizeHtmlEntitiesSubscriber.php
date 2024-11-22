<?php

namespace App\EventSubscriber\Form;

use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;
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

class SanitizeHtmlEntitiesSubscriber implements EventSubscriberInterface
{
	const SANITIZE_HTML_ENTITIES = 'sanitize_html_entities';
	
    public function __construct(
        private readonly PropertyAccessorInterface $pa,
        private readonly HtmlSanitizerInterface $sanitizer,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'sanitizeHtmlEntities',
        ];
    }

    public function sanitizeHtmlEntities(PreSubmitEvent $event): void
    {
		$form = $event->getForm();
		$data = $event->getData() ?? null;
		$options = $form->getConfig()->getOptions();
		
		if (null === $data) {
			return;
		}
		
		if (true !== $this->pa->getValue($options, \sprintf('[%s]', self::SANITIZE_HTML_ENTITIES))) {
			return;
		}
		
		$dataWithHtmlEntities = $this->sanitizer->sanitizeFor('textarea', $data);
		
		$event->setData($dataWithHtmlEntities);
    }
}
