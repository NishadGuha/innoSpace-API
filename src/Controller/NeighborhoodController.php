<?php

namespace App\Controller;

use App\Entity\Neighborhood;
use App\Form\NeighborhoodType;
use App\Repository\NeighborhoodRepository;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\SerializerUtil;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NeighborhoodController extends AbstractApiController
{

    private NeighborhoodRepository $neighborhoodRepository;

    /**
     * @param NeighborhoodRepository $neighborhoodRepository
     */
    public function __construct(NeighborhoodRepository $neighborhoodRepository)
    {
        $this->neighborhoodRepository = $neighborhoodRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        $neighborhoods = $this->neighborhoodRepository->all();

        $serializer = SerializerUtil::circularSerializer();

        $neighborhoodSerialized = $serializer->serialize($neighborhoods, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($neighborhoodSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function findByIdAction(Request $request, int $id): Response
    {
        try {
            $neighborhood = $this->neighborhoodRepository->findById($id);
        } catch (Exception $e) {
            return new Response(json_encode([
                "message" => $e->getMessage()
            ]), $e->getCode(), []);
        }

        $serializer = SerializerUtil::circularSerializer();

        $neighborhoodSerialized = $serializer->serialize($neighborhood, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($neighborhoodSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws NonUniqueResultException
     */
    public function removeByIdAction(Request $request, int $id): Response
    {
        $neighborhood = $this->neighborhoodRepository->findById($id);
        $neighborhoodId = $neighborhood->getId();

        $this->neighborhoodRepository->remove($neighborhood);

        $response = [
            "status" => 200,
            "message" => "success",
            "description" => "Neighborhood ". $neighborhoodId . " at " . $neighborhood->getStreet() . " removed."
        ];

        return new Response(json_encode($response), 200, []);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(NeighborhoodType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            print 'ERROR: Data Invalid';
            exit;
        }

        /** @var Neighborhood $neighborhood */
        $neighborhood = $form->getData();

        $this->neighborhoodRepository->save($neighborhood);

        $serializer = SerializerUtil::circularSerializer();

        $neighborhoodSerialized = $serializer->serialize($neighborhood, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($neighborhoodSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeAllAction(Request $request): JsonResponse
    {
        try {
            $neighborhoods = $this->neighborhoodRepository->all();

            foreach ($neighborhoods as $neighborhood) {
                $this->neighborhoodRepository->remove($neighborhood);
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
            "description" => "Deleted all neighborhoods"
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
        $neighborhood = $this->neighborhoodRepository->findById($id);

        if (!$neighborhood) {
            throw new NotFoundHttpException('Neighborhood not found');
        }

        $form = $this->buildForm(NeighborhoodType::class, $neighborhood, [
            'method' => $request->getMethod()
        ]);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            print 'ERROR: Data Invalid';
            exit;
        }

        /** @var Neighborhood $data */
        $data = $form->getData();

        $this->neighborhoodRepository->save($data);

        $serializer = SerializerUtil::circularSerializer();

        $neighborhoodSerialized = $serializer->serialize($neighborhood, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($neighborhoodSerialized, 200, []);
    }

    public function getAllHouses(Request $request, int $id): Response
    {
        $neighborhood = $this->neighborhoodRepository->findById($id);

        $houses = $neighborhood->getHouses();

        $serializer = SerializerUtil::circularSerializer();

        $housesSerialized = $serializer->serialize($houses, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($housesSerialized, 200, []);
    }
}