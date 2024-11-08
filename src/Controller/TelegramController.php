<?php

namespace App\Controller;

use function Symfony\component\string\u;

use App\Telegram\ReplyStrategy\InlineKeyboardButtonReplyStrategy;
use App\Telegram\ReplyStrategy\CallbackQueryReplyStrategy;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\Button\InlineKeyboardButton;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\ReplyKeyboardRemove;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\InlineKeyboardMarkup;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\ForceReply;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\ReplyKeyboardMarkup;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\Button\KeyboardButton;
use Symfony\Component\Notifier\Bridge\Telegram\TelegramOptions;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\TelegramService;

#[Route('/telegram', name: 'app_telegram_')]
class TelegramController extends AbstractController
{
    #[Route('/webhook', name: 'webhook', methods: ['POST'])]
    public function webhook(
        TelegramService $telegramService,
        Request $request,
        PropertyAccessorInterface $pa,
        ?ChatterInterface $chatter,
    ) {
		$response = $this->json([]);
		
		if (null === $chatter) {
			return $response;
		}
		
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
		
		if (null !== $chatMessage) {
			$chatter->send($chatMessage);			
		}
		
		return $response;
    }
}
