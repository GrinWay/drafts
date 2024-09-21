<?php

namespace App\Controller;

use function Symfony\Component\Translation\t;
use function Symfony\component\string\u;
use function Symfony\component\string\b;
use function Symfony\Component\Clock\now;

use Symfony\Component\Serializer\Attribute\SerializedPath;
use Symfony\Component\Serializer\Annotation\Context;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\DiscriminatorMap;
use Symfony\Component\Serializer\Mapping\ClassDiscriminatorFromClassMetadata;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Attribute\MaxDepth;
use Symfony\Component\Serializer\Exception\PartialDenormalizationException;
use Symfony\Component\Serializer\Context\Encoder\CsvEncoderContextBuilder;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Normalizer\TranslatableNormalizer;
use Symfony\Component\Serializer\Normalizer\UidNormalizer;
use Symfony\Component\Serializer\Normalizer\CustomNormalizer;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\ProblemNormalizer;
use Symfony\Component\Serializer\Normalizer\ConstraintViolationListNormalizer;
use Symfony\Component\Serializer\Normalizer\FormErrorNormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DataUriNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeZoneNormalizer;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use App\Serializer\NameConverter\NormailzedPrefixedWithNameConverter;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Notifier\Bridge\OneSignal\OneSignalOptions;
use Symfony\Component\Notifier\Bridge\Expo\ExpoOptions;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage as SymfonyExpressionLanguage;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Security\Voter\HomeVoter;
use Novu\SDK\Novu;
use Symfony\Component\Notifier\Bridge\Novu\NovuSubscriberRecipient;
use App\Notification\PushNotification\NovuNotification;
use Symfony\Component\Security\Http\LoginLink\LoginLinkNotification;
use App\Notification\EmailNotification\TemplatedEmailNotification;
use App\Notification\ChatNotification\SubjectPlusContentNotification;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\Notifier\Recipient\NoRecipient;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\Button\InlineKeyboardButton;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\ReplyKeyboardRemove;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\InlineKeyboardMarkup;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\ForceReply;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\ReplyKeyboardMarkup;
use Symfony\Component\Notifier\Bridge\Telegram\Reply\Markup\Button\KeyboardButton;
use Symfony\Component\Notifier\Bridge\Telegram\TelegramOptions;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Message\PushMessage;
use Symfony\Component\Notifier\Message\ChatMessage;
use Symfony\Component\Notifier\Bridge\Twilio\TwilioOptions;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\Cache\Adapter\DoctrineDbalAdapter;
use Symfony\Component\Cache\Adapter\PdoAdapter;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\Adapter\FilesystemTagAwareAdapter;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Lock\Store\DoctrineDbalStore;
use Symfony\Component\Lock\Key;
use Symfony\Component\Lock\LockFactory;
use Symfony\Component\Lock\Store\SemaphoreStore;
use Symfony\Component\Lock\Store\FlockStore;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Endroid\QrCode\Builder\BuilderInterface;
use App\OTP\TOTPStrategy;
use App\OTP\HOTPStrategy;
use Symfony\Component\Mime\MimeTypes;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font as QRCodeFont;
use App\QrCode\Label\Font as AppQRCodeFont;
use App\Carbon\ClockImmutable;
use OTPHP\TOTP;
use OTPHP\HOTP;
use Symfony\Component\Mime\BodyRendererInterface;
use Symfony\Component\Mime\Crypto\SMimeEncrypter;
use Symfony\Component\Mime\Crypto\DkimSigner;
use Symfony\Component\Mime\Crypto\SMimeSigner;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Part\File as MimeFile;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Address;
use App\Form\Type as AppFormType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Transport\Smtp\Auth\XOAuth2Authenticator;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\HttpClient\AmpHttpClient;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpClient\NativeHttpClient;
use Symfony\Component\HttpClient\NoPrivateNetworkHttpClient;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\HttpClient\HttpOptions;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\HttpClient\CachingHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\HttpCache\Store;
use Symfony\Component\Config\ConfigCache;
use App\Client\BrowserKit\BrowserClient;
use Symfony\Component\Config\Definition\Processor;
use App\Config\Configuration\FrameworkConfigurator;
use App\Config\Loader\YamlLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\OptionsResolver\Debug\OptionsResolverIntrospector;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;
use App\Repository\TaskFoodTopicRepository;
use App\Form\Type\YearMonthDayHourMinuteSecondType;
use Symfony\Component\CssSelector\CssSelectorConverter;
use App\Client\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Psr\Container\ContainerInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Carbon\CarbonImmutable;
use App\Entity\Media\Image;
use App\Service;
use Scheb\TwoFactorBundle\Security\TwoFactor\Trusted\TrustedDeviceTokenStorage;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Email\Generator\CodeGeneratorInterface;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\PdfWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Endroid\QrCode\Writer\WebPWriter;
use Endroid\QrCode\Writer\ValidationException;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Totp\TotpAuthenticatorInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\HttpFoundation\UriSigner;
use App\Messenger\Command\Message\SyncRunProcessMessage;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\Process\Messenger\RunProcessMessage;
use Symfony\Component\Security\Core\Signature\Exception\ExpiredSignatureException;
use Symfony\Component\Security\Core\Signature\Exception\InvalidSignatureException;
use App\Security\Authenticator\LoginLink\LoginLinkSignatureHasher;
use App\Form\Type\LoginLinkFormType;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;
use App\Exception\Security\AccessDenied\RoleNotGrantedAccessDeniedException;
use App\Exception\Security\Authentication\FormLoginNeedsException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use App\Exception\Security\Authentication\OAuthNeedsException;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Type\Note\NoteType;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Translation\MessageCatalogueInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Component\Translation\TranslatableMessage;
use Carbon\CarbonInterval;
use Symfony\Component\Clock\Clock;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\String\Slugger\SluggerInterface;
//use Symfony\Component\Emoji\EmojiTransliterator;
use Symfony\Component\String\CodePointString;
use Symfony\Component\String\ByteString;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use App\Validation\Compound\AllTypes;
use Symfony\Component\Finder\SplFileInfo;
use App\Constraint\User\PasswordConstraint;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Validation;
use App\Service\Form\TestForTestFormType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Service\Form\FormEventHelper;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use App\Contract\Form\PreventModifyingPropsOfEntity;
use App\EventSubscriber\Form\ForbidModifyPropEventSubscriber;
use App\Form\Type\TaskFormType;
use App\Form\Type\TestFormType;
use App\Form\Type\ImageFormType;
use App\Service\Product\ProductManaget;
use Symfony\Component\Uid\Uuid;
use App\Form\Type\TestLargeFormType;
use App\Form\Type\ProductFormType;
use App\Form\Type\UserType;
use App\Form\Type\StyledChoiceType;
use Symfony\Component\Security\Csrf\CsrfToken;
use App\Attribute\Twig\MyTemplate;
use App\Attribute\SomeAttribute;
use App\Attribute\NewClosureDefinitionWithTag;
use App\Messenger\Event\Message\Shop\PriceWasDecreased;
use App\Contract\Some\SomeServiceInterface;
use App\Service\SomeService2;
use Symfony\Component\DependencyInjection\Attribute\AutowireServiceClosure;
use Symfony\Component\DependencyInjection\Attribute\AutowireMethodOf;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Config\FileLocator;
use App\Type\Product\ProductType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Doctrine\Common\Collections\Criteria;
use App\Repository\TaskRepository;
use App\Repository\MediaRepository;
use App\Repository\UserRepository;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use App\Messenger\Test\TestMessage;
use App\Messenger\Test\TestMessageHandler;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Service\SomeService;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Service\ExpressionLanguage;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\AcceptHeader;
use Symfony\Component\HttpFoundation\HeaderUtils;
use App\Exception\E404;
use Symfony\Component\WebLink\Link;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use App\Dto\User\NullUserDto;
use App\Doctrine\DTO\UserDto;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Type\Shop\ShopType;
use App\Type\User\UserPlatformType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Requirement\EnumRequirement;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Attribute\MapDateTime;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\DefaultValueResolver;
use App\ValueResolver\CarbonValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\BackedEnumValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestPayloadValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\DateTimeValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\ServiceValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\UidValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\VariadicValueResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestAttributeValueResolver;
use App\Service\DoctrineService;
use App\Service\StringService;
use App\Attribute\TakePayload;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RequestMatcher\MethodRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\AttributesRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\ExpressionRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\HeaderRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\HostRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\IpsRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\QueryParameterRequestMatcher;
use Symfony\Component\HttpFoundation\StreamedJsonResponse;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\HttpKernel\Attribute\Cache;
use App\Service\ConfigService;
use App\Service\FilesystemService;
use App\Service\CarbonService;
use App\Entity\Product\FoodProduct;
use App\Entity\Product\FurnitureProduct;
use App\Entity\Product\ToyProduct;
use App\Entity\Product\Product;
use App\Entity\MappedSuperclass\Passport;
use App\Entity\UserPassport;
use App\Entity\ProductPassport;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;
use Symfony\Component\Serializer\Context\Encoder\JsonEncoderContextBuilder;
use App\Messenger\Notifier\SendEmail;
use App\Messenger\Notifier\ToAdminSendEmail;
use App\Messenger\Notifier\NotifierHandlers;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Messenger\Exception\StopWorkerException;
use App\Messenger\Test\Query\ListUsers;
use Symfony\Component\Messenger\Handler\Acknowledger;
use Symfony\Component\Messenger\Stamp\ValidationStamp;
use App\Messenger\Test\Event\UserWasCreated;
use Symfony\Component\Console\Messenger\RunCommandMessage;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Trigger\CronExpressionTrigger;
use App\Messenger\Scheduler\Trigger\MondayOnlyTrigger;
use Symfony\Bridge\Doctrine\PropertyInfo\DoctrineExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;
use Symfony\Component\PropertyInfo\Extractor\PhpStanExtractor;
use Symfony\Component\PropertyInfo\Extractor\SerializerExtractor;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AttributeLoader;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\Attribute\AutowireCallable;
use Symfony\Component\Dotenv\Dotenv;
use App\Service\Anonymous\SomeAnonymousService;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\ExpressionLanguage\Parser;
use Symfony\Component\ExpressionLanguage\SerializedParsedExpression;
use App\Response\HtmlResponse;
use Symfony\Bridge\Twig\Attribute\Template;
use App\Entity\Task;
use Symfony\Component\Form\Extension\Core\Type as FormType;
use App\Messenger\Query\Message\Task\TaskForm;
use Symfony\Component\Security\Http\Attribute\IsCsrfTokenValid;
use App\Trait\Security\GitHub\GitHubAccessTokenAware;

