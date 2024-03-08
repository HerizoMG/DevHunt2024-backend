<?php

namespace App\Controller;


use App\Entity\PiecesJointes;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class PiecesJointesController extends AbstractController
{
    #[Route('/api/pieces/jointes', name: 'create.pieces_jointes')]
    public function index(Request $request): JsonResponse
    {
		$file = $request->files->get('file');

		$piecesJointes = new PiecesJointes();
		$piecesJointes->setLink();

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PiecesJointesController.php',
        ]);
    }


}
