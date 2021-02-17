<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Groupe;
use App\Form\ContactType;
use App\Form\DeleteType;
use App\Form\GroupeType;
use App\Repository\ContactRepository;
use App\Repository\GroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class GroupeController extends AbstractController
{
    private $entityManager;
    private $groupeRepository;
    public function __construct(EntityManagerInterface $entityManager, GroupeRepository $groupeRepository, ContactRepository $contactRepository)
    {
        $this->entityManager =  $entityManager;
        $this->groupeRepository =  $groupeRepository;
        $this->contactRepository =  $contactRepository;
    }
    /**
     * @Route("/groupe", name="list_groupe")
     */
    public function list(Request $request)
    {
        $userId = $this->getUser()->getId();
        $groupeList = $this->groupeRepository->findByUserId($userId);
        $fullNameUser = $this->getUser()->getNom(). " " .$this->getUser()->getPrenom();
        return $this->render('groupe/list.html.twig', ['groupeList' => $groupeList, 'user' => $fullNameUser]);
    }

    /**
     * @Route("/groupe/create", name="create_groupe")
     */
    public function create(Request $request)
    {
        $user = $this->getUser();
        $groupe = new Groupe;
        $groupe->setUser($user);
        
        $form = $this->createForm(GroupeType::class, $groupe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($groupe);
            $this->entityManager->flush();
            return $this->redirectToRoute('groupe_details', ['id' => $groupe->getId()]);
        }
        return $this->render('groupe/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/groupe/{id}", name="groupe_details")
     */
    public function getDetails(Request $request, int $id)
    {
        $groupe = $this->getGroupe($id);
        // Protection contre la faille CSRF pour le delete
        $formDelete = $this->createForm(DeleteType::class);
        $formDelete->handleRequest($request);
        if ($formDelete->isSubmitted()) {
            $this->entityManager->remove($groupe);
            $this->entityManager->flush();
            return $this->redirectToRoute("list_groupe");
        }

        // Form update
        $formUpdate = $this->createForm(GroupeType::class, $groupe);
        $formUpdate->handleRequest($request);
        if ($formUpdate->isSubmitted() && $formUpdate->isValid()) {
            $this->entityManager->persist($groupe);
            $this->entityManager->flush();
            return $this->redirectToRoute('groupe_details', ['id' => $groupe->getId()]);
        }

        // Form create
        $contact = new Contact;
        $contact->addGroupe($groupe);
        
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);
        if ($formContact->isSubmitted() && $formContact->isValid()) {
            $this->entityManager->persist($contact);
            $this->entityManager->flush();
            return $this->redirectToRoute('groupe_details', ['id' => $groupe->getId()]);
        }

        return $this->render('groupe/details.html.twig', ['groupe' => $groupe,
        'formDelete' => $formDelete->createView(),
        'formUpdate' => $formUpdate->createView(),
        'formContact' => $formContact->createView()
        ]);
    }

    private function getGroupe(int $id)
    {
        $user = $this->getUser()->getId();
        $groupe = $this->groupeRepository->findOneBy(
            ['id' => $id, 'user' => $user]
        );
        dump($groupe);
        if ($groupe === null) {
            throw new NotFoundHttpException("groupe introuvable");
        }
        return $groupe;
    }
}