class HomeController extends AbstractController
{
	use GitHubAccessTokenAware;
	
    public function __construct(
        #[\App\Attribute\AutowireMyMethodOf(\App\Service\SomeService::class)]
        protected \Closure $getGenerator,
    ) {
    }

	/**
	* @var Carbon $nowModified -1second
	*/
    #[Route(path: '/{passedOtpNumber<\d+>?1}', methods: ['GET', 'POST'])]
    //#[IsCsrfTokenValid(id: 'default', tokenKey: '_token')]
    //#[SomeAttribute]
    public function home(
        int $passedOtpNumber,
        Request $r,
        Request $request,
        RequestStack $requestStack,
        ParameterBagInterface $parameters,
        $t,
        $projectDir,
        UrlHelper $url,
        EntityManagerInterface $em,
        $faker,
        $adminSendEmailMessage,
        $adminEmail,
        //Product $product,
        MessageBusInterface $messengerBusHandlerHasRouterContext,
        MessageBusInterface $bus,
        $get,
        PropertyInfoExtractorInterface $pi,
        #[Autowire('%app.app%')]
        $appApp,
        #[Autowire(service: 'service_container')]
        $container,
        StringService $stringService,
        //#[Autowire(expression: 'service("SERVICE_ID not ALIAS").getPath(".", "/s/f/g")')]
        //#[AutowireServiceClosure(SomeService::class)]
        //#[AutowireCallable(service: SomeService::class, method: 'getGenerator', lazy: true)]
        //#[AutowireMethodOf(SomeService::class, lazy: true)]
        //$getGenerator = null,
        \App\Dto\User\UserDto $userDto,
        \App\Email\Style\DefaultEmail $defaultEmail,
        \App\Email\Style\CongratulationEmail $configurationEmail,
        \App\Email\Style\ErrorEmail $errorEmail,
        \App\Email\Style\NewUserEmail $newUserEmail,
        //SomeService $test,
        //SomeService2 $test2,
        $promocodes,
        #[Autowire('@decorated_attribute_router_reader')]
        \App\Service\Router\AttributeRouterReader $attributeRouterReader,
        \App\Service\SomeService $soso,
        //#[Autowire('@FQCN $var')]
        $callableHashLocator,
        \Psr\EventDispatcher\EventDispatcherInterface $dispatcher,
        ?Carbon $now,
        $ru12Carbon,
        //$someValue,
        #[Autowire('@twig')]
        $twig,
        CsrfTokenManagerInterface $csrfTokenManager,
		?TokenInterface $token,
		LoginLinkHandlerInterface $loginLinkHandler,
		PropertyAccessorInterface $pa,
		#[Autowire('@security.authenticator.login_link_signature_hasher.main')]
		$linkHasher,
		Carbon $nowModified,
		#[Autowire('@security.authenticator.login_link_handler.main')]
		$loginLink,
		ClientRegistry $clientRegistry,
		UriSigner $uriSigner,
		UrlGeneratorInterface $ug,
		#[Autowire('@form.server_params')]
		$formServerParams,
		?User $user,
		?TotpAuthenticatorInterface $totpAuthenticator,
		?CodeGeneratorInterface $emailCodeGenerator,
		#[Autowire('@scheb_two_factor.trusted_token_storage')]
		?TrustedDeviceTokenStorage $trustedTokenStorage,
		// just don't remove it from the container
		//?Service\ServiceForTesting $serviceForTesting,
		//#[Autowire('@scheb_two_factor.trusted_token_storage')]
		$absCacheDir,
		$debug,
		#[Autowire('@validator')]
		$validator,
		#[Autowire('%env(APP_HOST)%')]
		$host,
		#[Autowire('%env(APP_REQUIRED_SCHEME)%')]
		$requiredScheme,
		HttpClientInterface $client,
		//TransportInterface $mailer,
		#[Autowire('%env(APP_ADMIN_MAILER_LOGIN)%')]
		$appAdminEmailLogin,
		#[Autowire('%env(APP_ADMIN_EMAIL)%')]
		$fromEmail,
		#[Autowire('%env(APP_TO_TEST_EMAIL)%')]
		$toEmail,
		#[Autowire('%app.abs_img_dir%')]
		$absImgDir,
		#[Autowire('%app.abs_qr_dir%')]
		$absQrDir,
		Service\TwigUtil $twigUtil,
		\App\Repository\UserRepository $userRepo,
		BodyRendererInterface $bodyRenderer,
		SessionInterface $session,
		#[Autowire('%env(APP_MAILER_HEADER_FROM)%')]
		$mailerHeaderFrom,
		#[Autowire('%env(APP_TO_TEST_EMAIL)%')]
		$testEmail,
		BuilderInterface $pngQrCodeBuilder,
		BuilderInterface $svgQrCodeBuilder,
		RateLimiterFactory $shortSecondsLimiter,
		LockFactory $doctrineLockFactory,
		$enUtcCarbon,
		#[Autowire('%kernel.cache_dir%')]
		$cacheDir,
		Service\CacheUtil $cacheUtil,
		CacheInterface $appCacheMinute,
		#[Autowire('@app.cache.async')]
		$cache,
		Service\ExpressionLanguage $expr,
		RateLimiterFactory $tooRareLimiter,
		?TexterInterface $texter,
		?ChatterInterface $chatter,
		MailerInterface $mailer,
		$appUrl,
		NotifierInterface $notifier,
		$adminPhone,
		#[Autowire('%env(APP_ONESIGNAL_ID)%')]
		$onesignalAppId,
		#[Autowire('%env(APP_ONESIGNAL_DEFAULT_RECIPIENT_ID)%')]
		$appOnesignalDefaultRecipientId,
		Service\FirebaseService $firebaseService,
		#[Autowire('%env(APP_FIREBASE_FCM_V1_API_KEY)%')]
		$firebaseApiKey,
		/*
		*/
		SerializerInterface $serializer,
	) {
		$response = $this->render('home/index.html.twig');
		
		$object = new MyClass();
		$object->createdAt = Carbon::now('UTC');
		
		$json = '{"createdAt":"2024"}';
		
		\dump(
			/*
			\get_debug_type($serializer),
			*/
			$serializer->serialize($object, 'json', context: [
				//'preserve_empty_objects' => true,
				DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s', // H:i:s
			]),
			$serializer->deserialize($json, MyClass::class, 'json', context: [
				//'preserve_empty_objects' => true,
				//DateTimeNormalizer::FORMAT_KEY => 'Y-m-d', // H:i:s
			]),
		);
		
		return $response;
		
		\dd('END');
		
		// Rate limiter
		
		$clientIp = $request->getClientIp();
		$limiter = $tooRare->create($clientIp);
		
		$getInterval = static fn($dateInterval) => CarbonInterval::instance($dateInterval)->locale('ru')->forHumans([
			'options' => \Carbon\CarbonInterface::JUST_NOW | \Carbon\CarbonInterface::ONE_DAY_WORDS,
		]);
		
		$response = $this->render('home/index.html.twig', []);
		
		$limit = $limiter->consume();
		
		$dateInterval = (new \DateTime('UTC'))->diff($limit->getRetryAfter());
		
		$reserved = $limiter->reserve(0);
		$timestamp = $reserved->getTimeToAct();
		$duration = $reserved->getWaitDuration();
		$result = $limit->isAccepted();
		
		/*
		\dd(
			$result,
			$limit->getRetryAfter(),
			$ru12Carbon->make(Carbon::createFromTimestampUTC($timestamp))->isoFormat('LLLL'),
			$getInterval((new CarbonInterval())->add($duration, 'seconds')->cascade()),
			$reserved->getRateLimit()->getRemainingTokens(),
		);
		*/
		
		if (true === $result) {
			//...
		}
		
		return $response;
		
		$callback = static function(int $downloadedBytes, int $totalBytes, array $currentDownloadedInfo): void {
			\dump($downloadedBytes, $totalBytes, $currentDownloadedInfo);
		};
		
		$store = new Store($stringService->getPath($projectDir, 'cache/dev/my/'));
		$client = new CurlHttpClient();
		$client = HttpClient::create();
		$client = new CachingHttpClient($client, $store);
		
		$client = $client->withOptions(
			(new HttpOptions())
				//->setOnProgress($callback)
				->setTimeout(200)
				//->setHeader('Content-Disposition', 'attachment; filename="php.zip"')
				->setExtra('curl', [
					
				])
				->toArray()
		);
		
		$responses = [];
		
		for($i = 0; $i < 1; ++$i) {
			$client = $client->withOptions(
				(new HttpOptions())
					->setUserData(\sprintf($requestDescription = 'request_idx_%s', $i))
					->setTimeout(3)
					->setMaxDuration(5)
					->toArray()
			);
			
			$responses[] = $client->request('GET', 'https://github.com', []);
		}
		
		foreach($client->stream($responses) as $response => $chunk) {
			if ($chunk->isTimeout()) {
				\dump(\sprintf(
					'TIMEOUT of "%s"',
					$response->getInfo('user_data'),
				));
				$response->cancel();
			} else if ($chunk->isLast()) {
				\dump(
					\sprintf(
						'Got full response of "%s" with status code: "%s".',
						$response->getInfo('user_data'),
						$response->getStatusCode(),
					)
				);
			} else {
				\dump(\sprintf(
					'Processing of "%s"... status code: "%s"', 
					$response->getInfo('user_data'),
					$response->getStatusCode(),
					)
				);				
			}
		}
		
		$result = \get_debug_type($response);
		\dd(
			$result,
			/*
			$response->getStatusCode(),
			$response->getInfo('user_data'),
			$response->getContent(),
			$response->getInfo(),
			$response->getContent(),
			$response->getHeaders(),
			$response->toArray(),
			$formDataPart->getPreparedHeaders()->toArray(),
			$formDataPart->bodyToString(),
			*/
		);
		
		\dd('END');
		
		//The Config Component
		
		//###> #1 FileLocator
		$fileLocator = new FileLocator(
			[
				$stringService->getPath($projectDir, '/config/packages'),
				$stringService->getPath($projectDir, '/vendor/GrinWay/env-processor-bundle/config/packages'),
			]
		);
		
		$absConfigPaths = $fileLocator->locate(
			name: 'translation.yaml',
			currentPath: null,
			first: false,
		);
		
		//\dd($absConfigPaths);
		//###> #2 FileLoader
		$loader = new DelegatingLoader(new LoaderResolver([
			$appYamlLoader = new YamlLoader($fileLocator),
		]));
		$loader = $appYamlLoader;
		
		$configs = $loader->load($absConfigPaths);
		//\dd($configs);
		//###> #3 aProcessor->aConfiguration
		$configs = [
			[
				'scalar_node' => 'scalar_node',
				'boolean_node' => true,
				'integer_node' => 50,
				'float_node' => 50.,
				'enum_node' => NoteType::ERROR,
				
				'user' => [
					'son@1.ru',
					'son@2.ru',
				],
				
				'array_node' => [
					'key' => [
						'-category_' => '_category',
						'name' => 'command-bundle',
						'version' => 'v1.0',
						
						'-category' => '-category',
						'11' => '11',
					],
					'dop' => [
						'-category_' => 'v2.0',
						'name' => 'command-bundle',
						'version' => 'v1.0',
						
						'11' => '11',
					],
					'null_values' => null,
					'true_values' => true,
					'false_values' => false,
				],
				'scalar_prototype' => [
					1,
					'string',
				],
				
				/*
				'array_as_an_scalar' => 1,
				'variable_node' => 1,
				*/
			],
			[
				'boolean_node' => true,
				'integer_node' => 50,
				'float_node' => 50.,
				'enum_node' => NoteType::ERROR,
				'scalar_prototype' => 50,
				
				
				'array_node' => [
					'key' => [
						'-category_' => 'v2.0',
					]
				],
			],
		];
		$configuration = new FrameworkConfigurator();
		$processor = new Processor();
		$config = $processor->processConfiguration(
			$configuration,
			$configs,
		);
		
		/*
		\dd(
			$config,
		);
		*/
		
		if (null !== $trustedTokenStorage && null !== $user) {
			//$trustedTokenStorage->clearTrustedToken($user->getUserIdentifier(), 'main');
		}
		
		/*
		\dump(
			$formServerParams->hasPostMaxSizeBeenExceeded(),
			$formServerParams->getPostMaxSize(),
			$formServerParams->getNormalizedIniPostMaxSize(),
			$formServerParams->getContentLength(),
		);
		*/
		
		if (null !== $user) {
			$loginLink = $loginLink->createLoginLink($user, $r, $seconds = 100);
		} else {			
			$loginLink = '#';
		}
		
		$uri = 'https://127.0.0.1/?user=s&expires=123102380';
		
		$signedUri = $uriSigner->sign($uri, $now->add(1, 'second'));
		
        return $response;

        //\dd($twig->getLoader()->exists('home/index.html.twig'));

        //\dd($this->render('condition/condition.html.twig'));

        $obj = new ClassWithContants();

        $obj->{'value-one'} = 'SUCCESS';

        $this->addFlash(
            'notice',
            'e',
        );

        return $this->render('home/home.html.twig', [
            'hash_locator' => $callableHashLocator,
            'a' => [
                'key' => 11,
                2,
            ],
            'func' => static fn($el) => $el,
            'obj' => $obj,
            'date' => new Carbon(),
        ]);

        //$promocode = $bus->dispatch(new GetPromocodeWithTheBestPrice($promocodes));

        // synthetic
        //$container->set('app.false', $this);


        /*
        $dispatcher->dispatch(new PriceWasDecreased());
        \dump(
        );
        $bus->dispatch(new \App\Messenger\Command\Message\SendEmail(
            'example@ex.ru',
            'SOME TEXT TO THIS EMAIL',
        ));
        */




        $pa = PropertyAccess::createPropertyAccessor();

        $uesrDto = new \StdClass();
        $uesrDto->name = 'Grin';
        $uesrDto->login = 'alom';
        $uesrDto->pass = '180898';

        $pathRead = $stringService->getPath(
            $projectDir,
            'config/config/test.yaml',
        );
        $pathDump = $stringService->getPath(
            $projectDir,
            'config/config/testDump.yaml',
        );
        $array = Yaml::parseFile($pathRead, Yaml::PARSE_CUSTOM_TAGS | Yaml::PARSE_CONSTANT | Yaml::PARSE_DATETIME | Yaml::PARSE_OBJECT | Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE);

        //\dump($array);

        //###>
        $array['world']['countries'][] = [
            'user' => $uesrDto,
        ];

        $contents = Yaml::dump($array, 10, 4, Yaml::DUMP_OBJECT);
        $contents = Yaml::dump($array, 10, 4, Yaml::DUMP_NULL_AS_TILDE | Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK | Yaml::DUMP_OBJECT | Yaml::DUMP_OBJECT_AS_MAP | Yaml::DUMP_EXCEPTION_ON_INVALID_TYPE);
        \file_put_contents($pathDump, $contents);

        $reflectionExtractor = new ReflectionExtractor();
        $phpDocExtractor = new PhpDocExtractor();
        $doctrineExtractor = new DoctrineExtractor($em);
        $phpStanExtractor = new PhpStanExtractor();
        $serializerClassMetadataFactory = new ClassMetadataFactory(new AttributeLoader());
        $serializerExtractor = new SerializerExtractor($serializerClassMetadataFactory);

        $pii = new PropertyInfoExtractor(
            listExtractors: [
                //$serializerExtractor,
                $reflectionExtractor,
                //$doctrineExtractor,
            ],
            typeExtractors: [
                $phpDocExtractor,
                $reflectionExtractor,
                $doctrineExtractor,
            ],
            descriptionExtractors: [
                $phpDocExtractor,
            ],
            accessExtractors: [
                $reflectionExtractor,
            ],
            initializableExtractors: [
                $reflectionExtractor,
            ],
        );

        $properties = $pi->getProperties(User::class, []);
        $types = $pi->getTypes(User::class, 'products');
        $shortDescr = $pi->getShortDescription(User::class, 'products');
        $longDescr = $pi->getLongDescription(User::class, 'products');
        $isReadable = $pi->isReadable(User::class, 'products');
        $isWritable = $pi->isWritable(User::class, 'products');
        $isInitializable = $pi->isInitializable(User::class, 'products');


        \dump(
            $properties,
            /*
            $phpDocExtractor->getDocBlock(User::class, 'products'),
            $phpStanExtractor->getTypesFromConstructor(User::class, 'passport'),
            foreach($types as $type) {
            \dump(
            $type->getBuiltInType(),
            $type->isNullable(),
            $type->getClassName(),
            $type->isCollection(),
            $type,
            );
            }
            $types,
            $shortDescr,
            $longDescr,
            $isReadable,
            $isWritable,
            $isInitializable,
            */
        );

        $result = $em->createQuery('
			SELECT p.id + p.price, p.name AS HIDDEN name 
			FROM ' . Product::class . ' p
			WHERE p INSTANCE OF ' . FurnitureProduct::class . '
			ORDER BY name DESC
		')
            //GROUP BY p.id
            ->getResult()
            //->getSingleScalarResult()
        ;

        \dd($result);

        array_walk($result, static fn($obj) => \dump($obj->getId()));

        //\dd($user->getName(), DoctrineService::getStateName($t, $em, $user));


        $accept = $r->headers->get('accept');
        $keyValues = [
            //['key0', ['val00', 'val01', 'val02']],
            ['key1', 'val1'],
            ['key2', 'val2'],
        ];
        $stringLikeQueryOne = 'arr[key0]=11&arr[key1]=2&a=b';

        $aH = AcceptHeader::fromString($accept);

        $isGet = new MethodRequestMatcher('GET');
        $isPost = new MethodRequestMatcher('POST');
        $isRouteParamsAttribute = new AttributesRequestMatcher([
            'defVal' => '.*',
        ]);
        $isExpression = new ExpressionRequestMatcher(
            new ExpressionLanguage(),
            'path matches "~.*~" and path in ["a"]'
        );
        $isHeader = new HeaderRequestMatcher([
            'accept',
        ]);
        $isHost = new HostRequestMatcher('127.*');
        $isIps = new IpsRequestMatcher([
            '127.0.0.0',
            '127.0.0.1',
        ]);
        $isQueryParameters = new QueryParameterRequestMatcher([
            'req',
            'a',
        ]);





        //$response = new Response('{"a": 13}', Response::HTTP_OK);
        $response = $this->render('home/home.html.twig');

        $response->headers->set('Content-Type', 'text/html');
        $response->setCharset('UTF-8');
        $response->prepare($r);
        //$response->send();

        //return new StreamedJsonResponse(SomeService::getGenerator());

        //$r->headers->set('X-Sendfile', '1');
        //$r->deleteFileAfterSend();


        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'some-html-file-content.php',
        );


        return $response;

        /*
        \dump($r->cookies->all());
        $cookieString = HeaderUtils::toString([
            'PHP_TEST_COOKIE' => 'YeS',
            'Expires' => $carbon->now()->add(10, 'seconds')->format(\DateTimeInterface::COOKIE),
        ], ';');
        $response->headers->setCookie(Cookie::fromString($cookieString));
        */
        //$response->setPublic();
        //$response->setPrivate();
        //$response->expire();
        //$response->setExpires($carbon->now()->tz('00')->add(1, 'day'));

            /*
        \dd(
            $r->getClientIps(),
            $r->getScriptName(),
            $r->getRequestUri(),
            $r->getRelativeUriForPath('lasa/gola'),
            $r->getContentTypeFormat(),
            $r->getProtocolVersion(),
            $r->getETags(),
            $r->getPreferredFormat(),
            */

            /*
            $isGet->matches($r),
            $isPost->matches($r),
            $isRouteParamsAttribute->matches($r),
            $r->attributes->all(),
            $isExpression->matches($r),
            $isHeader->matches($r),
            $isHost->matches($r),
            $isIps->matches($r),
            $isQueryParameters->matches($r),
            */

            /*
            $ip = '127.0.0.1',
            IpUtils::isPrivateIp($ip),
            IpUtils::isPrivateIp('77.82.215.52'),
            IpUtils::anonymize($ip),
            */

            /*
            $aH->all(),
            $aH->get('application/json')->getQuality(),
            $r->getAcceptableContentTypes(),
            $aH->has('text/html'),
            $aH->get('application/xml')->getAttributes(),
            */

            /*
            $accept,
            $r->getAcceptableContentTypes(),
            $r->getLanguages(),
            $r->getCharsets(),
            $r->getEncodings(),
            */

            /*
            HeaderUtils::split($accept, ',;'),
            $combine = HeaderUtils::combine($keyValues),
            HeaderUtils::toString($combine, '&'),
            HeaderUtils::quote('a" "строка'),
            HeaderUtils::unquote('a строка'),
            HeaderUtils::parseQuery($stringLikeQueryOne),
            */

            /*
            $r->getPathInfo(),
            $r->getPayload(),
            $r->getContent(),
            $r->toArray(),
            'request: ' , $r->request,
            'query: ' , $r->query,
            'cookies: ' , $r->cookies,
            'files: ' , $r->files,
            //'server: ' , $r->server,
            'headers: ' , $r->headers,

            'attributes: ' , $r->attributes,
        );
            */
			
		$inputData = 10;
		
		$check = Validation::createCallable($v,
			new Constraints\When(
				expression: 'is_array(value)',
				constraints: [
					new Constraints\All([
						new Constraints\Range(
							min: 0,
							max: 20,
						),
					]),
				],
			),
			new Constraints\When(
				expression: 'is_integer(value)',
				constraints: [
					new Constraints\Range(
						min: 0,
						max: 10,
					),
				],
			),
		);
		
		$check($inputData);

        return $response;
    }

