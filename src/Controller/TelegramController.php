<?php

namespace App\Controller;

use function Symfony\component\string\u;

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

#[Route('/telegram', name: 'app_telegram_')]
class TelegramController extends AbstractController
{
    #[Route('/webhook', name: 'webhook', methods: ['POST'])]
    public function webhook(
        Request $request,
        string $appUrl,
        ?ChatterInterface $chatter,
        PropertyAccessorInterface $pa,
        $projectDir,
    ) {
        /*
        // Must be

        $telegramService
            !tagged_locator
                AbstractTelegramAnswerStrategy[]
                    concrete decision

                        public function supportsPayload($payload) optional parameter 'channel_post'
                        public function supportsText($text)
                        public function supportsData($data)

                        public function getWriteToKey: string
                            from
                            chat
                            ...

                        public function getAnswer: ?ChatMessage

        $answer = $telegramService->getAnswer(
            $payload,
        );

        if (null !== $answer) {
            $chatter?->send($answer);
        }

        return $this->json([]);

        */

        $payload = $request->getPayload()->all();

        \dump($payload);

        $subject = null;
        $options = null;

        if (null !== $response = $pa->getValue($payload, '[callback_query]')) {
            $data = $pa->getValue($response, '[data]');

            $subject = \sprintf(<<<'__SUBJECT__'
			__Ответ на нажатие callback query (inline button)__
			Выбрано: "%s"
			__SUBJECT__, $data);

            $options = (new TelegramOptions())
                // override chat id
                ->chatId(
                    $pa->getValue($response, '[message][chat][id]')
                )

                ->answerCallbackQuery(
                    callbackQueryId: $pa->getValue($response, '[id]'),
                    showAlert: true,
                    cacheTime: 1,
                )

                ->parseMode('markdownv2')
                //->parseMode(TelegramOptions::PARSE_MODE_MARKDOWN_V2)
                //->disableWebPagePreview(true)
                //->protectContent(false)
                //->disableNotification(true)
            ;
        } elseif (null !== $response = $pa->getValue($payload, '[message]')) {
            $text = $pa->getValue($response, '[text]');

            $subject = \sprintf(<<<'__SUBJECT__'
			__Приветствую тебя на начальном этапе работы с ботом__
			*Ответ на: "%s"*
			__SUBJECT__, $text);

            $replyKeyboardRemove = new ReplyKeyboardRemove(
                removeKeyboard: true,
                selective: false,
            );

            $inlineKeyboardMarkup = (new InlineKeyboardMarkup())
                ->inlineKeyboard([
                    (new InlineKeyboardButton('Ячейка 1'))->callbackData('1'),
                    (new InlineKeyboardButton('Ячейка 2'))->callbackData('2'),
                    (new InlineKeyboardButton('Ячейка 3'))->callbackData('3'),
                ])
            ;

            $replyKeyboardMarkup = (new ReplyKeyboardMarkup())
                ->keyboard([
                    new KeyboardButton('Ячейка 1'),
                    (new KeyboardButton('Контакт'))
                        ->requestContact(true)
                    ,
                    (new KeyboardButton('Моя локация'))
                        ->requestLocation(true)
                    ,
                ])
                ->resizeKeyboard(true)
            ;

            $markup = null;
            if (\preg_match('~^/удали\s*меню$~iu', $text)) {
                $markup = $replyKeyboardRemove;
            } elseif (\preg_match('~^/встроенное\s*меню$~iu', $text)) {
                $markup = $inlineKeyboardMarkup;
            } elseif (\preg_match('~^/меню$~iu', $text)) {
                $markup = $replyKeyboardMarkup;
            }

            $editMessageId = null;
            if (\preg_match('~^\/change\s*?(?<message_id>[0-9]+)\s*with\s*(?<new_content>.+)\s*$~iu', $text, $modifyStore)) {
                if (isset($modifyStore['message_id']) && isset($modifyStore['new_content'])) {
                    $editMessageId = $modifyStore['message_id'];
                    $subject = $modifyStore['new_content'];
                }
            }

            $options = (new TelegramOptions())
                ->chatId(
                    $pa->getValue($response, '[chat][id]')
                )
                /*
                ->replyTo(
                    $pa->getValue($response, '[message_id]')
                )
                */

                ->parseMode('markdownv2')
                ->disableWebPagePreview(true)
                ->protectContent(false)
                ->disableNotification(true)

                /*
                ->hasSpoiler(true)
                ->photo('AgACAgIAAxkBAAIBQGbdA44KuIrrpn4Kif4dOWX3EDI2AAJS4TEbtZDoSno7Iu5lBD2hAQADAgADbQADNgQ')
                */
            ;

            if (null !== $markup) {
                $options->replyMarkup($markup);
            }

            if (null !== $editMessageId) {
                $options->edit($editMessageId);
            }
        } elseif (null !== $response = $pa->getValue($payload, '[edited_message]')) {
            $text = $pa->getValue($response, '[text]');
            $subject = \sprintf(
                'Сообщение было отредактировано на: "%s"',
                $text,
            );
        } else {
            return $this->json($payload);
        }

        if (null !== $subject) {
            $message = (new ChatMessage($subject))
                ->transport('telegram')
            ;

            if (null !== $options) {
                $message->options($options);
            }

            try {
                $chatter?->send($message);
            } catch (\Exception $e) {
                \dump('Exception happened');
            }
        }

        return $this->json([]);
    }
}
