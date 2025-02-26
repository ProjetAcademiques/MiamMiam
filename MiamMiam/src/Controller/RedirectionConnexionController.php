<?php
namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RedirectionConnexionController extends AbstractController{
    #[Route('/login', name:'login_page')]
    public function loginPage(){
        return $this->render('utilisateur/login.html.twig');
    }
    #[Route('/', name:'home_page')]
    public function homePage(){
        return $this->render('utilisateur/login.html.twig');
    }
    #[Route('/registerForm', name:'register_page')]
    public function registerPage(){
        return $this->render('utilisateur/register.html.twig');
    }
    

  
}