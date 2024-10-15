<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ContactType;
use App\Form\CategoriesType;
use App\Form\ModifierCategorieType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Contact;
use App\Entity\Categories;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\SupprimerCategorieType;

class BaseController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('base/index.html.twig', [
        ]);
    }
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request, EntityManagerInterface $em): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $contact->setDateEnvoi(new \Datetime());
                $em->persist($contact);
                $em->flush();
                $this->addFlash('notice','Message envoyé');
                return $this->redirectToRoute('app_contact');
            }
        }
        return $this->render('base/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/private-categories', name: 'app_categories')]
        public function categories(Request $request, EntityManagerInterface $em): Response
        {
        $categories = new Categories();
        $form = $this->createForm(CategoriesType::class, $categories);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $em->persist($categories);
                $em->flush();
                $this->addFlash('notice', 'Nouveau Categories créer');
                return $this->redirectToRoute('app_categories');
            }
        }
        /*$allCategories = [];
        $form = $this->createForm(SupprimerCategorieType::class, null, [
            'categories' => $allCategories,
        ]);        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $selectedCategories = $form->get('categories')->getData();
            foreach ($selectedCategories as $categorie) {
                $em->remove($categorie);
            }
            $em->flush();
            $this->addFlash('notice', 'Catégories supprimées avec succès');
            return $this->redirectToRoute('app_categories');
        }*/

        $allCategories = $em->getRepository(Categories::class)->findAll();

        return $this->render('base/categories.html.twig', [
            'categories' => $allCategories,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/private-modifier/{id}', name: 'app_modifier')]
    public function modifier(Request $request, Categories $categorie, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ModifierCategorieType::class, $categorie);
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if ($form->isSubmitted()&&$form->isValid()){
                $em->persist($categorie);
                $em->flush();
                $this->addFlash('notice','Catégorie modifiée');
                return $this->redirectToRoute('app_categories');
            }
        }
        return $this->render('base/modifier.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/private-supprimer-categorie/{id}', name: 'app_supprimer_categorie')]
    public function supprimerCategorie(Request $request, Categories $categorie,EntityManagerInterface $em): Response    
    {   
        if($categorie!=null){
            $em->remove($categorie);
            $em->flush();
            $this->addFlash('notice','Catégorie supprimée');
        }
        return $this->redirectToRoute('app_categories');
    }
}