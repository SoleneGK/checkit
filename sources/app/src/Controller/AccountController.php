<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\User;
use App\Form\KnownPasswordModificationType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @IsGranted ("IS_AUTHENTICATED_REMEMBERED")
 */
class AccountController extends AbstractController
{
	/**
	 * @Route("/mon-compte", name="account")
	 */
	public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
	{
		$user = $this->getUser();
		$form = $this->createForm(KnownPasswordModificationType::class);
		$form->handleRequest($request);

		$success_message = "";

		if ($form->isSubmitted() && $form->isValid())
		{
			$user->setPassword(
				$passwordEncoder->encodePassword(
					$user,
					$form->get('password')->getData()
				)
			);

			$entityManager = $this->getDoctrine()->getManager();
			$entityManager->flush();

			$success_message = "Le mot de passe a été modifié.";
		}

		return $this->render('account/display.html.twig', [
			'controller_name' => 'AccountController',
			'form' => $form->createView(),
			'success_message' => $success_message,
		]);
	}
}
