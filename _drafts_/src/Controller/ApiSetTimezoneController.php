<?php

namespace GrinWay\GenericParts\Controller;

use GrinWay\GenericParts\Service\{
    GSDataTimeValidator
};
use GrinWay\GenericParts\Exception\{
    GSPOSTRequestDoesnotContainParameter,
    GSCarbonInvalidTimezone,
    GSSerializerParseException
};
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{
    JsonResponse,
    Response,
    Request
};
use Symfony\Component\Routing\Annotation\Route;
use Carbon\Carbon;
use Symfony\Component\Serializer\{
    SerializerInterface,
    Serializer
};
use GrinWay\GenericParts\JsonResponse\{
    GSJsonResponseTimezoneSuccessfullySet
};

class ApiSetTimezoneController extends GSAbstractController
{
    public function __construct(
        $tzSessionName,
    ) {
        parent::__construct(
            $tzSessionName,
        );
    }

    #[Route('/api/set/timezone', priority: 0)]
    public function index(
        Request $request,
        SerializerInterface $serializer,
    ) {
        try {
            $data = $serializer->decode($request->getContent(), 'json');
        } catch (\Exception $e) {
            throw new GSSerializerParseException();
        }

        if (!isset($data['tz'])) {
            throw new GSPOSTRequestDoesnotContainParameter(
                params: ['tz'],
            );
        }
        $tz = $data['tz'];

        if (!GSDataTimeValidator::isCarbonTimezone($tz)) {
            throw new GSCarbonInvalidTimezone(
                params: ['tz' => $tz],
            );
        }

        $request->getSession()->set($this->tzSessionName, $tz);

        return new GSJsonResponseTimezoneSuccessfullySet(
            params: [$tz],
        );
    }
}
