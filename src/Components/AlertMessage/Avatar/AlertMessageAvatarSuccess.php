<?php

namespace App\Components\AlertMessage\Avatar;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(template: 'components/AlertMessage/AlertMessage.html.twig')]
class AlertMessageAvatarSuccess extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public string $type = 'success';

    #[LiveProp]
    public string $message = 'Ton avatar a bien été mis à jour.';
}
