<?php 

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use App\Entity\States;
use Doctrine\ORM\EntityManagerInterface;

class StateToEntityTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function transform($state)
    {
        if (null === $state) {
            return '';
        }

        return $state->getId();
    }

    public function reverseTransform($stateId)
    {
        if (!$stateId) {
            return null;
        }

        $state = $this->entityManager
            ->getRepository(States::class)
            ->find((int)$stateId);

        if (null === $state) {
            throw new TransformationFailedException(sprintf(
                'La provincia con el ID "%s" no existe.',
                $stateId
            ));
        }

        return $state;
    }
}