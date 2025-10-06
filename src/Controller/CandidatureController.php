<?php
namespace App\Controller;

use App\Entity\Candidature;
use App\Form\CandidatureType;
use App\Repository\CandidatureRepository;
use App\Repository\MissionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/candidature')]
class CandidatureController extends AbstractController
{
    #[Route('/new/{missionId}', name: 'app_candidature_new', methods: ['GET', 'POST'])]
    public function new(
        int $missionId,
        MissionRepository $missionRepo,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $mission = $missionRepo->find($missionId);
        $candidature = new Candidature();
        $candidature->setMission($mission);

        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($candidature);
            $em->flush();
            $this->addFlash('success', 'Candidature enregistrée !');
            return $this->redirectToRoute('app_mission_index');
        }

        return $this->render('candidature/new.html.twig', [
            'form' => $form->createView(),
            'mission' => $mission,
        ]);
    }

    #[Route('/mes', name: 'app_candidature_mes', methods: ['GET'])]
    public function mes(Request $request, CandidatureRepository $repo): Response
    {
        $email = $request->query->get('email');
        $candidatures = $email ? $repo->findBy(['email' => $email]) : [];

        return $this->render('candidature/mes.html.twig', [
            'candidatures' => $candidatures,
            'email' => $email,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_candidature_delete', methods: ['POST'])]
    public function delete(Candidature $candidature, EntityManagerInterface $em, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$candidature->getId(), $request->request->get('_token'))) {
            $em->remove($candidature);
            $em->flush();
            $this->addFlash('info', 'Candidature annulée.');
        }

        return $this->redirectToRoute('app_candidature_mes');
    }
}
