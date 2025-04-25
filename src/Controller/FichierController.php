<?php

namespace App\Controller;

use App\Entity\Fichier;
use App\Form\FichierType;
use App\Repository\ScategorieRepository;
use App\Repository\UserRepository;  // Burada UserRepository'i dahil ediyoruz
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class FichierController extends AbstractController
{
    #[Route('/ajout-fichier', name: 'app_ajout_fichier')]
    public function ajoutFichier(Request $request, ScategorieRepository $scategorieRepository, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $fichier = new Fichier();
        $scategories = $scategorieRepository->findBy([], ['categorie'=>'asc', 'numero'=>'asc']);
        $form = $this->createForm(FichierType::class, $fichier, ['scategories'=>$scategories]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Seçilen Scategories'leri Fichier'a ekle
            $selectedScategories = $form->get('scategories')->getData();
            foreach ($selectedScategories as $scategorie) {
                $fichier->addScategory($scategorie);
            }
            
            // Dosya işleme
            $file = $form->get('fichier')->getData();
            if ($file) {
                // Dosyanın ismini hazırlıyoruz
                $nomFichierServeur = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $nomFichierServeur = $slugger->slug($nomFichierServeur); // Slugger kullanarak isim oluşturuyoruz
                $nomFichierServeur = $nomFichierServeur.'-'.uniqid().'.'.$file->guessExtension();

                try {
                    // Dosya bilgilerini set et
                    $fichier->setNomServeur($nomFichierServeur);
                    $fichier->setNomOriginal($file->getClientOriginalName());
                    $fichier->setDateEnvoi(new \Datetime());
                    $fichier->setExtension($file->guessExtension());
                    $fichier->setTaille($file->getSize());

                    // Dosyayı veritabanına kaydet
                    $entityManager->persist($fichier);
                    $entityManager->flush();

                    // Dosyayı fiziksel olarak sunucuya kaydet
                    $file->move($this->getParameter('file_directory'), $nomFichierServeur);

                    $this->addFlash('notice', 'Fichier envoyé');
                    return $this->redirectToRoute('app_ajout_fichier');
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur d\'envoi de fichier: '.$e->getMessage());
                }
            }

            // Başarılı ekleme sonrası yönlendirme
            $this->addFlash('success', 'Fichier ajouté avec succès !');
            return $this->redirectToRoute('app_liste_fichiers');
        }

        return $this->render('fichier/ajout.html.twig', [
            'form' => $form->createView(),
            'scategories' => $scategories
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

    #[Route('/liste-fichiers-par-utilisateur', name: 'app_liste_fichiers_par_utilisateur')]
    public function listeFichiersParUtilisateur(UserRepository $userRepository): Response
    {
        // UserRepository'i kullanarak kullanıcıları alıyoruz
        $users = $userRepository->findBy([], ['nom'=>'asc', 'prenom'=>'asc']);

        return $this->render('fichier/liste-fichiers-par-utilisateur.html.twig', [
            'users' => $users
        ]);
    }
    #[Route('/private-telechargement-fichier/{id}', name: 'app_telechargement_fichier', requirements:
["id"=>"\d+"] )]
 public function telechargementFichier(Fichier $fichier) {
 if ($fichier == null){
 $this->redirectToRoute('app_liste_fichiers_par_utilisateur'); }
 else{
 return $this->file($this->getParameter('file_directory').'/'.$fichier->getNomServeur(),
$fichier->getNomOriginal());
 }
 } 
}
