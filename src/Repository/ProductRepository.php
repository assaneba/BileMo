<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param User $user
     * @return \Doctrine\ORM\Query
     */
    public function allProductsQuery(UserInterface $user)
    {
        return $this->createQueryBuilder('p')
            ->where( ':userId MEMBER OF p.users')
            ->setParameter('userId', $user->getId())
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ;
    }
}
