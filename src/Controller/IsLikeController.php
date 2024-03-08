<?php

namespace App\Controller;

use App\Entity\Like;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IsLikeController extends AbstractController
{
    #[Route('/api/islike/{postId}/{userId}', name: 'islike', methods: ['GET'])]
    public function index(Request $request , ManagerRegistry $managerRegistry , int $postId, int $userId): JsonResponse
    {
		$isLike = $managerRegistry->getRepository(Like::class)->findOneBy(['post'=>$postId,'user'=>$userId]);
		if ($isLike)
		{
			return new JsonResponse(['message'=>'post liked'],Response::HTTP_OK);
		}else{
			return $this->json([Response::HTTP_NOT_FOUND]);
		}

    }
}
