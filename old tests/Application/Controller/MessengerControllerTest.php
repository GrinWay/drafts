<?php

namespace App\Tests\Application\Controller;

use function Symfony\Component\String\u;
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
		$client->followRedirects(false);
		$crawler = $client->request('GET', '/messenger');
		
		$callbackEachNodeHasMessanger = static function($node, $i) {
			return $node->nodeName() . ' [class="'.$node->attr('class').'"] text=' . $node->text();
		};
		$callbackEachNodeHasMessangerLink = static function($link, $i) {
			return $link->getUri();
		};
		$callbackReduce = static function($node, $i) {
			return isset(u($node->text())->match('~(?<topic>.*messenger)~i')['topic']);
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
			//?->each($callbackEachNodeHasMessangerLink)
			//?->each($callbackEachNodeHasMessanger)
			//?->text()
		;
		
		$containerText = $crawler->filter('.container')->text(normalizeWhitespace: false);
		$bodyChildrenWithMessangerText = $crawler->filter('html body')->children()
			->reduce($callbackReduce)
			->each($callbackEachNodeHasMessanger)
		;
		$images = $crawler->filter('img');
		$links = $crawler->filter('a');
		$linksCount = $links->count();
		$linksArray = $links->links();
		$allLinksUri = \array_map(static fn($l) => \rtrim($l->getUri(), '/'), $linksArray);
		$textHtml = $crawler->filter('html')->text();
		$titleHtml = $crawler->filter('html title')->text();
		
		$this->assertCount(1, $images);
		$this->assertGreaterThanOrEqual(1, $bodyChildrenWithMessangerText);
		$this->assertContains('https://127.0.0.1/login', $allLinksUri);
		$this->assertGreaterThanOrEqual(5, $linksCount);
		$this->assertStringContainsStringIgnoringCase('messenger', $textHtml);
		$this->assertStringContainsStringIgnoringCase('messenger', $titleHtml);
	}
}