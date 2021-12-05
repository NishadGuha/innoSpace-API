<?php

namespace App\Controller;

use App\Form\DeviceTypeType;
use App\Repository\DeviceTypeRepository;
use App\Utils\SerializerUtil;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DeviceTypeController extends AbstractApiController
{
    private DeviceTypeRepository $deviceTypeRepository;

    /**
     * @param DeviceTypeRepository $deviceTypeRepository
     */
    public function __construct(DeviceTypeRepository $deviceTypeRepository)
    {
        $this->deviceTypeRepository = $deviceTypeRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {

        $deviceTypes = $this->deviceTypeRepository->all();

        $serializer = SerializerUtil::circularSerializer();

        $deviceTypesSerialized = $serializer->serialize($deviceTypes, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($deviceTypesSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws NonUniqueResultException
     */
    public function findByIdAction(Request $request, int $id): Response
    {
        $deviceType = $this->deviceTypeRepository->findById($id);

        $serializer = SerializerUtil::circularSerializer();

        $deviceTypeSerialized = $serializer->serialize($deviceType, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($deviceTypeSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws NonUniqueResultException
     */
    public function removeByIdAction(Request $request, int $id): Response
    {
        $deviceType = $this->deviceTypeRepository->findById($id);
        $deviceTypeId = $deviceType->getId();

        $this->deviceTypeRepository->remove($deviceType);

        $response = [
            "status" => 200,
            "message" => "success",
            "description" => "Device type ". $deviceTypeId . " removed."
        ];

        return new Response(json_encode($response), 200, []);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(DeviceTypeType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            print 'ERROR: Data Invalid';
            exit;
        }

        try {
            $deviceType = $form->getData();
            $this->deviceTypeRepository->save($deviceType);
        } catch (Exception $e) {
            return new Response(json_encode([
                "message" => $e->getMessage()
            ]), $e->getCode(), []);
        }

        $serializer = SerializerUtil::circularSerializer();

        // Serialize your object in Json
        $deviceTypeSerialized = $serializer->serialize($deviceType, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($deviceTypeSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeAllAction(Request $request): JsonResponse
    {
        try {
            $deviceTypes = $this->deviceTypeRepository->all();

            foreach ($deviceTypes as $deviceType) {
                $this->deviceTypeRepository->remove($deviceType);
            }
        } catch (Exception $e) {
            return $this->json([
                "status" => "Error",
                "message" => $e->getMessage()
            ]);
        }

        return $this->json([
            "status" => 200,
            "message" => "success",
            "description" => "Deleted all device types"
        ]);

    }
}