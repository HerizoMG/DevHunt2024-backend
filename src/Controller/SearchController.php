<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SearchController extends AbstractController
{
    #[Route('/api/search', name: 'search', methods: ['GET'])]
	public function search(Request $request, ManagerRegistry $managerRegistry): JsonResponse
	{
		$keyword = $request->query->get('title');
		if (!$keyword) {
			return new JsonResponse(['error' => 'Missing search query parameter'], Response::HTTP_BAD_REQUEST);
		}

		$repository = $managerRegistry->getRepository(Post::class);

		$posts = $repository->createQueryBuilder('p')
			->andWhere('p.title LIKE :keyword')
			->setParameter('keyword', '%'.$keyword.'%')
			->getQuery()
			->getResult();

		$responseData = [];
		foreach ($posts as $post) {
			$postData = [
				'id' => $post->getId(),
				'title' => $post->getTitle(),
				'type' => $post->getType(),
				'description' => $post->getDescription(),
				'isEpingle' => $post->isIsEpingle(),
				'user' => [
					'id' => $post->getUser()->getId(),
					'firstname' => $post->getUser()->getFirstName(),
				],
			];
			$responseData[] = $postData;
		}
		return new JsonResponse($responseData, Response::HTTP_OK);
	}
}
