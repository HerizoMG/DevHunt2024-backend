<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UploadExcelDataController extends AbstractController
{
	private EntityManagerInterface $em;

	private Generator $faker;

	public function __construct(EntityManagerInterface $em)
	{
		$this->entityManager = $em;
		$this->faker = Factory::create('fr_FR');
	}

	/**
	 * @Route("/upload-excel", name="xlsx")
	 * @param Request $request
	 * @throws \Exception
	 */
	#[Route('/api/upload/excel/data/student', name: 'student.data.excel', methods: ['POST'])]
	public function student(Request $request)
	{
		$file = $request->files->get('file');
		$path = $request->files->get('path');

		if (!$path) {
			return new JsonResponse(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
		}

		$newFilename = uniqid().'.'.$path->guessExtension();
		try {
			$path->move(
				$this->getParameter('image_directory'),
				$newFilename
			);
		} catch (FileException $e) {
			return new JsonResponse(['error' => 'Upload failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
		}
		$path = 'images/'.$newFilename;

		$fileFolder = __DIR__ . '/../../public/students/';
		$filePathName = md5(uniqid()) . $file->getClientOriginalName();
		try {
			$file->move($fileFolder, $filePathName);
		} catch (FileException $e) {
			dd($e);
		}
		$spreadsheet = IOFactory::load($fileFolder . $filePathName);
		$row = $spreadsheet->getActiveSheet()->removeRow(1);
		$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

		foreach ($sheetData as $Row)
		{
			$last_name = $Row['A'];
			$first_name = $Row['B'];
			$username= $Row['C'];

			$user_existant = $this->entityManager->getRepository(User::class)->findOneBy(array('username' => $username));
			if (!$user_existant)
			{
				$user = new User();
				$user->setFirstName($first_name);
				$user->setLastName($last_name);
				$user->setUsername($username);
				$user->setPath($path);
				$user->setIsAdmin(false);
				$user->setIsAdministration(false);
				$user->setIsElder(false);
				$user->setIsAdmin(false);
				$user->setIsEnseignant(false);
				$user->setIsEntreprise(false);
				$user->setIsImmobilier(false);
				$user->setIsMateriel(false);
				$user->setIsNovice(true);
				$user->setEmail($this->faker->email());
				$user->setPassword($this->faker->password());
				$this->entityManager->persist($user);
				$this->entityManager->flush();
			}
		}
		return $this->json('users registered', 200);
	}

	/**
	 * @Route("/upload-excel", name="xlsx")
	 * @param Request $request
	 * @throws \Exception
	 */
	#[Route('/api/upload/excel/data/enseignant', name: 'enseignant.data.excel', methods: ['POST'])]
	public function enseignant(Request $request)
	{
		$file = $request->files->get('file');
		$path = $request->files->get('path');

		if (!$path) {
			return new JsonResponse(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
		}

		$newFilename = uniqid().'.'.$path->guessExtension();
		try {
			$path->move(
				$this->getParameter('image_directory'),
				$newFilename
			);
		} catch (FileException $e) {
			return new JsonResponse(['error' => 'Upload failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
		}
		$path = 'images/'.$newFilename;

		$fileFolder = __DIR__ . '/../../public/enseignants/';

		$filePathName = md5(uniqid()) . $file->getClientOriginalName();
		try {
			$file->move($fileFolder, $filePathName);
		} catch (FileException $e) {
			dd($e);
		}
		$spreadsheet = IOFactory::load($fileFolder . $filePathName);
		$row = $spreadsheet->getActiveSheet()->removeRow(1);
		$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

		foreach ($sheetData as $Row)
		{

			$last_name = $Row['A'];
			$first_name = $Row['B'];
			$IM= $Row['C'];
			$email = $Row['D'];

			$user_existant = $this->entityManager->getRepository(User::class)->findOneBy(array('username' => $IM));
			if (!$user_existant)
			{
				$user = new User();
				$user->setFirstName($first_name);
				$user->setLastName($last_name);
				$user->setUsername($IM);
				$user->setPath($path);
				$user->setIsAdmin(false);
				$user->setIsAdministration(false);
				$user->setIsElder(false);
				$user->setIsAdmin(false);
				$user->setIsEnseignant(true);
				$user->setIsEntreprise(false);
				$user->setIsImmobilier(false);
				$user->setIsMateriel(false);
				$user->setIsNovice(false);
				$user->setEmail($email);
				$user->setPassword($this->faker->password());
				$this->entityManager->persist($user);
				$this->entityManager->flush();

			}
		}
		return $this->json('users registered');
	}

	#[Route('/api/upload/excel/data/entreprise', name: 'entreprise.data.excel', methods: ['POST'])]
	public function entreprise(Request $request)
	{
		$file = $request->files->get('file');
		$path = $request->files->get('path');

		if (!$path) {
			return new JsonResponse(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
		}

		$newFilename = uniqid().'.'.$path->guessExtension();
		try {
			$path->move(
				$this->getParameter('image_directory'),
				$newFilename
			);
		} catch (FileException $e) {
			return new JsonResponse(['error' => 'Upload failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
		}
		$path = 'images/'.$newFilename;

		$fileFolder = __DIR__ . '/../../public/entreprise/';

		$filePathName = md5(uniqid()) . $file->getClientOriginalName();
		try {
			$file->move($fileFolder, $filePathName);
		} catch (FileException $e) {
			dd($e);
		}
		$spreadsheet = IOFactory::load($fileFolder . $filePathName);
		$row = $spreadsheet->getActiveSheet()->removeRow(1);
		$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

		foreach ($sheetData as $Row)
		{

			$raisonSocial = $Row['A'];
			$email = $Row['B'];

			$user_existant = $this->entityManager->getRepository(User::class)->findOneBy(array('email' => $email));
			if (!$user_existant)
			{
				$user = new User();
				$user->setUsername($email);
				$user->setRaisonSocial($raisonSocial);
				$user->setPath($path);
				$user->setIsAdmin(false);
				$user->setIsAdministration(false);
				$user->setIsElder(false);
				$user->setIsAdmin(false);
				$user->setIsEnseignant(false);
				$user->setIsEntreprise(true);
				$user->setIsImmobilier(false);
				$user->setIsMateriel(false);
				$user->setIsNovice(false);
				$user->setEmail($email);
				$user->setPassword($this->faker->password());
				$this->entityManager->persist($user);
				$this->entityManager->flush();

			}
		}
		return $this->json('users registered');
	}
}
