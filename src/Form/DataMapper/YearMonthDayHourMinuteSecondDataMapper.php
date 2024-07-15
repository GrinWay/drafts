<?php

namespace App\Form\DataMapper;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\DataMapperInterface;
use App\Service\CarbonService;

class YearMonthDayHourMinuteSecondDataMapper implements DataMapperInterface {
	private string $carbonClass;
	
	public function mapDataToForms(mixed $viewData, \Traversable $forms): void
	{
		\dump('(map) to form');
		if (null === $viewData) {
			return;
		}
		if (!$viewData instanceof \DateTimeInterface) {
			throw new UnexpectedTypeException($viewData, \DateTimeInterface::class);
		}
		//\dd($viewData);
		if (!$viewData instanceof Carbon || !$viewData instanceof CarbonImmutable) {
			$viewData = new CarbonImmutable($viewData);
		}
		
		foreach($forms as $form) {
			$property = $form->getName();
			$data = $viewData->$property;
			$form->setData($data);
		}
	}
	
	public function setCarbonClass(string $carbonClass): static {
		CarbonService::throwIfNotTypeByString($carbonClass);
		$this->carbonClass = $carbonClass;
		return $this;
	}
	
	public function mapFormsToData(\Traversable $forms, mixed &$viewData): void
	{
		\dump('(map) to object');
		$forms = \iterator_to_array($forms);
		
		$y = $forms['year']->getData();
		$m = $forms['month']->getData();
		$d = $forms['day']->getData();
		$h = $forms['hour']->getData();
		$i = $forms['minute']->getData();
		$s = $forms['second']->getData();
		$dateTime = $y.'-'.$m.'-'.$d.' '.$h.':'.$i.':'.$s;
		
		if (empty($y) || empty($m) || empty($d) || empty($h) || empty($i) || empty($s)) {
			$viewData = null;
			return;
		}
		
		try {
			$viewData = new $this->carbonClass($dateTime);
		} catch (\Exception $e) {
			//###> Cuz constraints will work after data mappers
			$viewData = null;
		}
	}
}