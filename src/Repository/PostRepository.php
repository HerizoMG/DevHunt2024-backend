<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    //    /**
    //     * @return Post[] Returns an array of Post objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

	public function findByUserRole(string $role): array
	{
		return $this->createQueryBuilder('p')
			->innerJoin('p.user', 'u')
			->where('u.'.$role.' = true')
			->getQuery()
			->getResult();
	}

	public function findByUserRoleAndKeyword(string $role, string $keyword): array
	{
		return $this->createQueryBuilder('p')
			->innerJoin('p.user', 'u')
			->andWhere('(
            p.title LIKE :keyword OR
            p.description LIKE :keyword OR
            u.firstName LIKE :keyword OR
            u.lastName LIKE :keyword
        )')
			->andWhere('u.'.$role.' = true')
			->setParameter('keyword', '%' . $keyword . '%')
			->getQuery()
			->getResult();
	}

	public function findByKeyword(string $keyword): array
	{
		return $this->createQueryBuilder('p')
			->innerJoin('p.user', 'u')
			->andWhere('(
            p.title LIKE :keyword OR
            p.description LIKE :keyword OR
            u.firstName LIKE :keyword OR
            u.lastName LIKE :keyword
        )')
			->setParameter('keyword', '%' . $keyword . '%')
			->getQuery()
			->getResult();
	}

//	public function findByKeyword(string $keyword): array
//	{
//		return $this->createQueryBuilder('p')
//			->andWhere('(
//            p.title LIKE :keyword OR
//            p.description LIKE :keyword
//        )')
//			->setParameter('keyword', '%' . $keyword . '%')
//			->getQuery()
//			->getResult();
//	}

}
