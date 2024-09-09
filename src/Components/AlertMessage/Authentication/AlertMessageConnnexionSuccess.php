<?php

namespace App\Components\AlertMessage\Authentication;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(template: 'components/AlertMessage/AlertMessage.html.twig')]
class AlertMessageConnnexionSuccess extends AbstractController
{
    use DefaultActionTrait;

    public function __construct(
        private TranslatorInterface $translator
    ) {
        $this->message = $translator->trans('connexion.success', [], 'alertmessage');
    }

    #[LiveProp]
    public string $type = 'success';

    #[LiveProp]
    public string $message = 'Connexion réussie.';
}
