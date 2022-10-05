<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categorie>
 *
 * @method Categorie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categorie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categorie[]    findAll()
 * @method Categorie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Categorie $entity, bool $flush = true): void
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
    public function remove(Categorie $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    
    public function amine($type)
    {
        return $this->createQueryBuilder('c')
            ->where('c.type like :type')
            ->setParameter('type', '%'.$type.'%')
            ->getQuery()
            ->getResult();
    }

    
    public function trier(){ 
        $entityManger=$this->getEntityManager();
        $q=$entityManger->createQuery('Select c From App\Entity\Categorie c ORDER BY c.type ASC');
        return $q->getResult();
        
        }
    // /**
    //  * @return Categorie[] Returns an array of Categorie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Categorie
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    


    public function stat(){
        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT categorie.type,COUNT(categorie.id) as nbr FROM App\Entity\Categorie left join App\Entity\Partenaire on categorie.id=partenaire.categorie_id group by categorie.id ");
        return $query->getSingleScalarResult();

    }



    
    public function findAllMenubyDescription($type){
        return $this->createQueryBuilder('Categorie')
            ->where('Categorie.type LIKE :type')
            ->setParameter('type', '%'.$type.'%')
            ->getQuery()
            ->getResult();
    }



}

