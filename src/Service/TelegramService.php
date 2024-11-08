<?php

namespace App\Service;

use Symfony\Component\Notifier\Bridge\Telegram\TelegramOptions;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\HttpFoundation\Request;
use App\Contract\Telegram\TelegramReplyStrategyInterface;

/*
$chatMessage = $telegramService->getChatMessage(
	$request,
	new CallbackQueryReplyStrategy(
		$request,
		$pa,
	),
	new InlineKeyboardButtonReplyStrategy(
		$request,
		$pa,
	),
);
*/
class TelegramService
{
	public function __construct(
		private readonly PropertyAccessorInterface $pa,
	) {}
	
	/**
     * API
     */
	public function getChatMessage(Request $request, TelegramReplyStrategyInterface...$telegramReplyStrategies): ?ChatMessage {
		$chatMessage = null;
		
		$subject = null;
		$options = null;
		
		foreach($telegramReplyStrategies as $telegramReplyStrategy) {
			if (true === $telegramReplyStrategy->supports()) {
				$subject = $telegramReplyStrategy->getSubject();
				$options = $telegramReplyStrategy->getOptions();			
				break;
			}
		}
		
		if (null !== $subject) {
			$chatMessage = (new ChatMessage($subject))
				->transport('telegram')
			;

			if (null !== $options) {
                $chatMessage->options($options);
            }
		}
		
		return $chatMessage;
	}
}