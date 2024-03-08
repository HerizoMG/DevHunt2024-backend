<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
	private EntityManagerInterface $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->entityManager = $em;
	}

	#[Route('/api/user/{id}', name: 'show.user', methods: ['GET'])]
	public function index(Request $request, ManagerRegistry $managerRegistry, int $id): JsonResponse
	{
		$user = $managerRegistry->getRepository(User::class)->find($id);
		$roles = [];

		if ($user->isIsAdmin()) {
			$roles[] = 'Admin';
		}
		if ($user->isIsEnseignant()) {
			$roles[] = 'Enseignant';
		}
		if ($user->isIsNovice()) {
			$roles[] = 'Novice';
		}
		if ($user->isIsAdministration()) {
			$roles[] = 'Admnistration';
		}
		if ($user->isIsEntreprise()) {
			$roles[] = 'Entreprise';
		}
		if ($user->isIsMateriel()) {
			$roles[] = 'Materiel';
		}
		if ($user->isIsElder()) {
			$roles[] = 'Elder';
		}
		if ($user->isIsImmobilier()) {
			$roles[] = 'Immobilier';
		}

		return $this->json([
			'user_id' => $user->getId(),
			'firstname' => $user->getFirstName(),
			'lastname' => $user->getLastName(),
			'username' => $user->getUserName(),
			'path' => $user->getPath(),
			'isAdmin' => $user->isIsAdmin(),
			'isNovice' => $user->isIsNovice(),
			'isEnseignant' => $user->isIsEnseignant(),
			'isAdministration' => $user->isIsAdministration(),
			'isEntreprise' => $user->isIsEntreprise(),
			'isMateriel' => $user->isIsMateriel(),
			'isElder' => $user->isIsElder(),
			'isImmobilier' => $user->isIsImmobilier(),
			'raisonSocial' => $user->getRaisonSocial(),
			'email' => $user->getEmail(),
			'role' => $roles,
		]);
	}

	#[Route('/api/updateInfo/{userId}', name: 'update.user', methods: ['PUT'])]
	public function updateInfoUser(Request $request, ManagerRegistry $managerRegistry, int $userId)
	{
		$requestData = json_decode($request->getContent(), true);
		$user = $managerRegistry->getRepository(User::class)->find($userId);

		$password = $requestData['password'];
		$path = $request->files->get('path');

		if (!$path) {
			return new JsonResponse(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
		}
		$newFilename = uniqid().'.'.$path->guessExtension();
		try {
			$path->move(
				$this->getParameter('pieceJointes'),
				$newFilename
			);
		} catch (FileException $e) {
			return new JsonResponse(['error' => 'Upload failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
		}
		$piecesJointesPath =$newFilename;

		$user->getPassword($password)
			->getPath($piecesJointesPath);

		$this->entityManager->persist($user);
		$this->entityManager->flush();
	}
}
