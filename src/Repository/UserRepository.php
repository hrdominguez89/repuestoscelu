<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

     /**
     * Upgrades the encoded password of a user, typically for using a better hash algorithm.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        // TODO: when encoded passwords are in use, this method should:
        // 1. persist the new password in the user storage
        // 2. update the $user object with $user->setPassword($newEncodedPassword);
    }

    public function findStatesAndCities()
    {
        return $this->createQueryBuilder('u')
            ->select('DISTINCT s.id AS state_id, s.name AS state_name, c.id AS city_id, c.name AS city_name')
            ->join('u.city', 'c')
            ->join('u.state', 's')
            ->where('u.active = :active')
            ->andWhere('u.visible = :visible')
            //->andWhere('u.role = 2') //role 2 = sucursal, 1=superadmin
            ->setParameter('active', true)
            ->setParameter('visible', true)
            ->orderBy('s.name,c.name','ASC')
            ->getQuery()
            ->getResult();
    }
    
}
