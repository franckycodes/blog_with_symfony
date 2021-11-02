<?php

namespace App\Repository;

use App\Entity\BlogPosts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BlogPosts|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogPosts|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogPosts[]    findAll()
 * @method BlogPosts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogPostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogPosts::class);
    }

    // /**
    //  * @return BlogPosts[] Returns an array of BlogPosts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BlogPosts
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getAllPosts()
    {
        $query=$this->createQueryBuilder('p')
        ->orderBy('p.id', 'DESC') 
        ->setFirstResult(0)
        ->setMaxResults(10)
        ->getQuery();

        return $query->getArrayResult();
    }
    public function getAllBlog()
    {
        $query=$this->createQueryBuilder('p')
        ->orderBy('p.id', 'DESC')  
        ->getQuery();

        return $query->getArrayResult();
    }
    public function deleteBlog($blogId)
    {
        $query=$this->createQueryBuilder('p')
        ->delete(BlogPosts::class, 'p')
        ->where('p.id = :blogId')
        ->setParameter(':blogId',(int)$blogId)->getQuery();

        return $query->getArrayResult();
    }
    public function getBlog($blogId)
    {
        $query=$this->createQueryBuilder('p') 
        ->where('p.id = :blogId')
        ->setParameter(':blogId',(int)$blogId)->getQuery();

        return $query->getArrayResult();
    }

    //update a targeted blog post
    public function updateBlog($blogId, $blogTitle, $blogDescription)
    {
        $dateNow=new \DateTime(date('Y-d-m h:i:s'));
        $query=$this->createQueryBuilder('p')
        ->update(BlogPosts::class, 'p')
    
        ->set('p.post_title',':qTitle')
        ->set('p.description', ':qDescription')
        ->set('p.date_last_update', ':qNow')
        ->where('p.id=:qId')
        ->setParameters(['qId'=>$blogId,
                        'qNow'=>$dateNow,
                        'qTitle'=>$blogTitle,
                        'qDescription'=>$blogDescription])
                        ->getQuery();

        return $query->getArrayResult();  
    }
}
