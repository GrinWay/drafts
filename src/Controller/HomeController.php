<?php

namespace App\Controller;

use function Symfony\component\string\u;
use function Symfony\component\string\b;

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
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use App\Dto\User\NullUserDto;
use App\Dto\User\UserDto;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\UriSigner;
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
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Context\Encoder\JsonEncoderContextBuilder;
use App\Messenger\Notifier\SendEmail;
use App\Messenger\Notifier\ToAdminSendEmail;
use App\Messenger\Notifier\NotifierHandlers;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Symfony\Component\Serializer\SerializerInterface;
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
use Psr\Container\ContainerInterface;
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

class HomeController extends AbstractController
{
    public function __construct(
        #[\App\Attribute\AutowireMyMethodOf(\App\Service\SomeService::class)]
        protected \Closure $getGenerator,
    ) {
    }

    #[Route(path: '/{slug<[a-zA-Zа-яА-Я0-9_\-\:]+>?1}')]
    //#[IsCsrfTokenValid(id: 'default', tokenKey: '_token')]
    //#[SomeAttribute]
    public function home(
        Request $r,
        RequestStack $requestStack,
        ParameterBagInterface $parameters,
        $t,
        #[Autowire(param: 'kernel.project_dir')]
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
        //StringService $stringService,
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
        Request $request,
        /**
         * controllerArgumentsDynamicDbEventListener: METHOD(RESULT_OF_REPOSITORY, ...ARGS)
         *
         * @param Carbon $now = +2 days
         */
        ?Carbon $now,
        $ru12Carbon,
        //$someValue,
        #[Autowire('@twig')]
        $twig,
        $slug,
        #[Autowire('@security.csrf.token_manager')]
        $csrfTokenManager,
    ) {
        $etagContent = '';

        [$task, $form] = $get(new TaskForm(['slug' => $slug]));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            \dd(
				$form->getParent(),
            /*
                $token = $csrfTokenManager->getToken('default'),
                //$form->get('_token')->getData(),
                //$token = $request->getPayload()->get('_token'),
                $csrfTokenManager->isTokenValid(new CsrfToken('default', $token)),
                //$request->getPayload()->all(),
                'text_unmapped: ', $form->get('text_unmapped')->getData(), //null
            */
            );
            $em->flush();

            return $this->redirectToRoute('app_home_home', [
                'slug' => $task->getSlug(),
            ]);
        }

        $response = $this->render('home/index.html.twig', [
            'form' => $form,
        ]);

        //$response->setEtag(md5($etagContent));
        if ($response->isNotModified($request)) {
            return $response;
        }
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
        return $this->render('admin/index.html.twig');
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
    #[Route('product/types/{id?27}',
		requirements: [
			//'id' => '[0-9a-fA-F\-]{8}\-[0-9a-fA-F\-]{4}\-[0-9a-fA-F\-]{4}\-[0-9a-fA-F\-]{4}\-[0-9a-fA-F\-]{12}',
			'id' => '[0-9]+',
		],
	)]
	/**
	* @var Carbon $nowModified +8year
	*/
    public function productTypes(
		Request $r,
		string $id,
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
	) {
		$result = (string) u('String ~Reg~ witht conteinntdne')
			->ignoreCase()
			->replace('~reg~', '')
			->append('!')
		;
		$result = u('лодвыфао')->ignoreCase()->indexOf('л').'';
		$result = ''.u('a certain pattern::phone')->before('::');

		\dd($result);
//$id = Uuid::fromString($id);
		$obj = $imageRepo->find($id);
		
		/*
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
		//$form = $this->createForm(TaskFormType::class, $obj, options: [
		$form = $this->createForm(ImageFormType::class, $obj, options: [
			//'validation_groups' => [
			//	'TestForTestFormType',
			//	'AbstractTestForTestFormType',
			//	'Default',
			//],
			//'forbid_modify_props_feature' => false,
		]);
        
		if ($r->isMethod('PATCH')) {
			$request = $r->getPayload()->all();
			$files = $r->files->all();
			$rootKey = '['.$form->getName().']';
			$payload = $pa->getValue($request, $rootKey) ?? [];
			$payloadFiles = $pa->getValue($files, $rootKey) ?? [];
			$payload = \array_merge($payload, $payloadFiles);
			/* 
			$pa->setValue($payload, '[product][webPath]', 'images/2.jpg');
			\dd($payload);
			*/
			
			$form->submit($payload, false);
			if ($form->isSubmitted() && $form->isValid()) {
				/*
				\dd($payload, \mb_strlen($form->get('product')->getData()?->getName()));
				*/
				
				$obj = $form->getData();
					/*
				\dd(
					$payload,
					$obj,
					
					$obj->getFileSize(),
					$obj->getFileMimeType(),
					$obj->getFileOriginalName(),
					$obj->getFileDimensions(),
					$form->get('file')->get('delete')->getData(),
					$form->get('file')->getData(),
					$obj->getVichFile(),
				);
				$em->persist($obj);
				$em->remove($obj);
					*/
				$em->flush();
				//\dd($payload, $data);
				return $this->redirectToRoute('app_home_producttypes', $r->attributes->get('_route_params', []));
			}
		}
		
        return [
            'form' => $form,
            'obj' => $obj,
        ];
    }
}