    #[Route(path: '/product/abc')]
    public function removeProduct()
    {
        return new Response('HTML');
    }

    #[Route(path: '/product/{id<[0-9]+>}', methods: ['GET'])]
    //#[Cache(public: true, maxage: 10)]
    public function condition(): Response
    {

        $resp = $this->render('condition/condition.html.twig', [
            'value' => 123,
        ]);

        return $resp;

        /*
        foreach([
            ProductType::EAT,
            ProductType::FURNITURE,
            ProductType::MILK,
        ] as $category) {
            $productCategory = new ProductCategory($category);
            $em->persist($productCategory);
        }
        $em->flush();
        */


        /*

        $product = $productRep->findBy([], [
            'id' => Criteria::DESC,
        ]);

        // ~~i
        $product = $productRep->findOneBy([
            'name' => 'швабра',
            'price' => '1000',
        ], [
            'id' => Criteria::DESC,
        ]);

        $product = new Product('Швабра', '2000', $enUtcCarbon->now()->add(10, 'days'));
        $em->persist($product);
        $em->flush();
        */

        return $resp;
    }

    #[Template('blog/_blogrin_way_list.html.twig')]
    public function recentBlogs(
        $faker,
        $ru12Carbon,
        int $max,
    ) {
        // db
        $randBlog = static fn() => [
            'name' => $faker->name,
            'createdAt' => $ru12Carbon->make($faker->date),
            'isPublic' => ([true, false])[$faker->numberBetween(0, 1)],
        ];
        $blogsFromDb = \array_map(static fn() => $randBlog(), \range(0, $max));

        return ['blogs' => $blogsFromDb];
    }

