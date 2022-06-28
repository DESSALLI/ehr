<?php

namespace App\Controller;

use App\Repository\AuthaurisationRepository;
use App\Repository\PatientsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin/dashboard", name="app_admin_dashboard")
     */
    public function index(): Response
    {
        return $this->render('admin_dashboard/index.html.twig', [
            'controller_name' => 'AdminDashboardController',
        ]);
    }

    
    /**
     * @Route("/admin/request/list", name="app_admin_doctor_requests")
     */
    public function DoctorRequest(AuthaurisationRepository $authaurisationRepository){
        return $this->render('admin_dashboard/doctorrequest.html.twig', [
            'controller_name' => 'AdminDashboardController',
            'auths' => $authaurisationRepository->findAll(),
        ]);
    }
}
