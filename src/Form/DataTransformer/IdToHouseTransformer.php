<?php

namespace App\Form\DataTransformer;

use App\Entity\House;
use App\Repository\HouseRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToHouseTransformer implements DataTransformerInterface
{

    private HouseRepository $houseRepository;

    /**
     * @param HouseRepository $houseRepository
     */
    public function __construct(HouseRepository $houseRepository)
    {
        $this->houseRepository = $houseRepository;
    }

    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }

        return $value->getId();
    }

    public function reverseTransform($value): House
    {
        try {
            $house = $this->houseRepository->findById(intval($value));
        } catch (TransformationFailedException $e) {
            throw new TransformationFailedException(sprintf(
                'An house with id "%s" does not exist!',
                $value
            ));
        }

        return $house;
    }
}