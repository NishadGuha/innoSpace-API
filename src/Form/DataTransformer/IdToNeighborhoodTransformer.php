<?php

namespace App\Form\DataTransformer;

use App\Entity\Neighborhood;
use App\Repository\NeighborhoodRepository;
use Exception;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToNeighborhoodTransformer implements DataTransformerInterface
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
     * @param Neighborhood|null $value
     * @return string
     */
    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }

        return $value->getId();
    }

    public function reverseTransform($value): Neighborhood
    {
        try {
            $neighborhood = $this->neighborhoodRepository->findById(intval($value));
        } catch (TransformationFailedException $e) {
            throw new TransformationFailedException(sprintf(
                'An neighborhood with number "%s" does not exist!',
                $value
            ));
        }

        return $neighborhood;
    }
}