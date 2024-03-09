<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\PiecesJointes;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
	private EntityManagerInterface $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->entityManager = $em;
	}
	#[Route('/api/post', name: 'show.post', methods:['GET'])]
	public function show(): JsonResponse
	{
		$repository = $this->entityManager->getRepository(Post::class);
		$posts = $repository->findBy([], ['createdAt' => 'DESC']);
		$data = [];
		foreach($posts as $post) {
			$postData = [
				'id' => $post->getId(),
				'title' => $post->getTitle(),
				'type' => $post->getType(),
				'description' => $post->getDescription(),
				'isEpingle' => $post->isIsEpingle(),
				'createdAt' => $post->getCreatedAt(),
				'user' => [],
				'piecesJointe' => [],
				'like' => [],
				'comments' =>[],
			];

			$commentData = [];
			foreach ($post->getComments() as $comment) {
				$commentData = [
					'id' => $comment->getId(),
					'designation' => $comment->getDesignation(),
					'piecesJointeComment' => [],
					'userComment' => [
						'id' => $comment->getUser()->getId(),
						'firstname' => $comment->getUser()->getFirstName(),
						'lastname' => $comment->getUser()->getLastName(),
						'username' =>$comment->getUser()->getUserName(),
						'path' => $comment->getUser()->getPath(),
						'raisonSocial' => $comment->getUser()->getRaisonSocial(),
						'email' => $comment->getUser()->getEmail(),
						'isAdmin' => $comment->getUser()->isIsAdmin(),
						'isNovice' => $comment->getUser()->isIsNovice(),
						'isEnseignant' => $comment->getUser()->isIsEnseignant(),
						'isAdministration' => $comment->getUser()->isIsAdministration(),
						'isEntreprise' => $comment->getUser()->isIsEntreprise(),
						'isMateriel' => $comment->getUser()->isIsMateriel(),
						'isElder' => $comment->getUser()->isIsElder(),
						'isImmobilier' => $comment->getUser()->isIsImmobilier(),
						'role' => []
					],
				];
				if ($comment->getUser()->isIsAdmin()) {
					$commentData['userComment']['role'][] = 'Admin';
				}
				if ($comment->getUser()->isIsNovice()) {
					$commentData['userComment']['role'][] = 'Novice';
				}
				if ($comment->getUser()->isIsEnseignant()) {
					$commentData['userComment']['role'][] = 'Enseignant';
				}
				if ($comment->getUser()->isIsAdministration()) {
					$commentData['userComment']['role'][] = 'Administration';
				}
				if ($comment->getUser()->isIsElder()) {
					$commentData['userComment']['role'][] = 'Elder';
				}
				if ($comment->getUser()->isIsEntreprise()) {
					$commentData['userComment']['role'][] = 'Entreprise';
				}
				if ($comment->getUser()->isIsImmobilier()) {
					$commentData['userComment']['role'][] = 'Immobilier';
				}
				if ($comment->getUser()->isIsMateriel()) {
					$commentData['userComment']['role'][] = 'Materiel';
				}
				$postData['comments'][] = $commentData;

				foreach ($comment->getPiecesJointes() as $piecesJointe) {
					$pjData = [
						'id' =>$piecesJointe->getId(),
						'piecesJointe'=>$piecesJointe->getLink()
					];
					$commentData['piecesJointeComment'][] = $pjData;
				}

				$postData['comments'][] = $commentData;
			}


			$likeData = [];
			$like = $post->getLiker();
			foreach ($like as $liker)
			{
				$likeData = [
					'post_id' =>$liker->getPost()->getId(),
					'userd_id' =>$liker->getUser()->getId(),
				];
				$postData['like'] = $likeData;
			}
			$piecesJointe = $post->getPiecesJointes();
			foreach ($piecesJointe as $pj)
			{
				$pjData = [
					'piecesJointe' => $pj->getLink(),
				];
				$postData['piecesJointe'][] = $pjData;
			}

			$user = $post->getUser();
			if ($user) {
				$userData = [
					'id' => $user->getId(),
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
					'role' => []
				];
				if ($user->isIsAdmin()) {
					$userData['role'][] = 'Admin';
				}
				if ($user->isIsNovice()) {
					$userData['role'][] = 'Novice';
				}
				if ($user->isIsEnseignant()) {
					$userData['role'][] = 'Enseignant';
				}
				if ($user->isIsAdministration()) {
					$userData['role'][] = 'Administration';
				}
				if ($user->isIsElder()) {
					$userData['role'][] = 'Elder';
				}
				if ($user->isIsEntreprise()) {
					$userData['role'][] = 'Entreprise';
				}
				if ($user->isIsImmobilier()) {
					$userData['role'][] = 'Immobilier';
				}
				if ($user->isIsMateriel()) {
					$userData['role'][] = 'Materiel';
				}
				$postData['user'] = $userData;
			}
			$data[] = $postData;
		}
		return new JsonResponse($data, Response::HTTP_OK);
	}

	#[Route('/api/post', name: 'create.post', methods: ['POST'])]
	public function create(Request $request, ManagerRegistry $managerRegistry, EntityManagerInterface $entityManager): JsonResponse
	{
		$jsonData = json_decode($request->request->get('json_data'), true);

		$type = $jsonData['type'] ?? null;
		$title = $jsonData['title'] ?? null;
		$description = $jsonData['description'] ?? null;
		$isEpingle = $jsonData['isEpingle'] ?? false;
		$id = $jsonData['user_id'] ?? 1;

		$piecesJointesFile = $request->files->get('piecesJointe');
		if (!$piecesJointesFile) {
			return new JsonResponse(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
		}

		$newFilename = uniqid() . '.' . $piecesJointesFile->guessExtension();
		try {
			$piecesJointesFile->move(
				$this->getParameter('pieceJointes'),
				$newFilename
			);
		} catch (FileException $e) {
			return new JsonResponse(['error' => 'Upload failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
		}
		$piecesJointesPath = $newFilename;

		$user = $managerRegistry->getRepository(User::class)->find($id);

		if (!$user) {
			return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
		}

		$piecesJointesEntity = new PiecesJointes();
		$piecesJointesEntity->setLink($piecesJointesPath);

		$post = new Post();
		$post->setType($type);
		$post->setTitle($title);
		$post->setDescription($description);
		$post->setIsEpingle($isEpingle);
		$post->setUser($user);

		$post->addPiecesJointe($piecesJointesEntity);

		$entityManager->persist($post);
		$entityManager->flush();

		return $this->json([
			'title' => $post->getTitle(),
			'type' => $post->getType(),
			'description' => $post->getDescription(),
			'isEpingle' => $post->isIsEpingle(),
			'piecesJointe' => $post->getPiecesJointes()
		]);
	}


	#[Route('/api/post/{id}', name: 'update.post', methods: ['PUT'])]
	public function update(Request $request, ManagerRegistry $managerRegistry ,int $id): JsonResponse
	{
		$jsonData = json_decode($request->request->get('json_data'), true);

		$piecesJointesFile = $request->files->get('piecesJointes');
		if (!$piecesJointesFile) {
			return new JsonResponse(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
		}
		$newFilename = uniqid().'.'.$piecesJointesFile->guessExtension();
		try {
			$piecesJointesFile->move(
				$this->getParameter('pieceJointes'),
				$newFilename
			);
		} catch (FileException $e) {
			return new JsonResponse(['error' => 'Upload failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
		}
		$piecesJointesPath = $newFilename;
		$piecesJointesEntity = new PiecesJointes();
		$piecesJointesEntity->setLink($piecesJointesPath);

		$type = $jsonData['type'] ?? null;
		$title = $jsonData['title'] ?? null;
		$description = $jsonData['description'] ?? null;
		$isEpingle = $jsonData['isEpingle'] ?? false;

		$post = $managerRegistry->getRepository(Post::class)->find($id);

		$post->setType($type)
			->setTitle($title)
			->setDescription($description)
			->setIsEpingle($isEpingle)
			->addPiecesJointe($piecesJointesEntity);

		$this->entityManager->flush();
		return $this->json([
			'id' => $id,
			'message' => 'post updated successfully'
		], Response::HTTP_OK);
	}

	#[Route('/api/post/{id}', name: 'get.post', methods: ['GET'])]
	public function getOnePost(Request $request, ManagerRegistry $managerRegistry ,int $id): JsonResponse
	{
		$post = $managerRegistry->getRepository(Post::class)->find($id);
		$postData = [
			'id' => $post->getId(),
			'title' => $post->getTitle(),
			'type' => $post->getType(),
			'description' => $post->getDescription(),
			'isEpingle' => $post->isIsEpingle(),
			'createdAt' => $post->getCreatedAt(),
			'user' => [],
			'piecesJointe' => [],
			'like' => [],
			'comments' =>[],
		];
		$like = $post->getLiker();
		foreach ($like as $liker)
		{
			$likeData = [
				'post_id' =>$liker->getPost()->getId(),
				'userd_id' =>$liker->getUser()->getId(),
			];
			$postData['like'][] = $likeData;
		}

		$piecesJointe = $post->getPiecesJointes();
		foreach ($piecesJointe as $pj)
		{
			$pjData = [
				'piecesJointe' => $pj->getLink(),
			];
			$postData['piecesJointe'][] = $pjData;
		}

		$user = $post->getUser();
		if ($user) {
			$userData = [
				'id' => $user->getId(),
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
				'role' => []
			];
			if ($user->isIsAdmin()) {
				$userData['role'][] = 'Admin';
			}
			if ($user->isIsNovice()) {
				$userData['role'][] = 'Novice';
			}
			if ($user->isIsEnseignant()) {
				$userData['role'][] = 'Enseignant';
			}
			if ($user->isIsAdministration()) {
				$userData['role'][] = 'Administration';
			}
			if ($user->isIsElder()) {
				$userData['role'][] = 'Elder';
			}
			if ($user->isIsEntreprise()) {
				$userData['role'][] = 'Entreprise';
			}
			if ($user->isIsImmobilier()) {
				$userData['role'][] = 'Immobilier';
			}
			if ($user->isIsMateriel()) {
				$userData['role'][] = 'Materiel';
			}
			$postData['user'] = $userData;
		}

		$comments = $post->getComments();
		foreach ($comments as $comment) {
			$commentData = [
				'appreciation' => $comment->getAppreciation(),
				'designation' => $comment->getDesignation()
			];
			$postData['comments'][] = $commentData;
		}
		$data[] = $postData;
		return $this->json($data);
	}

	#[Route('/api/post/{id}', name:'delete.post', methods:['DELETE'])]
	public function delete(int $id , ManagerRegistry $managerRegistry) :JsonResponse
	{
		$post = $managerRegistry->getRepository(Post::class)->find($id);
		$this->entityManager->remove($post);
		$this->entityManager->flush();
		return $this->json([
			'id' => $id,
			'message' => 'post deleted successfully'
		], Response::HTTP_OK);
	}

	public function CountLike($postId): int
	{
		$query = $this->entityManager->createQuery(
			'SELECT COUNT(l.id) 
            FROM App\Entity\Like l 
            WHERE l.post = :postId'
		)->setParameter('postId', $postId);

		$likeCount = $query->getSingleScalarResult();

		return $likeCount;
	}
}
