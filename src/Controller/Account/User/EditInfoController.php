<?php

namespace App\Controller\Account\User;

use App\Entity\User;
use App\Form\EditProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/utilisateur', name: 'user_')]
class EditInfoController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/edition/profil/{id}', name: 'edit_profile', methods: ['GET', 'POST'])]
    public function editProfile(
        User $user,
        Request $request
    ): Response {
        if (!$this->getUser()) {
            return $this->redirectToRoute('security_login');
        }
        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_home');
        }
        $form = $this->createForm(EditProfileType::class, $user, [
            'validation_groups' => ['Default'],
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $updateProfilSuccess = true;

            return $this->redirectToRoute('user_edit_profile', [
                'id' => $user->getId(),
                'updateProfilSuccess' => $updateProfilSuccess,
            ]);
        }

        return $this->render('pages/account/user/edit_info/base.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
