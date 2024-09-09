<?php

namespace App\Command\Telegram;

use GrinWay\Command\Contracts\IO as IODumper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use App\Command\AbstractCommand;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\Service\Attribute\Required;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[AsCommand(
	name: 'telegram:set_webhook',
	hidden: true,
)]
class TelegramSetWebhook extends AbstractCommand
{
	public const HELP = 'Sets Telegram webhook';
	public const DESCRIPTION = self::HELP;	
	
	private HttpClientInterface $client;
	private string $telegramApiToken;
	private string $appHost;
	
	#[Required]
	public function _setRequired(
		HttpClientInterface $client,
		#[Autowire('%env(APP_TELEGRAM_TOKEN)%')]
		string $telegramApiToken,
		#[Autowire('%env(APP_HOST)%')]
		string $appHost,
	): void {
		$this->client = $client;
		$this->telegramApiToken = $telegramApiToken;
		$this->appHost = $appHost;
	}
		
    protected function command(
        InputInterface $input,
        OutputInterface $output,
    ): int {
		
		$webhookUri = \sprintf('https://%s/telegram/webhook', $this->appHost);
		$telegramBotApiUriSetWebhook = \sprintf(
			'https://api.telegram.org/bot%s/setWebhook?url=%s',
			$this->telegramApiToken,
			$webhookUri,
		);
		
		$response = $this->client->request('GET', $telegramBotApiUriSetWebhook, [
			'timeout' => 30,
		]);
		
		$responseStatusCode = $response->getStatusCode();
		
		$message = \sprintf(
			'Telegram WebHook was set with uri: "%s"|Response status code: [%s]', 
			$webhookUri,
			$responseStatusCode,
		);			

		if (\str_starts_with($responseStatusCode, '2')) {
			$ioStrategy = new IODumper\FormattedIODumper('<bg=black;fg=green>%s</>');
		} else {
			$ioStrategy = new IODumper\CautionIODumper();
		}
		
		$this->ioDump(
			\explode('|', $message),
			$ioStrategy,
		);
		
		return Command::SUCCESS;
	}
}