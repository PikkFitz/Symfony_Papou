<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    #[Route('/login', name: 'security.login', methods:['GET', 'POST'])]
     /**
     * This controller allows us to login
     *
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response  // AuthenticationUtils nécessaire pour last_username dans login.html.twig
    {
        // Récupérer l'erreur de la dernière tentative de connexion
        $error = $authenticationUtils->getLastAuthenticationError();

        // Récupérer le dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // Message personnalisé en cas d'erreur
        if ($error) {
            // !!!!! MESSAGE FLASH !!!!!
            $this->addFlash    // Nécessite un block "for message" dans le fichier .html.twig pour fonctionner
            (
                'danger',  // Nom de l'alerte 
                ['info' => 'Erreur', 'bonus' => "Adresse mail ou mot de passe invalide"]  // Message(s)
            );
        }

        return $this->render('pages/security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }


    #[Route('/logout', name: 'security.logout', methods:['GET', 'POST'])]
    /**
     * This controller allows us to logout
     *
     * @return void
     */
    public function logout()
    {
        // Nothing to do here...
    }


    #[Route('/inscription', name: 'security.registration', methods:['GET', 'POST'])]
    /**
     * This controller allows us to register
     *
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function registration(Request $request, EntityManagerInterface $manager) : Response
    {
        $customer = new Customer();
        $form = $this->createForm(RegistrationType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $customer = $form->getData();

            // !!!!! MESSAGE FLASH !!!!!
            $this->addFlash(
                'success',  // Nom de l'alerte 
                ['info' => 'Création du compte', 'bonus' => 'Le compte utilsateur de M./Mme "'. $customer->getFirstname() . ' ' . $customer->getLastname() .'" a bien été créé !']  // Message(s)
            );

            $manager->persist($customer);
            $manager->flush();

            return $this->redirectToRoute('security.login');
        }

        return $this->render('pages/security/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}