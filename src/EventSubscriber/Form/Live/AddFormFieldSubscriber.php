<?php

namespace App\EventSubscriber\Form\Live;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddFormFieldSubscriber implements EventSubscriberInterface
{
	public function __construct(
		private readonly \Closure $isAddField,
		private readonly string $formFieldName,
		private readonly string $formFieldType = FormType\TextType::class,
		private readonly array $formFieldOptions = [],
	) {}
	
	public static function getSubscribedEvents(): array
    {
        return [
			FormEvents::PRE_SUBMIT => 'addField',
		];
    }

    public function addField(FormEvent $event): void
    {
		if (($this->isAddField)($event)) {
			$event->getForm()->add($this->formFieldName, $this->formFieldType, $this->formFieldOptions);
		}
    }
}