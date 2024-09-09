<?php

namespace App\Components\AlertMessage\Password;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(template: 'components/AlertMessage/AlertMessage.html.twig')]
class AlertMessagePasswordDanger extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public string $type = 'danger';

    #[LiveProp]
    public string $message = 'Ton mot de passe renseigné n\'est pas le bon.';
}
