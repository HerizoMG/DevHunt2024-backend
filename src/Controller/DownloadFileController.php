<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\PiecesJointes;
use Exception;

class DownloadFileController extends AbstractController
{
	#[Route('/api/download/file/{id}', name: 'download_file', methods: ['GET'])]
	public function downloadAction($id, ManagerRegistry $managerRegistry)
	{
		try {
			$file = $managerRegistry->getRepository(PiecesJointes::class)->findOneBy(['link' => $id]);
			if (!$file) {
				$array = [
					'status' => 0,
					'message' => 'File does not exist'
				];
				$response = new JsonResponse($array, 404);
				return $response;
			}
			$fileName = $file->getLink();
			$fileWithPath = $this->getParameter('pieceJointes') . "/" . $fileName;

			$response = new BinaryFileResponse($fileWithPath);

			// Ajouter des en-têtes pour forcer le téléchargement et définir le nom du fichier
			$response->setContentDisposition(
				ResponseHeaderBag::DISPOSITION_ATTACHMENT,
				$fileName
			);

			return $response;
		} catch (Exception $e) {
			$array = [
				'status' => 0,
				'message' => 'Download error'
			];
			$response = new JsonResponse($array, 400);
			return $response;
		}
	}
}

