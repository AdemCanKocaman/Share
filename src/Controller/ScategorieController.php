<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route; // Modifié pour l'importation correcte
use App\Form\AjoutScategorieType;
use App\Entity\Scategorie;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class ScategorieController extends AbstractController
{
    #[Route('/ajout-scategorie', name: 'app_ajout-scategorie')]
    public function ajoutScategorie(Request $request, EntityManagerInterface $em): Response
    {
        $scategorie = new Scategorie();
        $form = $this->createForm(AjoutScategorieType::class, $scategorie);
        
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                try {
                    $em->persist($scategorie);
                    $em->flush();
                } catch (\RuntimeException $e) {
                    $this->addFlash('notice', $e->getMessage());
                    return $this->redirectToRoute('app_ajout-scategorie');
                }
                $this->addFlash('notice', 'Sous catégorie insérée');
                return $this->redirectToRoute('app_ajout-scategorie');
            }
        }
        
        return $this->render('scategorie/ajout-scategorie.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
