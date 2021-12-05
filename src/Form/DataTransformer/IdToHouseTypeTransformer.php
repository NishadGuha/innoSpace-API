<?php

namespace App\Form\DataTransformer;

use App\Entity\HouseType;
use App\Repository\HouseTypeRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToHouseTypeTransformer implements DataTransformerInterface
{
    private HouseTypeRepository $houseTypeRepository;

    /**
     * @param HouseTypeRepository $houseTypeRepository
     */
    public function __construct(HouseTypeRepository $houseTypeRepository)
    {
        $this->houseTypeRepository = $houseTypeRepository;
    }

    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }

        return $value->getId();
    }

    public function reverseTransform($value): HouseType
    {
        try {
            $houseType = $this->houseTypeRepository->findById(intval($value));
        } catch (TransformationFailedException $e) {
            throw new TransformationFailedException(sprintf(
                'A house type with id "%s" does not exist!',
                $value
            ));
        }

        return $houseType;
    }
}