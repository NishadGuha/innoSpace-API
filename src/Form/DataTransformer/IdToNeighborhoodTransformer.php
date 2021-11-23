<?php

namespace App\Form\DataTransformer;

use App\Entity\Neighborhood;
use App\Repository\NeighborhoodRepository;
use Doctrine\ORM\NonUniqueResultException;
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
     * Transform Entity to String
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

    /**
     * Transform String to Entity
     * @param mixed $value
     * @return Neighborhood
     * @throws NonUniqueResultException
     */
    public function reverseTransform($value): Neighborhood
    {
        try {
            $neighborhood = $this->neighborhoodRepository->findById(intval($value));
        } catch (TransformationFailedException $e) {
            throw new TransformationFailedException(sprintf(
                'An neighborhood with id "%s" does not exist!',
                $value
            ));
        }

        return $neighborhood;
    }
}