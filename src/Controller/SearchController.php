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


	#[Route('/api/filter/{role}/{keyword}', name: 'filter', methods: ['GET'])]
	public function index(Request $request, ManagerRegistry $managerRegistry, string $role, string $keyword): JsonResponse
	{
		$validRoles = ['isNovice', 'isEnseignant', 'isAdministration', 'isImmobilier', 'isEntreprise', 'isElder', 'isClub' , 'isMateriel', 'isAll'];

		if (!in_array($role, $validRoles)) {
			return new JsonResponse(['error' => 'Invalid role'], Response::HTTP_BAD_REQUEST);
		}

		if ($role === 'isAll') {
			// Si le rôle est "isAll", on récupère tous les posts sans filtrer par rôle
			$posts = $managerRegistry->getRepository(Post::class)->findByKeyword($keyword);
		} else {
			// Sinon, on récupère les posts en filtrant par le rôle spécifié
			$posts = $managerRegistry->getRepository(Post::class)->findByUserRoleAndKeyword($role, $keyword);
		}

		if (empty($posts)) {
			return new JsonResponse(['message' => 'No posts found for the role ' . $role . ' with the keyword ' . $keyword], Response::HTTP_OK);
		}

		$responseData = [];
		foreach ($posts as $post) {
			$postData[] = [
				'id' => $post->getId(),
				'title' => $post->getTitle(),
				'description' => $post->getDescription(),
				'user' => [
					'id' => $post->getUser()->getId(),
					'firstname' => $post->getUser()->getFirstName(),
					'lastname' => $post->getUser()->getLastName(),
					'role' => $role,
				],
			];
		}
		return new JsonResponse($postData, Response::HTTP_OK);
	}



}
