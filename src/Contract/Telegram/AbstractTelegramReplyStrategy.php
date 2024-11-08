<?php

namespace App\Contract\Telegram;

use Symfony\Component\Notifier\Bridge\Telegram\TelegramOptions;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractTelegramReplyStrategy implements TelegramReplyStrategyInterface
{
	protected ?array $response = null;
	
	public function __construct(
		protected readonly Request $request,
		protected readonly PropertyAccessorInterface $pa,
	) {}
	
	/**
     * @interface TelegramReplyStrategyInterface
     */
	public function supports(): bool {
		$this->setResponse();
		
		return null !== $this->response;
	}
	
	/**
     * @interface TelegramReplyStrategyInterface
     */
	public function getSubject(): ?string {
		if (false === $this->supports()) {
			return null;
		}
		
		return $this->doGetSubject();
	}
	
	/**
     * @interface TelegramReplyStrategyInterface
     */
	public function getOptions(): ?TelegramOptions {
		if (false === $this->supports()) {
			return null;
		}
		
		return $this->doGetOptions();
	}
	
	protected function setResponse(): void {
		$this->response = $this->pa->getValue(
			$this->request->getPayload()->all(),
			$this->getRootResponsePropertyAccessArrayKey(),
		);
	}
	
	/**
     * @class AbstractTelegramReplyStrategy
     * 
     * Abstract method
     */
	abstract protected function getRootResponsePropertyAccessArrayKey(): string;
	
	/**
     * @class AbstractTelegramReplyStrategy
     * 
     * Abstract method
     */
	abstract protected function doGetSubject(): ?string;
	
	/**
     * @class AbstractTelegramReplyStrategy
     * 
     * Abstract method
     */
	abstract protected function doGetOptions(): ?TelegramOptions;
}