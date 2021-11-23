<?php

namespace App\Controller;

use App\Entity\Device;
use App\Form\DeviceType;
use App\Repository\DeviceRepository;
use App\Utils\SerializerUtil;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeviceController extends AbstractApiController
{

    private DeviceRepository $deviceRepository;

    /**
     * @param DeviceRepository $deviceRepository
     */
    public function __construct(DeviceRepository $deviceRepository)
    {
        $this->deviceRepository = $deviceRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {

        $devices = $this->deviceRepository->all();

        $serializer = SerializerUtil::circularSerializer();

        $devicesSerialized = $serializer->serialize($devices, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($devicesSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function findByIdAction(Request $request, int $id): Response
    {
        try {
            $device = $this->deviceRepository->findById($id);
        } catch (Exception $e) {
            return new Response(json_encode([
                "message" => $e->getMessage()
            ]), $e->getCode(), []);
        }

        $serializer = SerializerUtil::circularSerializer();

        $deviceSerialized = $serializer->serialize($device, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($deviceSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws NonUniqueResultException
     */
    public function removeByIdAction(Request $request, int $id): Response
    {
        $device = $this->deviceRepository->findById($id);
        $deviceId = $device->getId();

        $this->deviceRepository->remove($device);

        $response = [
            "status" => 200,
            "message" => "success",
            "description" => "Device ". $deviceId . " removed."
        ];

        return new Response(json_encode($response), 200, []);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(DeviceType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            print 'ERROR: Data Invalid';
            exit;
        }

        /** @var Device $device */
        $device = $form->getData();

        $this->deviceRepository->save($device);

        $serializer = SerializerUtil::circularSerializer();

        $deviceSerialized = $serializer->serialize($device, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($deviceSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeAllAction(Request $request): JsonResponse
    {
        try {
            $devices = $this->deviceRepository->all();

            foreach ($devices as $device) {
                $this->deviceRepository->remove($device);
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
            "description" => "Deleted all devices"
        ]);

    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response|void
     * @throws NonUniqueResultException
     */
    public function updateAction(Request $request, int $id)
    {
        $device = $this->deviceRepository->findById($id);

        if (!$device) {
            throw new NotFoundHttpException('Device not found');
        }

        $form = $this->buildForm(DeviceType::class, $device, [
            'method' => $request->getMethod()
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            print 'ERROR: Data Invalid';
            exit;
        }

        /** @var Device $data */
        $data = $form->getData();

        $this->deviceRepository->save($data);

        $serializer = SerializerUtil::circularSerializer();

        $deviceSerialized = $serializer->serialize($device, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($deviceSerialized, 200, []);
    }

    public function getAllUsages(Request $request, int $id): Response
    {
        $device = $this->deviceRepository->findById($id);

        $usages = $device->getUsages();

        $serializer = SerializerUtil::circularSerializer();

        $usagesSerialized = $serializer->serialize($usages, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($usagesSerialized, 200, []);
    }
}