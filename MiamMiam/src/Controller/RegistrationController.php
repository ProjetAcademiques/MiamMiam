<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class RegistrationController extends AbstractController
{
    private $tokenStorage;
    private $requestStack;

    public function __construct(TokenStorageInterface $tokenStorage, RequestStack $requestStack)
    {
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
    }


    #[Route('/registerForm', name: 'app_register')]
    public function registerForm(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $this->requestStack->getSession();
        $email = $session->get('email');
        $password = $session->get('password');
        $pseudo = $request->request->get('pseudonyme');
        
        if (!$pseudo) {
            // Return to register page with error message
            return $this->render('registration/register.html.twig', [
                'error_message' => 'Pseudo requis.'
            ]);
        }
        
        $utilisateur = new User();
        $utilisateur->setPseudo($pseudo);
        $utilisateur->setEmail($email);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $utilisateur->setPassword($hashedPassword);
        $utilisateur->setDateCreation(new \DateTime());
        $utilisateur->setIsAdmin(false);
        $utilisateur->setRoles([]);
        $entityManager->persist($utilisateur);
        $entityManager->flush();
        $session->remove('email');
        $session->remove('password');

        $token = new UsernamePasswordToken($utilisateur, 'main', $utilisateur->getRoles());
        $this->tokenStorage->setToken($token);
        $session->set('_security_main', serialize($token));

        return $this->redirectToRoute('app_liste_index');
    }

    #[Route('/pastregister', name: 'app_past_register')]
    public function RedirectPastregister(): Response{
        if ($this->getUser()){
            return $this->redirectToRoute('app_liste_index');
        }
        return $this->render('registration/pastregister.html.twig');
    }


    #[Route('/register', name: 'app_register_form')]
    public function Redirectregister(): Response{
        return $this->render('registration/register.html.twig');
    }
   

    #[Route('/saveForm', name: 'app_save_form', methods: ['POST'])]
    public function saveForm1(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $request->getSession();
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('passwordConfirm');
        
        $errorMessage = null;
        
        $utilisateur = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($utilisateur) {
            $errorMessage = 'Adresse-Mail déjà existante';
        } elseif ($password !== $confirmPassword) {
            $errorMessage = 'Le mot de passe et la confirmation doivent être les mêmes';
        } elseif (!$email || !$password) {
            $errorMessage = 'Email et mot de passe requis.';
        }
        
        if ($errorMessage) {
            // Return to pastregister page with error message
            return $this->render('registration/pastregister.html.twig', [
                'error_message' => $errorMessage,
                'last_email' => $email
            ]);
        }
        
        $session->set('email', $email);
        $session->set('password', $password);
        $session->save();
        
        return $this->redirectToRoute('app_register_form');
    }
}
