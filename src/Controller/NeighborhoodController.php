<?php

namespace App\Controller;

use App\Entity\Neighborhood;
use App\Form\NeighborhoodType;
use App\Repository\NeighborhoodRepository;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\SerializerUtil;

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

        $serializer = SerializerUtil::serializer();

        $neighborhoodSerialized = $serializer->serialize($neighborhoods, 'json');

        return new Response($neighborhoodSerialized, 200, []);
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

        $serializer = SerializerUtil::serializer();

        $neighborhoodSerialized = $serializer->serialize($neighborhood, 'json');

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
}