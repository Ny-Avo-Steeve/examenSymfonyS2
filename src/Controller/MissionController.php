<?php
namespace App\Controller;

use App\Entity\Mission;
use App\Form\MissionType;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mission')]
class MissionController extends AbstractController
{
    #[Route('/', name: 'app_mission_index', methods: ['GET'])]
    public function index(MissionRepository $missionRepository): Response
    {
        return $this->render('mission/index.html.twig', [
            'missions' => $missionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_mission_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $mission = new Mission();
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($mission);
            $em->flush();
            $this->addFlash('success', 'Mission créée !');
            return $this->redirectToRoute('app_mission_index');
        }

        return $this->render('mission/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_mission_show', methods: ['GET'])]
    public function show(Mission $mission): Response
    {
        return $this->render('mission/show.html.twig', ['mission' => $mission]);
    }

    #[Route('/{id}/edit', name: 'app_mission_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mission $mission, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(MissionType::class, $mission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Mission mise à jour !');
            return $this->redirectToRoute('app_mission_index');
        }

        return $this->render('mission/edit.html.twig', [
            'mission' => $mission,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_mission_delete', methods: ['POST'])]
    public function delete(Mission $mission, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mission->getId(), $request->request->get('_token'))) {
            $em->remove($mission);
            $em->flush();
            $this->addFlash('info', 'Mission supprimée.');
        }

        return $this->redirectToRoute('app_mission_index');
    }
}
