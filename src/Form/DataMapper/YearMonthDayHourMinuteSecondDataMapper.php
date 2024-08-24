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
		if (!$viewData instanceof Carbon && !$viewData instanceof CarbonImmutable) {
		//\dd($viewData);
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
		
		$y = $forms['year']->getData()   ?: '0000';
		$m = $forms['month']->getData()  ?: '00';
		$d = $forms['day']->getData()    ?: '00';
		$h = $forms['hour']->getData()   ?: '00';
		$i = $forms['minute']->getData() ?: '00';
		$s = $forms['second']->getData() ?: '00';
		$dateTime = $y.'-'.$m.'-'.$d.' '.$h.':'.$i.':'.$s;
		
		if (empty($y) || empty($m) || empty($d) || empty($h) || empty($i) || empty($s)) {
			$viewData = null;
			return;
		}
		
		try {
			if ($viewData instanceof Carbon) {
				$viewData = $viewData
					->setYear($y)
					->setMonth($m)
					->setDay($d)
					->setHour($h)
					->setMinute($i)
					->setSecond($s)
				;
			} else {
				$viewData = new $this->carbonClass($dateTime);				
			}
		} catch (\Exception $e) {
			//###> Cuz constraints will work after data mappers
			$viewData = null;
		}
	}
}