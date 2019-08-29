<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CheckItController extends AbstractController
{  
    /**
    * @Route("/", name="index")
    */
    public function printLoginPage()
    {
        // Est-ce qu'il y a eu une tentative de connexion échouée ?
        $login_error = true;

        if ($login_error == true) {
            $error_message = "Identifiants incorrects";

            return $this->render('logout/login.html.twig', [
                'error_message' => $error_message
            ]);
        }
        else {
            return $this->render('logout/login.html.twig');
        }
    }

    /**
     * @Route("/creer-compte", name="account_creation")
     */
    public function printAccountCreationPage() {
        // Regarder s'il y a des données envoyées par le formulaire
        $form_submitted = true;

        if ($form_submitted == false) {
            // Essayer de créer un compte
            $account_created = true;

            if ($account_created == true) {
                return $this->render('logout/account_created.html.twig');
            }
            else {
                $error_message = "Erreur lors de la création du compte";
                $mail_submitted = "bidule@gmail.com";

                return $this->render('logout/account_creation.html.twig', [
                    'error_message' => $error_message,
                    'mail_submitted' => $mail_submitted
                ]);
            }
        }
        else {
            return $this->render('logout/account_creation.html.twig', [
                'mail_submitted' => ""
            ]);
        }
    }
}
