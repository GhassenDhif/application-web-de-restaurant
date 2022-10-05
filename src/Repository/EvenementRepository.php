<?php

namespace App\Repository;

use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
* @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
* @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
* @method Evenement[]    findAll()
* @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
*/
class EvenementRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Evenement $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Evenement $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


     /**
      * @return Evenement[] Returns an array of Evenement objects
      */

    public function findEntitiesByString($str)
    {
        return $this->getEntityManager()
            ->createQuery('select e 
                from Kernel:Post e
                where e.nom like str'
            )
        ->setParameter('str','%'.$str.'%')
        ->getResult();
    }

    public function sort()
    {
        return $this->findBy(array(), array('date' => 'ASC'));
    }

}
