<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Password\PasswordHasherInterface;

#[Route('/utilisateur')]
final class UtilisateurController extends AbstractController
{
    #[Route(name: 'app_utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('utilisateur/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_utilisateur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($utilisateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/new.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('utilisateur/show.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_utilisateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('utilisateur/edit.html.twig', [
            'utilisateur' => $utilisateur,
            'form' => $form,
        ]);
    }

    #[Route('/{id<\d+>}', name: 'app_utilisateur_delete', methods: ['POST'])]
    public function delete(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($utilisateur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_utilisateur_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/saveForm', name: 'app_save_form', methods: ['POST'])]
    public function saveForm1(Request $request):Response
    {
        $session = $request->getSession();
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('passwordConfirm');
        if ($password !== $confirmPassword){
            return new Response('Le mot de passe et la confirmation doivent être les mêmes',400);
        }
        if (!$email || !$password) {
            return new Response('Email et mot de passe requis.',400);

        }
        $session->set('email', $email);
        $session->set('password', $password);
        $session->save();
        return $this->redirectToRoute('register_page');

}

    #[Route('/registerForm', name: 'app_register_form', methods: ['POST','GET'])]
    
    public function registerForm(Request $request,EntityManagerInterface $entityManager):Response
    {
        $session = $request->getSession();
        if (!$session->has('email') || !$session->has('password')) {
            return new Response('Les informations sont manquantes.Veuillez recommencer.',400);
        }
        $email = $session->get('email');
        $password = $session->get('password');
        $pseudo = $request->request->get('pseudonyme');
        if (!$pseudo) {
            return new Response('Pseudo requis.',400);
        }
        $utilisateur = new Utilisateur();
        $utilisateur->setPseudo($pseudo);
        $utilisateur->setAdressemail($email);
        $hashedPassword = password_hash($password,PASSWORD_BCRYPT);
        $utilisateur->setMDP($hashedPassword);
        $utilisateur->setDateDeCreation(new \DateTime());
        $entityManager->persist($utilisateur);
        $entityManager->flush();
        $session->remove('email');
        $session->remove('password');
        return $this->redirectToRoute('home_page');
}
#[Route('/loginForm', name: 'app_login_form', methods: ['POST'])]
public function LoginForm(Request $request,EntityManagerInterface $entityManager):Response{
    $email = $request->request->get('email');
    $password = $request->request->get('password');
    $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['adressemail' => $email]);
    if (!$utilisateur) {
        return new Response('Email incorrect.',401);
    }
    if (!password_verify($password, $utilisateur->getMDP())) {
        return new Response('Mot de passe incorrect.', 401);
    }
    return $this->redirectToRoute('app_register_form');

}
}