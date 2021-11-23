<?php

namespace App\Form\DataTransformer;

use App\Entity\Device;
use App\Repository\DeviceRepository;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class IdToDeviceTransformer implements DataTransformerInterface
{

    private DeviceRepository $deviceRepository;

    /**
     * @param DeviceRepository $deviceRepository
     */
    public function __construct(DeviceRepository $deviceRepository)
    {
        $this->deviceRepository = $deviceRepository;
    }

    public function transform($value): string
    {
        if (null === $value) {
            return '';
        }

        return $value->getId();
    }

    public function reverseTransform($value): Device
    {
        try {
            $device = $this->deviceRepository->findById(intval($value));
        } catch (TransformationFailedException $e) {
            throw new TransformationFailedException(sprintf(
                'An house with id "%s" does not exist!',
                $value
            ));
        }

        return $device;
    }
}