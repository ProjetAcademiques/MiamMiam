<?php
namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RedirectionConnexionController extends AbstractController{
    #[Route('/login', name:'login_page')]
    public function loginPage(){
        return $this->render('utilisateur/login.html.twig');
    }
    

  
}