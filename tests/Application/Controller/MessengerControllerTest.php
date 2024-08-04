<?php

namespace App\Tests\Application\Controller;

use App\Tests\Application\AbstractApplicationCase;

class MessengerControllerTest extends AbstractApplicationCase {
	public function testGotCrawler(): void {
		$client = self::createClient(
			server: [
				'HTTPS' => true,
				'HTTP_HOST' => '127.0.0.1',
			],
		);
		
		//$client->enableProfiler();
		$client->followRedirects(true);
		$crawler = $client->request('GET', '/messenger');
		
		$callbackEach = static function($node, $i) {
			return $node->nodeName() . ' [class="'.$node->attr('class').'"] text=' . $node->text();
		};
		$callbackEachLink = static function($link, $i) {
			return $link->getUri();
		};
		$callbackReduce = static function($node, $i) {
			return $node->text();
		};
		$result = $crawler->filter('html div')
			//->filter('html .container')
			//->eq(0)
			//->slice(-2, 2)
			//->reduce($callbackReduce)
			//->last()
			->getIterator()
			//->count()
			
			//->filter('form')
			//->first()
			//->form()
			
			//->filter('img')
			//->images()
			
			//->filter('a')
			//->links()
			
			//->selectButton('Button')
			//->attr('type')
			
			//->selectImage('Symfony logo')
			//->image()
				//->getUri()
				
			//->selectLink('PRETEND')
			//->link()
				//->getMethod()
				//->getUri()
			
			//->extract(['class'])
			//->attr('class')
			
			//->previousAll()
			//->attr('class')
			//->nodeName()
			//->evaluate('')
			// including the current element
			//->outerHtml()
			
			//->html()
			//->matches('.example-wrapper')
			//?->each($callbackEachLink)
			//?->each($callbackEach)
			//?->text()
		;
		
		$images = $crawler->filter('img');
		$links = $crawler->filter('a');
		$linksCount = $links->count();
		$linksArray = $links->links();
		$allLinksUri = \array_map(static fn($l) => \rtrim($l->getUri(), '/'), $links->links());
		$textHtml = $crawler->filter('html')->text();
		$titleHtml = $crawler->filter('html title')->text();
		
		//TODO: current 
		//clear && bin/phpunit tests/Application/Controller/MessengerControllerTest.php
		$this->assertCount(1, $images);
		$this->assertContains('https://127.0.0.1/login', $allLinksUri);
		$this->assertGreaterThanOrEqual(5, $linksCount);
		$this->assertStringContainsStringIgnoringCase('messenger', $textHtml);
		$this->assertStringContainsStringIgnoringCase('messenger', $titleHtml);
	}
}