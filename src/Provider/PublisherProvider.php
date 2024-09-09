<?php

namespace App\Provider;

use App\Repository\UserRepository;

class PublisherProvider
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function getPublisherEmailByPseudo(string $pseudo): ?string
    {
        $publisherUser = $this->userRepository->findOneBy(['pseudo' => $pseudo]);

        return $publisherUser ? $publisherUser->getEmail() : null;
    }
}
