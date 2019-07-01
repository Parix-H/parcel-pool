<?php

namespace App\Controller;

use App\Entity\DestinationCity;
use App\Form\DestinationCityType;
use App\Repository\DestinationCityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/destination/city")
 */
class DestinationCityController extends AbstractController
{
    /**
     * @Route("/", name="destination_city_index", methods={"GET"})
     */
    public function index(DestinationCityRepository $destinationCityRepository): Response
    {
        return $this->render('destination_city/index.html.twig', [
            'destination_cities' => $destinationCityRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="destination_city_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $destinationCity = new DestinationCity();
        $form = $this->createForm(DestinationCityType::class, $destinationCity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($destinationCity);
            $entityManager->flush();

            return $this->redirectToRoute('destination_city_index');
        }

        return $this->render('destination_city/new.html.twig', [
            'destination_city' => $destinationCity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="destination_city_show", methods={"GET"})
     */
    public function show(DestinationCity $destinationCity): Response
    {
        return $this->render('destination_city/show.html.twig', [
            'destination_city' => $destinationCity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="destination_city_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DestinationCity $destinationCity): Response
    {
        $form = $this->createForm(DestinationCityType::class, $destinationCity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('destination_city_index', [
                'id' => $destinationCity->getId(),
            ]);
        }

        return $this->render('destination_city/edit.html.twig', [
            'destination_city' => $destinationCity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="destination_city_delete", methods={"DELETE"})
     */
    public function delete(Request $request, DestinationCity $destinationCity): Response
    {
        if ($this->isCsrfTokenValid('delete'.$destinationCity->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($destinationCity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('destination_city_index');
    }
}
