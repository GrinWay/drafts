<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Controller\TemplateController;
use Symfony\Component\HttpKernel\Controller\ControllerReference;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Fragment\FragmentUriGeneratorInterface;
use Symfony\Component\DomCrawler\Crawler;

//TODO: TestUtil
class TestUtil
{
	//###> API ###
	
	/**
	* Usage:
	* 
	*  $return = $crawler->filter('PARENT CSS FILTER')->children()->each(
	*     TestUtil::extract()
	* );
	*  $return = $crawler->filter('PARENT CSS FILTER')->children()->each(
	*     TestUtil::extract(true)
	* );
	*  $return = $crawler->filter('CSS FILTER')->each(
	*     TestUtil::extract(false, ['_name', '_text'])
	* ); // same as below
	*  $return = $crawler->filter('CSS FILTER')->each(
	*     TestUtil::extract(false, ['_name'], '_text')
	* ); // same as above
	* 
	* This function is particularly for Crawler::each method
	* 
	* @return callable that return array (attributes of Crawler + _name returns Crawler::nodeName)
	*/
	public static function extract(
		bool $isIncludeDefault = true,
		array|string $attributesAsArray = [],
		string...$attributes,
	): callable {
		$default = [];
		
		if (\is_string($attributesAsArray)) {
			$attributesAsArray = [$attributesAsArray];
		}
		
		if (true === $isIncludeDefault) {
			$default = [
				'_name',
				'class',
				'_text',
			];
		}
		
		$attributes = \array_merge($default, $attributesAsArray, $attributes);
		
		return static function(
			Crawler $node,
			int $i,
		) use($attributes): array {
			$result = [];
			foreach($attributes as $attribute) {
				$results = $node->extract([$attribute]);
				$result[$attribute] = \array_shift($results);
			}
			return $result;
		};
	}
	
	//###< API ###
}
