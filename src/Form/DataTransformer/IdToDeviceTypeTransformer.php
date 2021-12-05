<?php

namespace App\Form\DataTransformer;

use App\Entity\DeviceType;
use App\Repository\DeviceTypeRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToDeviceTypeTransformer implements DataTransformerInterface
{
    private DeviceTypeRepository $deviceTypeRepository;

    /**
     * @param DeviceTypeRepository $deviceTypeRepository
     */
    public function __construct(DeviceTypeRepository $deviceTypeRepository)
    {
        $this->deviceTypeRepository = $deviceTypeRepository;
    }

    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }

        return $value->getId();
    }

    public function reverseTransform($value): DeviceType
    {
        try {
            $deviceType = $this->deviceTypeRepository->findById(intval($value));
        } catch (TransformationFailedException $e) {
            throw new TransformationFailedException(sprintf(
                'A device type with id "%s" does not exist!',
                $value
            ));
        }

        return $deviceType;
    }
}