<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikerController extends AbstractController
{
	private EntityManagerInterface $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

	#[Route('/api/like', name: 'create.like', methods:['POST'])]
	public function createLike(Request $request): JsonResponse
	{
		$data = json_decode($request->getContent(), true);
		$postId = $data['post_id'] ?? null;
		$userId = $data['user_id'] ?? null;

		$post = $this->entityManager->getRepository(Post::class)->find($postId);
		$user = $this->entityManager->getRepository(User::class)->find($userId);

		if ($post && $user) {
			$existingLike = $this->entityManager->getRepository(Like::class)->findOneBy(['post' => $post, 'user' => $user]);
			if ($existingLike) {
				$this->entityManager->remove($existingLike);
				$this->entityManager->flush();
				return new JsonResponse(['message' => 'Like removed successfully'], Response::HTTP_OK);
			} else {
				$like = new Like();
				$like->setPost($post);
				$like->setUser($user);

				$this->entityManager->persist($like);
				$this->entityManager->flush();
				return new JsonResponse(['message' => 'Like added successfully'], Response::HTTP_CREATED);
			}
		}
		return new JsonResponse(['message' => 'Post or User not found'], Response::HTTP_NOT_FOUND);
	}

}
