<?php

namespace App\Controller\Account\User;

use App\Entity\User;
use App\Form\SecurityType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/utilisateur', name: 'user_')]
class EditPasswordController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/edition/mot-de-passe/{id}', name: 'edit_password', methods: ['GET', 'POST'])]
    public function editPassword(
        User $user,
        UserPasswordHasherInterface $passwordHasher,
        Request $request
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('security_login');
        }
        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_home');
        }
        $form = $this->createForm(SecurityType::class, $user, [
            'validation_groups' => ['registration'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $currentPassword = $form->get('currentPassword')->getData();
            if ($passwordHasher->isPasswordValid($user, $currentPassword)) {
                $user->setUpdatedAt(new \DateTimeImmutable());
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                $passwordSuccess = true;

                return $this->redirectToRoute('user_edit_password', [
                    'id' => $user->getId(),
                    'passwordSuccess' => $passwordSuccess,
                ]);
            } else {
                $passwordDanger = true;

                return $this->redirectToRoute('user_edit_password', [
                    'id' => $user->getId(),
                    'passwordDanger' => $passwordDanger,
                ]);
            }
        }

        return $this->render('pages/account/user/edit_password/base.html.twig', [
            'form' => $form->createView(),
            'current_route' => $request->get('_route'),
        ]);
    }
}
