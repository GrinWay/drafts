<?php

namespace App\Telegram\ReplyStrategy;

use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\ReplyKeyboardMarkup;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\Button\InlineKeyboardButton;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\ReplyKeyboardRemove;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\InlineKeyboardMarkup;
use Symfony\Component\Notifier\Bridge\Telegram\TelegramOptions;
use App\Contract\Telegram\AbstractTelegramReplyStrategy;

class InlineKeyboardButtonReplyStrategy extends AbstractTelegramReplyStrategy
{
	protected function getRootResponsePropertyAccessArrayKey(): string {
		return '[message]';
	}
	
	protected function doGetSubject(): ?string {
		$text = $this->pa->getValue($this->response, '[text]');
		
		return \sprintf(<<<'__SUBJECT__'
__Приветствую тебя на начальном этапе работы с ботом__
*Ответ на: "%s"*
__SUBJECT__, $text);
	}
	
	protected function doGetOptions(): ?TelegramOptions {
		
		$markup = (new InlineKeyboardMarkup())
			->inlineKeyboard([
				(new InlineKeyboardButton('Ячейка 1'))->callbackData('1'),
				(new InlineKeyboardButton('Ячейка 2'))->callbackData('2'),
				(new InlineKeyboardButton('Ячейка 3'))->callbackData('3'),
			])
		;
		
		$options = (new TelegramOptions())
			->chatId(
				$this->pa->getValue($this->response, '[chat][id]')
			)
			->parseMode('markdownv2')
			->disableWebPagePreview(true)
			->protectContent(false)
			->disableNotification(true)
			
			->replyMarkup($markup)
		;
		
		return $options;
	}
}