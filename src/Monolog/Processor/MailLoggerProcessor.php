<?php

namespace App\Monolog\Processor;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Bundle\SecurityBundle\Security;
use Monolog\Attribute\AsMonologProcessor;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Bridge\Monolog\Processor\DebugProcessor;

#[AutoconfigureTag('monolog.processor', [ 'handler' => 'app.mail.groupped' ])]
//#[AsMonologProcessor(handler: 'app.mail.groupped')]
class MailLoggerProcessor implements ProcessorInterface
{
	public function __construct(
		private readonly Security $security,
		private readonly PropertyAccessorInterface $pa,
	) {}
	
	public function __invoke(LogRecord $record): LogRecord {
		return $record;
	}
}