    #[Template('product/_product_types_form.html.twig')]
    #[Route('product/types/{id?51}/{_locale?ru}',
		requirements: [
			//'id' => Requirement::UUID,
			'id' => '[0-9]+',
		],
	)]
	/**
	* @var Carbon $nowModified +10 year
	*/
    public function productTypes(
		Request $request,
		string $id,
		string $_locale,
		TaskRepository $taskRepo,
		UserRepository $userRepo,
		ImageRepository $imageRepo,
		StringService $stringService,
		EntityManagerInterface $em,
		#[Autowire('@event_dispatcher')]
		$dispatcher,
		PropertyAccessorInterface $pa,
		ValidatorInterface $v,
		UploaderHelper $vichService,
		#[Autowire('@kernel')]
		$kernel,
		$t,
		$emojiSlugger,
		#[Autowire('@app.engligh_inflector')]
		$enInf,
		$appEnabledLocales,
		LocaleSwitcher $localeSwitcher,
		RequestContext $rc,
		MessageBusInterface $bus,
		UrlGeneratorInterface $ug,
		$_route,
		//#[Autowire('@security.user.provider.concrete.app_user_provider')]
		//$userProvider,
		UserPasswordHasherInterface $userPasswordHasherInterface,
		PasswordHasherFactoryInterface $hasherFacotry,
		?TokenInterface $token,
	) {
		$r = $request;
		$hasher = $hasherFacotry->getPasswordHasher('admin_hasher');
		$string = '123';
		//$hash = $hasher->hash($string);
		
		//\dump($token->getRoleNames(), $token->getUser()->getRoles());
		
		//$request->getSession()->set('someKey', new \StdClass());
		
		//$security->logout(false);
		
		//return $response;
		
		$locale = $localeSwitcher->getLocale();
		//$localeSwitcher->setLocale('en');
		//$localeSwitcher->reset();
		$callbackWithAnotherLocale = static function($locale) use ($localeSwitcher, $r, $rc): void {
			\dd(
				//$bus->dispatch(new RunCommandMessage('debug:translation ru --domain=app.form')),
				$r->getLocale(),
				$localeSwitcher->getLocale(),
				$rc->getParameters()['_locale'],
			);			
		};
		//$localeSwitcher->runWithLocale('ar', $callbackWithAnotherLocale);
		//\dd($originLocale);
		//$t->setLocale('en');
		
		//$id = Uuid::fromString($id);
		//$obj = $imageRepo->find($id);
		$obj = null;

		/*
		$obj->setUserDto(new UserDto(id: 0, name: 'Unknown', age: 0));
		\dd(
			$vichService->asset($obj),
		);
		\dd(
			'guessExtension: '. $obj->getFile()->guessExtension(),
			'getMimeType: '. $obj->getFile()->getMimeType(),
			//'getContent: '. $obj->getFile()->getContent(),
			
			'getBasename: '. $obj->getFile()->getBasename('.jpg'),
			'getFilename: '. $obj->getFile()->getFilename(),
			'getPath: '. $obj->getFile()->getPath(),
			'getPathname: '. $obj->getFile()->getPathname(),
			'getRealPath: '. $obj->getFile()->getRealPath(),
			'getSize: '. $obj->getFile()->getSize(),
			'getType: '. $obj->getFile()->getType(),
			'isDir: '. $obj->getFile()->isDir(),
			'isExecutable: '. $obj->getFile()->isExecutable(),
			'isFile: '. $obj->getFile()->isFile(),
			'isLink: '. $obj->getFile()->isLink(),
			'isReadable: '. $obj->getFile()->isReadable(),
			'isWritable: '. $obj->getFile()->isWritable(),
		);
		*/
		//$data = '';
		//$form = $this->createForm(ImageFormType::class, $obj, options: [
		$form = $this->createForm(ImageFormType::class, $obj, options: [
			//'validation_groups' => [
			//	'TestForTestFormType',
			//	'AbstractTestForTestFormType',
			//	'Default',
			//],
			//'forbid_modify_props_feature' => false,
			//'action' => $this->generateUrl($r->attributes->get('_route', 'app_home_producttypes')),
		]);
		
		//if ($r->isMethod('PATCH')) {
			/* 
			$request = $r->getPayload()->all();
			$files = $r->files->all();
			$rootKey = '['.$form->getName().']';
			$payload = $pa->getValue($request, $rootKey) ?? [];
			$payloadFiles = $pa->getValue($files, $rootKey) ?? [];
			$payload = \array_merge($payload, $payloadFiles);
			
			$pa->setValue($payload, '[product][webPath]', 'images/2.jpg');
			\dd($payload);
			*/
			
			//$form->submit($payload, false);
			$form->handleRequest($r);
			
			if ($form->isSubmitted() && $form->isValid()) {
				/*
				\dd($payload, \mb_strlen($form->get('product')->getData()?->getName()));
				*/
				
				$obj = $form->getData();
					/*
				\dd(
					$form->getData(),
					$form->get('id')->getData(),
					$payload,
					
					$obj->getFileSize(),
					$obj->getFileMimeType(),
					$obj->getFileOriginalName(),
					$obj->getFileDimensions(),
					$form->get('file')->get('delete')->getData(),
					$form->get('file')->getData(),
					$obj->getVichFile(),
				$em->persist($obj);
				$em->remove($obj);
				);
				$em->flush();
					*/
					\dd($obj);
				//\dd($payload, $data);
				return $this->redirectToRoute('app_home_producttypes', $r->attributes->get('_route_params', []));
			}
		//}
		
        return [
            'form' => $form,
            'obj' => $obj,
        ];
    }
}

class MyClass {
	public \DateTimeInterface $createdAt;
	
	#[SerializedPath('[level_1][level_2]')]
	public ?string $data = null;
}
