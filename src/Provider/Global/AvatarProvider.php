<?php

namespace App\Provider\Global;

use App\Repository\AvatarRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;

class AvatarProvider
{
    public function __construct(
        private AvatarRepository $avatarRepository,
        private UserRepository $userRepository,
        private Security $security
    ) {
    }

    public function getCurrentUserAvatar()
    {
        $user = $this->security->getUser();
        if (!$user) {
            return null;
        }

        return $this->avatarRepository->findOneBy(['user' => $user]);
    }

    public function getUserAvatar($pseudo)
    {
        $user = $this->userRepository->findOneBy(['pseudo' => $pseudo]);

        return $this->avatarRepository->findOneBy(['user' => $user]);
    }

    public function getAvatarByUser($id)
    {
        return $this->avatarRepository->findOneBy(['user' => $id]);
    }
}
