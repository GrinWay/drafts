<?php

namespace App\Telegram\ReplyStrategy;

use Symfony\Component\Notifier\Bridge\Telegram\TelegramOptions;
use App\Contract\Telegram\AbstractTelegramReplyStrategy;

class CallbackQueryReplyStrategy extends AbstractTelegramReplyStrategy
{	
	protected function getRootResponsePropertyAccessArrayKey(): string {
		return '[callback_query]';
	}
	
	protected function doGetSubject(): ?string {
		$data = $this->pa->getValue($this->response, '[data]');
		
		return \sprintf(<<<'__SUBJECT__'
Ответ на нажатие callback query inline button
Выбрано: "%s"
__SUBJECT__, $data);
	}
	
	protected function doGetOptions(): ?TelegramOptions {
		$chatId = $this->pa->getValue($this->response, '[message][chat][id]');
		$callbackQueryId = $this->pa->getValue($this->response, '[id]');
		
		$telegramOptions = (new TelegramOptions())
			->parseMode('markdownv2')
			->disableWebPagePreview(true)
			->disableNotification(true)
			->protectContent(false)
		;
		
		if (null !== $callbackQueryId) {
			$telegramOptions->answerCallbackQuery(
				callbackQueryId: $callbackQueryId,
				showAlert: true,
				cacheTime: 1,
			);
		}
		
		if (null !== $chatId) {
			$telegramOptions->chatId($chatId);
		}
		
		return $telegramOptions;
	}
}