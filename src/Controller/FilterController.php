<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FilterController extends AbstractController
{
    #[Route('/api/filter/{role}', name: 'filer', methods: ['GET'])]
    public function index(Request $request, ManagerRegistry $managerRegistry, string $role): JsonResponse
    {

		$validRoles = ['isNovice', 'isEnseignant', 'isAdministration', 'isImmobilier', 'isEntreprise', 'isElder'];

		if (!in_array($role, $validRoles)) {
			return new JsonResponse(['error' => 'Invalid role'], Response::HTTP_BAD_REQUEST);
		}

		$posts = $managerRegistry->getRepository(Post::class)->findByUserRole($role);

		$postData = [];
		foreach ($posts as $post) {
			$postData[] = [
				'id' => $post->getId(),
				'title' => $post->getTitle(),
				'description' => $post->getDescription(),
				// Ajoutez d'autres champs si nécessaire
			];
		}

		// Retourner les posts filtrés en JSON
		return new JsonResponse($postData, Response::HTTP_OK);
    }
}
