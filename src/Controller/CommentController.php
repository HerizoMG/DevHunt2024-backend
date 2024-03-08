<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\PiecesJointes;;

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

class CommentController extends AbstractController
{
	private EntityManagerInterface $em;
	public function __construct(EntityManagerInterface $em)
	{
		$this->entityManager = $em;
	}
//	#[Route('/api/comment', name: 'show.comment', methods: ['GET'])]
//	public function show(): JsonResponse
//	{
//		$repository = $this->entityManager->getRepository(Comment::class);
//		$comments = $repository->findAll();
//		$data = [];
//
//		foreach ($comments as $comment) {
//			$commentData = [
//				'id' => $comment->getId(),
//				'appreciation' => $comment->getAppreciation(),
//				'designation' => $comment->getDesignation(),
//				'user' => []
//			];
//
//			$user = $comment->getUser();
//				foreach ($user as $users) {
//					$userData = [
//						'id' => $users->getId(),
//						'firstname' => $users->getFirstName(),
//						'lastname' => $users->getLastName(),
//						'username' => $users->getUserName(),
//						'raisonSocial' => $users->getRaisonSocial(),
//						'email' => $users->getEmail(),
//					];
//					$commentData['user'][] = $userData;
//				}
//
//			$data[] = $commentData;
//		}
//
//		return $this->json($data, Response::HTTP_OK);
//	}
	#[Route('/api/comment', name: 'create.comment', methods: ['POST'])]
	public function create(Request $request, ManagerRegistry $managerRegistry): JsonResponse
	{
		$jsonData = json_decode($request->request->get('json_data'), true);
		$designation = $jsonData['designation'] ?? null;
		$piecesJointesFile = $request->files->get('pieceJointes');
		$userId = $jsonData['user_id'] ?? 1;
		$postId = $jsonData['post_id'] ?? 1;

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

		$user = $managerRegistry->getRepository(User::class)->find($userId);
		$post = $managerRegistry->getRepository(Post::class)->find($postId);

		$piecesJointesEntity = new PiecesJointes();
		$piecesJointesEntity->setLink($piecesJointesPath);

		$comment = new Comment();
		$comment->setDesignation($designation);
		$comment->addPiecesJointe($piecesJointesEntity);
		$comment->setUser($user);
		$comment->setPost($post);
		$this->entityManager->persist($comment);
		$this->entityManager->flush();

		return $this->json([
			'designation' => $comment->getDesignation(),
			'piecesJointe' => $comment->getPiecesJointes(),
			'createdAt' => $comment->getCreatedAt()
		]);
	}

//	#[Route('/api/comment/{id}', name: 'update.comment', methods: ['PUT'])]
//	public function update(Request $request, ManagerRegistry $managerRegistry ,int $id): JsonResponse
//	{
//		$jsonData = json_decode($request->request->get('json_data'), true);
//		$piecesJointesFile = $request->files->get('piecesJointe');
//		if (!$piecesJointesFile) {
//			return new JsonResponse(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
//		}
//		$newFilename = uniqid().'.'.$piecesJointesFile->guessExtension();
//		try {
//			$piecesJointesFile->move(
//				$this->getParameter('pieceJointes'),
//				$newFilename
//			);
//		} catch (FileException $e) {
//			return new JsonResponse(['error' => 'Upload failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
//		}
//		$piecesJointesPath = $newFilename;
//
//		$designation = $jsonData['designation'];
//		$post = $managerRegistry->getRepository(Comment::class)->find($id);
//		$piecesJointesEntity = new PiecesJointes();
//		$piecesJointesEntity->setLink($piecesJointesPath);
//		$post->setDesignation($designation)
//			->addPiecesJointe($piecesJointesEntity);
//		$this->entityManager->flush();
//		return $this->json([
//			'id' => $id,
//			'message' => 'comment updated successfully'
//		], Response::HTTP_OK);
//	}

//	#[Route('/api/comment/{id}', name:'delete.comment', methods:['DELETE'])]
//	public function delete(int $id , ManagerRegistry $managerRegistry) :JsonResponse
//	{
//		$comment = $managerRegistry->getRepository(Comment::class)->find($id);
//		$this->entityManager->remove($comment);
//		$this->entityManager->flush();
//		return $this->json([
//			'id' => $id,
//			'message' => 'comment deleted successfully'
//		], Response::HTTP_OK);
//	}
}
