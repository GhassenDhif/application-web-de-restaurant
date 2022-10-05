<?php

namespace App\Repository;

use App\Entity\Partenaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Partenaire>
 *
 * @method Partenaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partenaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partenaire[]    findAll()
 * @method Partenaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartenaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partenaire::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Partenaire $entity, bool $flush = true): void
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
    public function remove(Partenaire $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function nb()
    {
    
        $entity=$this->getEntityManager();
        $query=$entity->createQuery('Select count(p) from App\Entity\Partenaire p');
        return $query->getSingleScalarResult();    
    }
    

    public function listepartenaireparcateg($id){
        return $this->createQueryBuilder('p')
        ->join('p.categorie','c')
        ->addSelect('c')
        ->where('c.id=:id')
        ->setParameter('id',$id)
        ->getQuery()
        ->getScalarResult();
        }

    // /**
    //  * @return Partenaire[] Returns an array of Partenaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Partenaire
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
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




}
