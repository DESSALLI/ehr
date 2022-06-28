<?php

namespace App\Controller;

use App\Entity\Patients;
use App\Entity\Z_patients;
use App\Entity\Authaurisation;
use App\Repository\PatientsRepository;
use App\Repository\AuthaurisationRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DoctorDashboardController extends AbstractController
{
    /**
     * @Route("/doctor/dashboard", name="app_doctor_dashboard")
     */
    public function index(): Response
    {
        return $this->render('doctor_dashboard/index.html.twig', [
            'controller_name' => 'DoctorDashboardController',
        ]);
    }

    /**
     * @Route("/doctor/patient/list", name="app_doctor_patients_auth", methods={"GET"})
     */
    public function patient(PatientsRepository $patientsRepository, AuthaurisationRepository $authaurisationRepository, Security $security): Response
    {
        $patients = $patientsRepository->findAll();
        $dto_patients = [];
        foreach ($patients as $patient) {
            $dto_patient = new Z_patients();
            $dto_patient->setId($patient->getId());
            $dto_patient->setCode($patient->getCode());
            foreach ($patient->getAuthaurisations() as $auth) {

                if ($auth->getUser()->getId() == $security->getUser()->getId()) {
                    $dto_patient->setAuthaurisation($auth->getStatus());
                }
            }
            if ($dto_patient->getAuthaurisation() == "") {
                $dto_patient->setAuthaurisation("no action");
            }

            array_push($dto_patients, $dto_patient);
        }


        return $this->render('doctor_dashboard/patient.html.twig', [
            'patients' => $dto_patients,
        ]);
    }

    /**
     * @Route("/doctor/request/auth/{id}", name="request_auth")
     */
    public function authRequest($id, 
    UrlGeneratorInterface $urlGeneratorInterface,PatientsRepository $patientsRepository, Security $security, AuthaurisationRepository $authaurisationRepository)
    {
        $patient = $patientsRepository->find($id);
        $authaurisation = new Authaurisation();
        foreach ($patient->getAuthaurisations() as $auth) {
            if ($auth->getUser()->getId() == $security->getUser()->getId()) {
                $authaurisation = $auth;
                break;
            }
        }
        if ($authaurisation->getStatus() == null) {
            $authaurisation->setStatus("pending");
            $authaurisation->setUser($security->getUser());
            $authaurisation->setPatient($patient);
        } 
        $authaurisationRepository->add($authaurisation, true);
        return new RedirectResponse($urlGeneratorInterface->generate('app_doctor_patients_auth'));
    }
}
