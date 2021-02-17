<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Form\DeleteType;
use App\Form\GroupeType;
use App\Form\TelephoneType;
use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    private $entityManager;
    private $contactRepository;
    public function __construct(EntityManagerInterface $entityManager, ContactRepository $contactRepository, UserRepository $userRepository)
    {
        $this->entityManager =  $entityManager;
        $this->contactRepository =  $contactRepository;
        $this->userRepository =  $userRepository;
    }

    /**
     * @Route("/contact/{id}", name="contact_details", requirements={"id"="\d+"})
     */
    public function getDetails(Request $request, int $id)
    {
        $contact = $this->getContact($id);

        $formDeleteNumero = $this->createForm(DeleteType::class);
        $formDeleteNumero->handleRequest($request);
        if ($formDeleteNumero->isSubmitted()) {
            foreach ($contact->getTelephone() as $NumPhone) {
                $NumPhone->getNumPhone();
                $this->entityManager->remove($NumPhone);
            }
            $this->entityManager->flush();
            return $this->redirectToRoute("contact_details", ['id' => $contact->getId()]);
        }

        $contact->getGroupe()[0]->getId();
        // Protection contre la faille CSRF pour le delete
        $formDelete = $this->createForm(DeleteType::class);
        $formDelete->handleRequest($request);
        if ($formDelete->isSubmitted()) {
            $this->entityManager->remove($contact);
            $this->entityManager->flush();
            return $this->redirectToRoute("groupe_details", ['id' => $contact->getGroupe()[0]->getId()]);
        }
        
        $formUpdate = $this->createForm(ContactType::class, $contact);
        $formUpdate->handleRequest($request);
        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            $this->entityManager->persist($contact);
            $this->entityManager->flush();
            return $this->redirectToRoute('contact_details', ['id' => $contact->getId()]);
        }
        return $this->render('contact/details.html.twig', [
            'contact' => $contact,
            'formDelete' => $formDelete->createView(),
            'formDeleteNumero' => $formDeleteNumero->createView(),
            'formUpdate' => $formUpdate->createView()
        ]);
    }

    private function getContact(int $id)
    {
        $user = $this->getUser();
        $contact = $this->contactRepository->findOneBy(
            ['id' => $id]
        );
        $contact = $this->contactRepository->findContactByUser($user, $contact);
        if ($contact === []) {
            throw new NotFoundHttpException("contact introuvable");
        }
        return $contact;
    }
}
