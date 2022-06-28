<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    /**
     * @Route("/dashboard")
     */
    public function dashboard(UrlGeneratorInterface $urlGeneratorInterface){
        $user = $this->getUser();

        if(in_array("ROLE_ADMIN", $user->getRoles())){
            return new RedirectResponse($urlGeneratorInterface->generate('app_admin_dashboard'));
        }
        elseif(in_array("ROLE_DOCTOR", $user->getRoles())){
            return new RedirectResponse($urlGeneratorInterface->generate('app_doctor_dashboard'));
        }
    }
}
