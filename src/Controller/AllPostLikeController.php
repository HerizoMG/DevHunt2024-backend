<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Post;
use Doctrine\ORM\Query\AST\WhereClause;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class AllPostLikeController extends AbstractController
{
    #[Route('/api/allPostLikeBy/{userId}', name: 'all_post_like', methods: ['GET'])]
    public function index(Request $request,ManagerRegistry $managerRegistry, int $userId): JsonResponse
    {
		$postLiked = $managerRegistry->getRepository(Like::class)->createQueryBuilder('l')
			->andWhere('l.user = :id')
			->setParameter('id', $userId)
			->getQuery()
			->getResult();

		$data = [];

		foreach ($postLiked as $postlike)
		{
			$post = $managerRegistry->getRepository(Post::class)->find($postlike->getId());
			$postDataLiked = [
				'id' => $post->getId(),
				'title' => $post->getTitle(),
				'type' => $post->getType(),
				'description' => $post->getDescription(),
				'piecesJointe' => []
			];

			$piecesJointe = $post->getPiecesJointes();
			foreach ($piecesJointe as $pj)
			{
				$pjData = [
					'piecesJointe' => $pj->getLink(),
				];
				$postDataLiked['piecesJointe'][] = $pjData;
			}
			$data[] =$postDataLiked;
		}
        return $this->json([$data]);
    }
}
