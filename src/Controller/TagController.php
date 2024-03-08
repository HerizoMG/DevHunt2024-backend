<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Tag;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TagController extends AbstractController
{
	private EntityManagerInterface $em;
	public function __construct(EntityManagerInterface $em)
	{
		$this->entityManager = $em;
	}
	#[Route('/api/tag', name: 'show.tag', methods: ['GET'])]
	public function show(): JsonResponse
	{
		$repository = $this->entityManager->getRepository(Tag::class);
		$tags = $repository->findAll();
		$data = [];
		foreach($tags as $tag)
		{
			$postData = [
				'id' => $tag->getId(),
				'designation' => $tag->getDesignation(),
			];
			$data[] = $postData;
		}
		return $this->json([$data], Response::HTTP_OK);
	}

	#[Route('/api/tag/create', name: 'create.tag', methods:['POST'])]
	public function create(Request $request): JsonResponse
	{
		$data = json_decode($request->getContent(), true);
		$tag = new Tag();
		$tag->setDesignation($data['designation']);
		$this->entityManager->persist($tag);
		$this->entityManager->flush();
		return $this->json([
			'message' => 'tag créé avec succès',
			'utilisateur' => [
				'iduser' => $tag->getId(),
				'designation' => $tag->getDesignation()
			]
		], Response::HTTP_CREATED);
	}

	#[Route('/api/tag/update/{id}', name : 'update.tag', methods:['PUT'])]
	public function update(Request $request, ManagerRegistry $managerRegistry ,int $id): JsonResponse
	{
		$jsonData = json_decode($request->getContent(),true);
		$designation = $jsonData['designation'];
		$tag = $managerRegistry->getRepository(Tag::class)->find($id);
		$tag->setDesignation($designation);
		$this->entityManager->flush();
		return $this->json([
			'id' => $id,
			'message' => 'tag updated successfully'
		], Response::HTTP_OK);
	}

	#[Route('/api/tag/delete/{id}', name:'delete.tag', methods:['DELETE'])]
	public function delete(int $id , ManagerRegistry $managerRegistry) :JsonResponse
	{
		$tag = $managerRegistry->getRepository(tag::class)->find($id);
		$this->entityManager->remove($tag);
		$this->entityManager->flush();
		return $this->json([
			'id' => $id,
			'message' => 'tag deleted successfully'
		], Response::HTTP_OK);
	}
}
