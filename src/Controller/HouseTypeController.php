<?php

namespace App\Controller;

use App\Form\HouseTypeType;
use App\Repository\HouseTypeRepository;
use App\Utils\SerializerUtil;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HouseTypeController extends AbstractApiController
{
    /**
     * @var HouseTypeRepository
     */
    private HouseTypeRepository $houseTypeRepository;

    /**
     * @param HouseTypeRepository $houseTypeRepository
     */
    public function __construct(HouseTypeRepository $houseTypeRepository)
    {
        $this->houseTypeRepository = $houseTypeRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {

        $houseTypes = $this->houseTypeRepository->all();

        $serializer = SerializerUtil::circularSerializer();

        $houseTypesSerialized = $serializer->serialize($houseTypes, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($houseTypesSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws NonUniqueResultException
     */
    public function findByIdAction(Request $request, int $id): Response
    {
        $houseType = $this->houseTypeRepository->findById($id);

        $serializer = SerializerUtil::circularSerializer();

        $houseTypeSerialized = $serializer->serialize($houseType, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($houseTypeSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return Response
     * @throws NonUniqueResultException
     */
    public function removeByIdAction(Request $request, int $id): Response
    {
        $houseType = $this->houseTypeRepository->findById($id);
        $houseTypeId = $houseType->getId();

        $this->houseTypeRepository->remove($houseType);

        $response = [
            "status" => 200,
            "message" => "success",
            "description" => "House type ". $houseTypeId . " removed."
        ];

        return new Response(json_encode($response), 200, []);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function createAction(Request $request): Response
    {
        $form = $this->buildForm(HouseTypeType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            print 'ERROR: Data Invalid';
            exit;
        }

        try {
            $houseType = $form->getData();
            $this->houseTypeRepository->save($houseType);
        } catch (Exception $e) {
            return new Response(json_encode([
                "message" => $e->getMessage()
            ]), $e->getCode(), []);
        }

        $serializer = SerializerUtil::circularSerializer();

        // Serialize your object in Json
        $houseTypeSerialized = $serializer->serialize($houseType, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return new Response($houseTypeSerialized, 200, []);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeAllAction(Request $request): JsonResponse
    {
        try {
            $houseTypes = $this->houseTypeRepository->all();

            foreach ($houseTypes as $houseType) {
                $this->houseTypeRepository->remove($houseType);
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
            "description" => "Deleted all house types"
        ]);

    }
}