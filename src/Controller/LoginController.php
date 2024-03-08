<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
	private EntityManagerInterface $em;
	public function __construct(EntityManagerInterface $em)
	{
		$this->entityManager = $em;
	}
	#[Route('/api/login', name: 'api_login', methods: ['POST'])]
	public function login(Request $request): JsonResponse
	{
		$data = json_decode($request->getContent(), true);
		$username = $data['username'] ?? null;
		$password = $data['password'] ?? null;
		$userRepository = $this->entityManager->getRepository(User::class);
		$user = $userRepository->findOneBy(['username' => $username,'password' => $password]);
		if (!$user) {
			return new JsonResponse(['error' => 'Invalid username or password'], Response::HTTP_BAD_REQUEST);
		}
		else{
			return new JsonResponse([
				'id' => $user->getId(),
				'firstname' => $user->getFirstName(),
				'lastname' => $user->getLastName(),
				'username' => $user->getUserName(),
				'path' => $user->getPath(),
				'isAdmin' => $user->isIsAdmin(),
				'isNovice' => $user->isIsNovice(),
				'isEnseignanet' => $user->isIsEnseignant(),
				'isIsAdministration' => $user->isIsAdministration(),
				'isIsEntreprise' => $user->isIsEntreprise(),
				'isIsMateriel' => $user->isIsMateriel(),
				'isIsElder' => $user->isIsElder(),
				'isIsImmobilier' => $user->isIsImmobilier(),
				'raisonSocial' => $user->getRaisonSocial(),
				'email' => $user->getEmail(),

			], Response::HTTP_OK);
		}
	}
}
