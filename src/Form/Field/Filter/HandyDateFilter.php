<?php

namespace App\Form\Field\Filter;

use Carbon\Carbon;
use App\Form\Type\HandyDateFilterFormType;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDataDto;
use EasyCorp\Bundle\EasyAdminBundle\Filter\FilterTrait;

class HandyDateFilter implements FilterInterface
{
	use FilterTrait;

    public static function new(string $propertyName, $label = null): self
    {
        return (new self())
            ->setFilterFqcn(__CLASS__)
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(HandyDateFilterFormType::class);
    }

    public function apply(QueryBuilder $qb, FilterDataDto $filterDataDto, ?FieldDto $fieldDto, EntityDto $entityDto): void
    {
		$e = $filterDataDto->getEntityAlias();
		$p = $filterDataDto->getProperty();
		$n = '_parameterName';
		$v = $filterDataDto->getValue();
		
		
		if ('today_date' === $v) {
			$f = '%Y-%m-%d';
			$v = Carbon::now('UTC')->format('Y-m-d');
		} elseif ('today_date_time' === $v) {
			$f = '%Y-%m-%d %H:%i';
			$v = Carbon::now('UTC')->format('Y-m-d H:i');
		} else {
			return;
		}
		
		$andWhere = 'DATE_FORMAT('.$e.'.'.$p.', \''.$f.'\') = :'.$n;
		
        $qb
			->andWhere($andWhere)
			->setParameter($n, $v)
		;
    }
}