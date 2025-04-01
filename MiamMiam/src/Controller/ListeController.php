<?php

namespace App\Controller;

use App\Entity\Liste;
use App\Entity\ListeArticle;
use App\Form\ListeType;
use App\Form\ListeArticleType;
use App\Repository\ListeRepository;
use App\Repository\ListeArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/liste')]
final class ListeController extends AbstractController
{
    #[Route(name: 'app_liste_index', methods: ['GET', 'POST'])]
    public function index(Request $request, ListeRepository $listeRepository, EntityManagerInterface $entityManager, UserInterface $user, ListeArticleRepository $listeArticleRepository): Response
    {
        $liste = new Liste();
        $form = $this->createForm(ListeType::class, $liste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $liste->addUser($this->getUser());
            $entityManager->persist($liste);
            $entityManager->flush();

            return $this->redirectToRoute('app_liste_show', ['id' => $liste->getId()]);
        }

        $listes = $listeRepository->findListesByUser($user);

        $articlesParListe = [];
        $nbArticlesParListe = [];

        // Statistiques individuelles par liste
        foreach ($listes as $liste) {
            $articlesParListe[$liste->getId()] = $listeRepository->findArticlesByListeId($liste->getId());
            $nbArticlesParListe[$liste->getId()] = count($articlesParListe[$liste->getId()]);
        }
        
        $statsGlobales = [
            'totalArticles' => 0,
            'prixTotal' => 0,
            'moyennePrixArticle' => 0,
            'articlesLesPlusAchetes' => [],
            'articlePlusCher' => null,
            'articleMoinsCher' => null,
            'depensesParType' => []
        ];
        
        $allArticles = $listeArticleRepository->findAllArticlesByUser($user);
        
        // Calcul des statistiques
        $articlesCount = [];
        $depensesParType = [];
        $plusCher = ['prix' => 0, 'article' => null];
        $moinsCher = ['prix' => PHP_FLOAT_MAX, 'article' => null];
        
        foreach ($allArticles as $listeArticle) {
            $articleObj = $listeArticle->getArticle();
            $quantite = $listeArticle->getQuantite();
            $prix = $articleObj->getPrix();
            $prixTotal = $prix * $quantite;
            
            // Comptage total d'articles et prix
            $statsGlobales['totalArticles'] += $quantite;
            $statsGlobales['prixTotal'] += $prixTotal;
            
            // Article le plus cher/moins cher (unitaire)
            if ($prix > $plusCher['prix']) {
                $plusCher['prix'] = $prix;
                $plusCher['article'] = $articleObj;
            }
            if ($prix < $moinsCher['prix'] && $prix > 0) {
                $moinsCher['prix'] = $prix;
                $moinsCher['article'] = $articleObj;
            }
            
            // Comptage des articles les plus achetés
            $articleNom = $articleObj->getNom();
            if (!isset($articlesCount[$articleNom])) {
                $articlesCount[$articleNom] = 0;
            }
            $articlesCount[$articleNom] += $quantite;
            
            // Calcul de la répartition des dépenses par type d'article
            $types = $articleObj->getType();
            if (!$types->isEmpty()) {
                foreach ($types as $type) {
                    $typeNom = $type->getNom();
                    $typeId = $type->getId();
                    if (!isset($depensesParType[$typeNom])) {
                        $depensesParType[$typeNom] = [
                            'id' => $typeId,
                            'montant' => 0,
                            'pourcentage' => 0
                        ];
                    }
                    $depensesParType[$typeNom]['montant'] += $prixTotal;
                }
            }
        }
        
        // Calcul de la moyenne du prix des articles
        if ($statsGlobales['totalArticles'] > 0) {
            $statsGlobales['moyennePrixArticle'] = $statsGlobales['prixTotal'] / $statsGlobales['totalArticles'];
        }
        
        // Tri des articles les plus achetés et limitation à 3
        arsort($articlesCount);
        $statsGlobales['articlesLesPlusAchetes'] = array_slice($articlesCount, 0, 3, true);
        
        // Assignation des articles les plus chers/moins chers
        $statsGlobales['articlePlusCher'] = $plusCher['article'];
        $statsGlobales['articleMoinsCher'] = $moinsCher['article'];
        
        // Calcul des pourcentages pour la répartition des dépenses par type
        foreach ($depensesParType as $type => $data) {
            if ($statsGlobales['prixTotal'] > 0) {
                $depensesParType[$type]['pourcentage'] = ($data['montant'] / $statsGlobales['prixTotal']) * 100;
            }
        }
        
        // Tri des types par montant dépensé (décroissant)
        uasort($depensesParType, function ($a, $b) {
            return $b['montant'] <=> $a['montant'];
        });
        
        $statsGlobales['depensesParType'] = $depensesParType;

        return $this->render('liste/index.html.twig', [
            'listes' => $listes,
            'articlesParListe' => $articlesParListe,
            'nbArticlesParListe' => $nbArticlesParListe,
            'statsGlobales' => $statsGlobales,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_liste_show', methods: ['GET'])]
    public function show(Liste $liste, ListeRepository $listeRepository): Response
    {
        if (!$liste->getUsers()->contains($this->getUser()) && !$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
        }
        
        $articles = $listeRepository->findArticlesByListeId($liste->getId());

        return $this->render('liste/show.html.twig', [
            'liste' => $liste,
            'articles' => $articles
        ]);
    }

    #[Route('/{id}/edit', name: 'app_liste_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Liste $liste, EntityManagerInterface $entityManager): Response
    {
        if (!$liste->getUsers()->contains($this->getUser()) && !$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
        }
        
        $form = $this->createForm(ListeType::class, $liste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_liste_show', ['id' => $liste->getId()], Response::HTTP_SEE_OTHER);
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
            if ($liste->getUsers()->contains($this->getUser())) {
                $liste->removeUser($this->getUser());
                    
                if ($liste->getUsers()->isEmpty()) {
                    $entityManager->remove($liste);
                }
                
                $entityManager->flush();
            } else {
                if ($this->isGranted('ROLE_ADMIN')) {
                    $entityManager->remove($liste);
                    $entityManager->flush();
                }
            }
        }

        return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/add', name: 'app_liste_add', methods: ['GET', 'POST'])]
    public function addArticle(Request $request, Liste $liste, EntityManagerInterface $entityManager): Response
    {
        if (!$liste->getUsers()->contains($this->getUser()) && !$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
        }
        
        $listeArticle = new ListeArticle();
        $listeArticle->setListe($liste);

        $form = $this->createForm(ListeArticleType::class, $listeArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $listeArticle->setDateAjout(new \DateTime());
            $entityManager->persist($listeArticle);
            $entityManager->flush();

            return $this->redirectToRoute('app_liste_show', ['id' => $liste->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('liste/add_article.html.twig', [
            'liste' => $liste,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/{id}/toggle', name: 'app_article_toggle', methods: ['POST'])]
    public function toggleArticleStatus(Request $request, ListeArticle $listeArticle, EntityManagerInterface $entityManager): Response
    {
        $liste = $listeArticle->getListe();
        
        if (!$liste->getUsers()->contains($this->getUser()) && !$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
        }
        
        if ($this->isCsrfTokenValid('toggle'.$listeArticle->getId(), $request->getPayload()->getString('_token'))) {
            // Inverser le statut actuel
            $listeArticle->setAcheter(!$listeArticle->isAcheter());
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_liste_show', ['id' => $liste->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/article/{id}/delete', name: 'app_article_delete', methods: ['POST'])]
    public function deleteArticle(Request $request, ListeArticle $listeArticle, EntityManagerInterface $entityManager): Response
    {
        $liste = $listeArticle->getListe();
        
        if (!$liste->getUsers()->contains($this->getUser()) && !$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_liste_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($this->isCsrfTokenValid('delete'.$listeArticle->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($listeArticle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_liste_show', ['id' => $liste->getId()], Response::HTTP_SEE_OTHER);
    }
}
