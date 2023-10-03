<?php

namespace App\Controller;

use App\Entity\Dovetailing;
use App\Form\DovetailingType;
use App\Repository\DovetailingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/dovetailing')]
class DovetailingController extends AbstractController
{
    #[Route('/', name: 'app_dovetailing_index', methods: ['GET'])]
    public function index(DovetailingRepository $dovetailingRepository): Response
    {
        return $this->render('dovetailing/index.html.twig', [
            'dovetailings' => $dovetailingRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_dovetailing_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $dovetailing = new Dovetailing();
        $form = $this->createForm(DovetailingType::class, $dovetailing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dovetailing);
            $entityManager->flush();

            return $this->redirectToRoute('app_dovetailing_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dovetailing/new.html.twig', [
            'dovetailing' => $dovetailing,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dovetailing_show', methods: ['GET'])]
    public function show(Dovetailing $dovetailing): Response
    {
        return $this->render('dovetailing/show.html.twig', [
            'dovetailing' => $dovetailing,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_dovetailing_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Dovetailing $dovetailing, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DovetailingType::class, $dovetailing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_dovetailing_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('dovetailing/edit.html.twig', [
            'dovetailing' => $dovetailing,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_dovetailing_delete', methods: ['POST'])]
    public function delete(Request $request, Dovetailing $dovetailing, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dovetailing->getId(), $request->request->get('_token'))) {
            $entityManager->remove($dovetailing);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_dovetailing_index', [], Response::HTTP_SEE_OTHER);
    }
}
