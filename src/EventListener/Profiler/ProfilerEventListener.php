<?php

namespace App\EventListener\Profiler;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('kernel.event_listener', [ 'priority' => -101 ])]
class ProfilerEventListener
{
	public function __construct(
		#[Autowire('@profiler')] private $profiler,
	) {}
	
    public function __invoke(ResponseEvent $event)
    {
		return;
		$route = $event->getRequest()->attributes->get('_route');
		
		if (\str_starts_with($route, '_')) {
			return;
		}
		
        $response = $event->getResponse();

		$xDebugTokenHeader = $response->headers->get('X-Debug-Token');
		
		\dump(
			$this->profiler->get('app.test')->getTestData(),
		);
    }
}
