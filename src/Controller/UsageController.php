<?php

namespace App\Controller;

use App\Entity\Usage;
use App\Form\UsageType;
use App\Repository\UsageRepository;
use App\Utils\SerializerUtil;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UsageController extends AbstractApiController
{
    private UsageRepository $usageRepository;

    /**
     * @param UsageRepository $usageRepository
     */
    public function __construct(UsageRepository $usageRepository)
    {
        $this->usageRepository = $usageRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {

        $usages = $this->usageRepository->all();

        $serializer = SerializerUtil::circularSerializer();

        $usagesSerialized = $serializer->serialize($usages, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($usagesSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function findByIdAction(Request $request, int $id): Response
    {
        try {
            $usage = $this->usageRepository->findById($id);
        } catch (Exception $e) {
            return new Response(json_encode([
                "message" => $e->getMessage()
            ]), $e->getCode(), []);
        }

        $serializer = SerializerUtil::circularSerializer();

        $usageSerialized = $serializer->serialize($usage, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($usageSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws NonUniqueResultException
     */
    public function removeByIdAction(Request $request, int $id): Response
    {
        $usage = $this->usageRepository->findById($id);
        $usageId = $usage->getId();
        $usageHouse = $usage->getHouse()->getId();

        $this->usageRepository->remove($usage);

        $response = [
            "status" => 200,
            "message" => "success",
            "description" => "Usage ". $usageId . " at house " . $usageHouse . " removed."
        ];

        return new Response(json_encode($response), 200, []);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(UsageType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            print 'ERROR: Data Invalid';
            exit;
        }

        /** @var Usage $usage */
        $usage = $form->getData();

        // Check if device belongs to house
        $device = $usage->getDevice();
        $house = $usage->getHouse();
        $devices = $house->getDevices();

        if ($devices->contains($device)) {
            $this->usageRepository->save($usage);
        } else {
            return new Response(json_encode([
                "status" => "Error",
                "message" => "Device " . $device->getId() . " does not belong to House " . $house->getId()
            ]), 500, []);
        }

        $serializer = SerializerUtil::circularSerializer();

        $usageSerialized = $serializer->serialize($usage, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($usageSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeAllAction(Request $request): JsonResponse
    {
        try {
            $usages = $this->usageRepository->all();

            foreach ($usages as $usage) {
                $this->usageRepository->remove($usage);
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
            "description" => "Deleted all usage logs"
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
        $usage = $this->usageRepository->findById($id);

        if (!$usage) {
            throw new NotFoundHttpException('Neighborhood not found');
        }

        $form = $this->buildForm(UsageType::class, $usage, [
            'method' => $request->getMethod()
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            print 'ERROR: Data Invalid';
            exit;
        }

        /** @var Usage $data */
        $data = $form->getData();

        $this->usageRepository->save($data);

        $serializer = SerializerUtil::circularSerializer();

        $usageSerialized = $serializer->serialize($usage, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($usageSerialized, 200, []);
    }
}