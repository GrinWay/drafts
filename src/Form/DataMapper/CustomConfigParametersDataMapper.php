<?php

namespace App\Form\DataMapper;

use function Symfony\component\string\u;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\DataMapperInterface;
use App\Service\CarbonService;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CustomConfigParametersDataMapper implements DataMapperInterface {
	private PropertyAccessorInterface $pa;
	private ParameterBagInterface $parameterBag;
	private array $constructedCustomParameters = [];
	
	public function mapDataToForms(mixed $viewData, \Traversable $forms): void
	{
		if (null === $viewData) {
			return;
		}
		$forms = \iterator_to_array($forms);
		
		$data = $this->pa->getValue($viewData, '[parameters]');
		
		if (null === $data) {
			return;
		}
		
		if (!\is_array($data)) {
			throw new \LogicException('Passed data must be: [parameters][]');
		}
		
		foreach($data as $key => $nonResolvedValue) {
			if (!isset($forms[$this->getEnvNameOnly($key)])) {
				continue;
			}
			$value = $this->parameterBag->get($key);
			$this->constructedCustomParameters[$key] = $value;
			$key = $this->getEnvNameOnly($key);
			$forms[$key]->setData($value);
		}
	}
	
	public function mapFormsToData(\Traversable $forms, mixed &$viewData): void
	{
		foreach($forms as $key => $form) {
			$key = $this->getPrefixedEnvName($key);
			if (!isset($this->constructedCustomParameters[$key])) {
				continue;
			}
			$value = $form->getData();
			$viewData['parameters'][$key] = $value;
		}
	}
	
	public function setRequired(
		PropertyAccessorInterface $pa,
		ParameterBagInterface $parameterBag,
	): static {
		$this->pa = $pa;
		$this->parameterBag = $parameterBag;
		return $this;
	}
	
	private function getPrefixedEnvName(string $key): string {
		return (string) u($key)->ensureEnd(')')->ensureStart('env(');
	}
	
	private function getEnvNameOnly(string $key): string {
		return \preg_replace('~env\((.+)\)~', "\$1", $key);
	}
}