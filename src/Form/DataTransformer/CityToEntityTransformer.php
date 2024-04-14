<?php 

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use App\Entity\Cities;
use Doctrine\ORM\EntityManagerInterface;

class CityToEntityTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($city)
    {
        if (null === $city) {
            return '';
        }

        return $city->getId();
    }

    public function reverseTransform($cityId)
    {
        if (!$cityId) {
            return null;
        }

        $city = $this->entityManager
            ->getRepository(Cities::class)
            ->find((int)$cityId);

        if (null === $city) {
            throw new TransformationFailedException(sprintf(
                'La localidad/ciudad con el ID "%s" no existe.',
                $cityId
            ));
        }

        return $city;
    }
}