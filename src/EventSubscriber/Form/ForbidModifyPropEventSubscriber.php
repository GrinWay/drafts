<?php

namespace App\EventSubscriber\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Form\Event\PostSetDataEvent;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormEvents;
use App\Contract\Form\PreventModifyingPropsOfEntity;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use App\Service\Form\FormEventHelper;

// TODO: PreventModifyingPropsOfEntity
class ForbidModifyPropEventSubscriber implements EventSubscriberInterface {
	
	public function __construct(
		private readonly PropertyAccessorInterface $pa,
		private readonly PreventModifyingPropsOfEntity $forbiddenPropsAware,
	) {
	}
	
	public static function getSubscribedEvents(): array {
        return [
            //###> CREATE_FORM ###
			FormEvents::PRE_SET_DATA => 'onPreSetData',
            FormEvents::POST_SET_DATA => 'onPostSetData',
            //###< CREATE_FORM ###
			// FROM NOW... FORM_BUILT
			
            //###> FORM::SUBMIT (uses FORM_BUILT) ###
            FormEvents::PRE_SUBMIT => 'onPreSubmit',
            FormEvents::SUBMIT => 'onSubmit',
            FormEvents::POST_SUBMIT => 'onPostSubmit',
            //###< FORM::SUBMIT ###
        ];
    }
	
	public function onPreSetData(PreSetDataEvent $e): void {
		\dump('root pre set data', $e->getData());
		//$e->setData($e->getData()->setName('Dracula'));
	}
	
	public function onPostSetData(PostSetDataEvent $e): void {
		\dump('root post set data', $e->getForm()->getData());
		
		//###> FADE
		$this->callbackForForbiddenProps(
			$e->getForm(),
			getOriginData: static fn($forbiddenPropName) => $e->getForm()->get($forbiddenPropName)->getData(),
			executeAlgo: static function (string $forbiddenPropName) use (&$e) {
			$e->getForm()->add(
				$forbiddenPropName,
				options: [
					'attr' => [
						'class' => 'disabled',
						'disabled' => 'disabled',
						'readonly' => 'readonly',
					],
				]
			);
		});
	}
	
	public function onPreSubmit(PreSubmitEvent $e): void {
		\dump('root pre submit', $newFormData = $e->getData(), $originData = $e->getForm()->getData());
		
		//###> UNSET FORBIDDEN FROM PAYLOAD
		$this->callbackForForbiddenProps(
			$e->getForm(),
			getOriginData: static fn($forbiddenPropName) => $e->getForm()->get($forbiddenPropName)->getData(),
			executeAlgo: static function (string $forbiddenPropName) use (&$e) {
				/**
				* ignore effect: only if submit(, clearMissing: false)
				* set null effect: as if DEFAULT submit(, clearMissing: true) == handleRequest
				*/
				$newFormData = $e->getData();
				unset($newFormData[$forbiddenPropName]);
				$e->setData($newFormData);
			},
		);
	}
	
	public function onSubmit(SubmitEvent $e): void {
		\dump('root submit', $e->getData(), $e->getForm()->getData());
		\dump('is view transformed', $e->getData());
		//$e->getForm()->add('deadLine');
	}
	
	public function onPostSubmit(PostSubmitEvent $e): void {
		\dump('root post submit', $e->getData(), $e->getForm()->getData());
	}
	
	/**
	* calls $getOriginData if only $forbiddenPropName exists in $form
	* calls $executeAlgo if only $getOriginData called
	*/
	private function callbackForForbiddenProps(Form $form, callable $getOriginData, callable $executeAlgo): void {
		foreach($this->forbiddenPropsAware as $forbiddenPropName) {
			if ($this->isMakeFieldForbidden($form, $forbiddenPropName, $getOriginData)) {
				$executeAlgo($forbiddenPropName);
			}
		}
	}
	
	private function isMakeFieldForbidden(Form $form, string $forbiddenPropName, callable $getOriginData): bool {
		if (!$form->has($forbiddenPropName)) {
			return false;
		}
		return null !== $getOriginData($forbiddenPropName);
	}
}