<?php
namespace App\Controller;

use App\Entity\Fichier;
use App\Form\FichierType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FichierController extends AbstractController
{
    #[Route('/ajout-fichier', name: 'app_ajout_fichier')]
    public function ajoutFichier(Request $request, EntityManagerInterface $entityManager): Response
    {
        $fichier = new Fichier();
        $form = $this->createForm(FichierType::class, $fichier);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($fichier);
            $entityManager->flush();

            $this->addFlash('success', 'Fichier ajouté avec succès !');
            return $this->redirectToRoute('app_liste_fichiers');
        }

        return $this->render('fichier/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/liste-fichiers', name: 'app_liste_fichiers')]
    public function listeFichiers(EntityManagerInterface $entityManager): Response
    {
    $fichiers = $entityManager->getRepository(Fichier::class)->findAll();

    return $this->render('fichier/liste.html.twig', [
        'fichiers' => $fichiers,
    ]);
}
}