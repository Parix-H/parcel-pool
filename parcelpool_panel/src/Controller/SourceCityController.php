<?php

namespace App\Controller;

use App\Entity\SourceCity;
use App\Form\SourceCityType;
use App\Repository\SourceCityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/source/city")
 */
class SourceCityController extends AbstractController
{
    /**
     * @Route("/", name="source_city_index", methods={"GET"})
     */
    public function index(SourceCityRepository $sourceCityRepository): Response
    {
        return $this->render('source_city/index.html.twig', [
            'source_cities' => $sourceCityRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="source_city_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sourceCity = new SourceCity();
        $form = $this->createForm(SourceCityType::class, $sourceCity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sourceCity);
            $entityManager->flush();

            return $this->redirectToRoute('source_city_index');
        }

        return $this->render('source_city/new.html.twig', [
            'source_city' => $sourceCity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="source_city_show", methods={"GET"})
     */
    public function show(SourceCity $sourceCity): Response
    {
        return $this->render('source_city/show.html.twig', [
            'source_city' => $sourceCity,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="source_city_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SourceCity $sourceCity): Response
    {
        $form = $this->createForm(SourceCityType::class, $sourceCity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('source_city_index', [
                'id' => $sourceCity->getId(),
            ]);
        }

        return $this->render('source_city/edit.html.twig', [
            'source_city' => $sourceCity,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="source_city_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SourceCity $sourceCity): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sourceCity->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sourceCity);
            $entityManager->flush();
        }

        return $this->redirectToRoute('source_city_index');
    }
}
