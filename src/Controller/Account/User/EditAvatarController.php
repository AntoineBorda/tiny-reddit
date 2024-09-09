<?php

namespace App\Controller\Account\User;

use App\Entity\Avatar;
use App\Form\AvatarType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[Route('/utilisateur', name: 'user_')]
class EditAvatarController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/edition/avatar', name: 'edit_avatar', methods: ['GET', 'POST'])]
    public function editAvatar(
        Request $request,
    ): Response {
        $avatar = $this->entityManager->getRepository(Avatar::class)->findOneBy(['user' => $this->getUser()]);

        if (!$avatar) {
            $avatar = new Avatar();
            $avatar->setUser($this->getUser());
        }

        $form = $this->createForm(AvatarType::class, $avatar);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($avatar);
            $this->entityManager->flush();

            $updateAvatarSuccess = true;

            return $this->redirectToRoute('user_edit_avatar', [
                'updateAvatarSuccess' => $updateAvatarSuccess,
            ]);
        }

        return $this->render('pages/account/user/edit_avatar/base.html.twig', [
            'form' => $form->createView(),
            'avatar' => $avatar,
        ]);
    }
}
