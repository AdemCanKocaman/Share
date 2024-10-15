<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContactRepository;
use App\Entity\User;
use App\Repository\UserRepository;
class ContactController extends AbstractController
{
    #[Route('/mod-liste-contacts', name: 'app_liste_contacts')]
    public function listeContacts(ContactRepository $contactRepository): Response
    {
        $contacts = $contactRepository->findAll();
        return $this->render('contact/liste-contacts.html.twig', [
            'contacts' => $contacts
        ]);
    }
    #[Route('/admin-liste-user', name: 'app_liste_user')]
    public function listeUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('user/user.html.twig', [
            'users' => $users
        ]);
    }
    #[Route('/profil', name: 'app_profil')]
    public function profil(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('user/profil.html.twig', [
            'users' => $users
        ]);
    }
}