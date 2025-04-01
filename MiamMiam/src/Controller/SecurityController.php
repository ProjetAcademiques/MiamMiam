<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;



class SecurityController extends AbstractController
{
    private $tokenStorage;
    private $requestStack;

    public function __construct(TokenStorageInterface $tokenStorage, RequestStack $requestStack)
    {
        $this->tokenStorage = $tokenStorage;
        $this->requestStack = $requestStack;
    }
    #[Route(path: '/login', name: 'login_page')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last email entered by the user
        $lastEmail = $authenticationUtils->getLastUsername();
        if ($this->getUser()){
            return $this->redirectToRoute('app_liste_index');
        }

        return $this->render('security/login.html.twig', [
            'last_email' => $lastEmail,
            'error' => $error,
        ]);
    }


    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    #[Route('/loginForm', name: 'app_login_form', methods: ['POST'])]
    public function LoginForm(Request $request, EntityManagerInterface $entityManager): Response
    {
        $session = $this->requestStack->getSession();
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $utilisateur = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        
        $errorMessage = null;
        
        if (!$utilisateur) {
            $errorMessage = 'Email incorrect.';
        } elseif (!password_verify($password, $utilisateur->getPassword())) {
            $errorMessage = 'Mot de passe incorrect.';
        }
        
        if ($errorMessage) {
            // Return to login page with error message
            return $this->render('security/login.html.twig', [
                'last_email' => $email,
                'error_message' => $errorMessage,
            ]);
        }
        
        $token = new UsernamePasswordToken($utilisateur, 'main', $utilisateur->getRoles());
        $this->tokenStorage->setToken($token);
        $session->set('_security_main', serialize($token));
        return $this->redirectToRoute('app_liste_index');
    }
    
    #[Route('/')]
    public function redirectListe(): Response{
        return $this->redirectToRoute('login_page');
    }

}
