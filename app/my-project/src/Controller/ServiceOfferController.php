<?php

namespace App\Controller;

use App\Entity\ServiceOffer;
use App\Form\ServiceOfferType;
use App\Repository\RateServiceRepository;
use App\Repository\ServiceOfferRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/service-offer')]
final class ServiceOfferController extends AbstractController
{
    #[Route(name: 'app_service_offer_index', methods: ['GET'])]
    public function index(ServiceOfferRepository $serviceOfferRepository): Response
    {
        return $this->render('service_offer/index.html.twig', [
            'service_offers' => $serviceOfferRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_service_offer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, RateServiceRepository $rateServiceRepository): Response
    {
        $serviceOffer = new ServiceOffer();
        $form = $this->createForm(ServiceOfferType::class, $serviceOffer);
        $form->handleRequest($request);

        $serviceOffer->setUser($this->getUser());
        $serviceOffer->setDateCreated(new DateTimeImmutable('now'));

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($serviceOffer);
            $entityManager->flush();

            return $this->redirectToRoute('app_service_offer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service_offer/new.html.twig', [
            'service_offer' => $serviceOffer,
            'rate_services' => $rateServiceRepository->findAll(),
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_service_offer_show', methods: ['GET'])]
    public function show(ServiceOffer $serviceOffer): Response
    {
        return $this->render('service_offer/show.html.twig', [
            'service_offer' => $serviceOffer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_service_offer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ServiceOffer $serviceOffer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ServiceOfferType::class, $serviceOffer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_service_offer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('service_offer/edit.html.twig', [
            'service_offer' => $serviceOffer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_service_offer_delete', methods: ['POST'])]
    public function delete(Request $request, ServiceOffer $serviceOffer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$serviceOffer->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($serviceOffer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_service_offer_index', [], Response::HTTP_SEE_OTHER);
    }
}
