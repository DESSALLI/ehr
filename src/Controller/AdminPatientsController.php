<?php

namespace App\Controller;

use App\Entity\Patients;
use App\Form\PatientsType;
use App\Repository\PatientsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/patients")
 */
class AdminPatientsController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_patients_index", methods={"GET"})
     */
    public function index(PatientsRepository $patientsRepository): Response
    {
        return $this->render('admin_patients/index.html.twig', [
            'patients' => $patientsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_patients_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PatientsRepository $patientsRepository): Response
    {
        $patient = new Patients();
        $form = $this->createForm(PatientsType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('name')->getData();
            $code = substr($name,0, 3)."".rand(100, 999);
            //dd($code);
            $patient->setCode($code);
            $patientsRepository->add($patient, true);

            return $this->redirectToRoute('app_admin_patients_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_patients/new.html.twig', [
            'patient' => $patient,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_patients_show", methods={"GET"})
     */
    public function show(Patients $patient): Response
    {
        return $this->render('admin_patients/show.html.twig', [
            'patient' => $patient,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_patients_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Patients $patient, PatientsRepository $patientsRepository): Response
    {
        $form = $this->createForm(PatientsType::class, $patient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $patientsRepository->add($patient, true);

            return $this->redirectToRoute('app_admin_patients_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_patients/edit.html.twig', [
            'patient' => $patient,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_patients_delete", methods={"POST"})
     */
    public function delete(Request $request, Patients $patient, PatientsRepository $patientsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$patient->getId(), $request->request->get('_token'))) {
            $patientsRepository->remove($patient, true);
        }

        return $this->redirectToRoute('app_admin_patients_index', [], Response::HTTP_SEE_OTHER);
    }
}
