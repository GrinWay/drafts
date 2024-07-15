<?php

namespace GrinWay\GenericParts\Controller;

use GrinWay\GenericParts\Service\{
    GrinWayCarbonService
};
use GrinWay\GenericParts\Exception\{
    GSDateTimeBadLocaleOrTimezoneException
};

use function Symfony\Component\String\u;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{
    Response,
    JsonResponse
};
use Symfony\Component\Routing\Annotation\Route;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Request;

class ApiUtcDtController extends GSAbstractController
{
    public function __construct(
        $tzSessionName,
    ) {
        parent::__construct(
            $tzSessionName,
        );
    }

    #[Route('/api/dt')]
    public function index(
        Request $request,
        $debugLogger,
    ) {
        $carbon = Carbon::now();
		
        try {
            $carbon = $carbon->forUser(
                locale: $locale     = $locale ?? $request->getLocale(),
                tz:     $tz         = $request->getSession()->get($this->tzSessionName),
            );
        } catch (\Exception $e) {
            throw new GSDateTimeBadLocaleOrTimezoneException(params: ['locale' => $locale, 'tz' => $tz]);
        }

        //$debugLogger->info('LOCALE', [$locale]);

        $dt     	= GrinWayCarbonService::isoFormat($carbon);

		$response	= $this->json($dt);
		$response->setEncodingOptions(\JSON_UNESCAPED_UNICODE | \JSON_UNESCAPED_SLASHES);
        return $response;
    }
}
