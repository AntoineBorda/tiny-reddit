<?php

namespace App\Components\AlertMessage\Password;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(template: 'components/AlertMessage/AlertMessage.html.twig')]
class AlertMessagePasswordSuccess extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public string $type = 'success';

    #[LiveProp]
    public string $message = 'Ton mot de passe a bien été modifié.';
}
