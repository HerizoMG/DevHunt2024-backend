<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvatarController extends AbstractController
{
	#[Route('/api/avatar/{pathImage}', name: 'api_avatar', methods: ['GET'])]
	public function getAvatar(string $pathImage): Response
	{
		$avatarPath = $this->getParameter('kernel.project_dir') . '/public/avatar/' . $pathImage;
		if (!file_exists($avatarPath)) {
			throw $this->createNotFoundException('image not found');
		}
		return new BinaryFileResponse($avatarPath);
	}
}
