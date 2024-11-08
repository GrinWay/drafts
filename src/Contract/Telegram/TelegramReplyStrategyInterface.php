<?php

namespace App\Contract\Telegram;

use Symfony\Component\Notifier\Bridge\Telegram\TelegramOptions;

interface TelegramReplyStrategyInterface
{
	/**
     * @interface TelegramReplyStrategyInterface
     */
	public function supports(): bool;
	
	/**
     * @interface TelegramReplyStrategyInterface
     */
	public function getSubject(): ?string;
	
	/**
     * @interface TelegramReplyStrategyInterface
     */
	public function getOptions(): ?TelegramOptions;
}