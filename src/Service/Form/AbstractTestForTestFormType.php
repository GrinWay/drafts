<?php

namespace App\Service\Form;

use App\Repository\TestForTestFormTypeRepository;
use App\Service\StringService;

class AbstractTestForTestFormType
{
	function __construct(
		public mixed $name = null,
	) {
	}
}
