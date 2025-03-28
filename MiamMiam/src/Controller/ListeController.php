<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Entity\Article;
use App\Form\ListeType;
use App\Repository\ListeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/liste')]
#[Route('/')]
final class ListeController extends AbstractController
{
    #[Route(name: 'app_liste_index', methods: ['GET', 'POST'])]
    public function index(Request $request, ListeRepository $listeRepository, EntityManagerInterface $entityManager): Response
    {
        $liste = new Liste();
        $form = $this->createForm(ListeType::class, $liste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($liste);
            $entityManager->flush();

            return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
        }

        $listes = $listeRepository->findAll();
        $articlesParListe = [];

        foreach ($listes as $liste) {
            $articlesParListe[$liste->getId()] = $listeRepository->findArticlesByListeId($liste->getId());
        }

        return $this->render('liste/index.html.twig', [
            'listes' => $listes,
            'articlesParListe' => $articlesParListe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_liste_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $liste = new Liste();
        $form = $this->createForm(ListeType::class, $liste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($liste);
            $entityManager->flush();

            return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('liste/new.html.twig', [
            'liste' => $liste,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_liste_show', methods: ['GET'])]
    public function show(Liste $liste, EntityManagerInterface $entityManager): Response
    {
        $query = $entityManager->createQuery(
            'SELECT a, la.quantite, la.date_ajout
             FROM App\Entity\Article a
             JOIN App\Entity\ListeArticle la WITH la.article = a
             JOIN App\Entity\Liste l WITH la.liste = l
             WHERE l.id = :listeId'
        )->setParameter('listeId', $liste->getId());

        $articles = $query->getResult();

        return $this->render('liste/show.html.twig', [
            'liste' => $liste,
            'articles' => $articles
        ]);
    }

    #[Route('/{id}/edit', name: 'app_liste_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Liste $liste, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ListeType::class, $liste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('liste/edit.html.twig', [
            'liste' => $liste,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_liste_delete', methods: ['POST'])]
    public function delete(Request $request, Liste $liste, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$liste->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($liste);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
    }
}
