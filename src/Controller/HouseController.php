<?php

namespace App\Controller;

use App\Entity\House;
use App\Form\HouseType;
use App\Repository\HouseRepository;
use App\Repository\NeighborhoodRepository;
use App\Utils\SerializerUtil;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class HouseController extends AbstractApiController
{
    private HouseRepository $houseRepository;

    private NeighborhoodRepository $neighborhoodRepository;

    /**
     * @param HouseRepository $houseRepository
     * @param NeighborhoodRepository $neighborhoodRepository
     */
    public function __construct(HouseRepository $houseRepository, NeighborhoodRepository $neighborhoodRepository)
    {
        $this->houseRepository = $houseRepository;
        $this->neighborhoodRepository = $neighborhoodRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {

        $houses = $this->houseRepository->all();

        $serializer = SerializerUtil::circularSerializer();

        $housesSerialized = $serializer->serialize($houses, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($housesSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws NonUniqueResultException
     */
    public function findByIdAction(Request $request, int $id): Response
    {
        $house = $this->houseRepository->findById($id);

        $serializer = SerializerUtil::circularSerializer();

        $houseSerialized = $serializer->serialize($house, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($houseSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws NonUniqueResultException
     */
    public function removeByIdAction(Request $request, int $id): Response
    {
        $house = $this->houseRepository->findById($id);
        $houseId = $house->getId();

        $this->houseRepository->remove($house);

        $response = [
            "status" => 200,
            "message" => "success",
            "description" => "House ". $houseId . " at " . $house->getHouseNumber() . " removed."
        ];

        return new Response(json_encode($response), 200, []);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(HouseType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            print 'ERROR: Data Invalid';
            exit;
        }

        try {
            $house = $form->getData();
            $this->houseRepository->save($house);
        } catch (Exception $e) {
            return new Response(json_encode([
                "message" => $e->getMessage()
            ]), $e->getCode(), []);
        }

        $serializer = SerializerUtil::circularSerializer();

        // Serialize your object in Json
        $houseSerialized = $serializer->serialize($house, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($houseSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeAllAction(Request $request): JsonResponse
    {
        try {
            $houses = $this->houseRepository->all();

            foreach ($houses as $house) {
                $this->houseRepository->remove($house);
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
            "description" => "Deleted all houses"
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
        $house = $this->houseRepository->findById($id);

        if (!$house) {
            throw new NotFoundHttpException('house not found');
        }

        $form = $this->buildForm(HouseType::class, $house, [
            'method' => $request->getMethod()
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            print 'ERROR: Data Invalid';
            exit;
        }

        try {
            $house = $form->getData();
            $this->houseRepository->save($house);
        } catch (Exception $e) {
            return new Response(json_encode([
                "message" => $e->getMessage()
            ]), $e->getCode(), []);
        }

        $serializer = SerializerUtil::circularSerializer();

        $houseSerialized = $serializer->serialize($house, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($houseSerialized, 200, []);
    }
}