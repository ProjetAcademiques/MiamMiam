<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Liste;
use App\Repository\ListeRepository;
use App\Repository\ArticleRepository;
use App\Repository\MagasinRepository;
use App\Repository\UserRepository;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crud')]
final class CrudController extends AbstractController{
    #[Route('/',name:'app_crud_show')]
    public function show(ListeRepository $listeRepository,ArticleRepository $articleRepository,MagasinRepository $magasinRepository,UserRepository $utilisateurRepository,TypeRepository $typeRepository){
        $listes = $listeRepository->findAll();
        $articles = $articleRepository->findAll();
        $magasins = $magasinRepository->findAll();	
        $utilisateurs = $utilisateurRepository->findAll();
        $types = $typeRepository->findAll();
        return $this->render('crud/show.html.twig',[
            'listes'=>$listes, 
            'articles'=>$articles, 
            'magasins'=>$magasins, 
            'utilisateurs'=>$utilisateurs, 
            'types'=>$types
        ]);
    }
    #[Route('/{EntityName}/delete/{id}',name:'app_delete',methods:["POST"])]
    public function deleteRowEntity($id,$EntityName,EntityManagerInterface $entityManager):Response{
        $entityClass = 'App\\Entity\\' . ucfirst($EntityName); 
        $entity = $entityManager->getRepository($entityClass)->find($id);
        $entityManager->remove($entity);
        $entityManager->flush();
        return $this->redirectToRoute('app_crud_show');
    }
}