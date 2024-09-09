<?php

namespace App\Components\AlertMessage\Expression;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(template: 'components/AlertMessage/AlertMessage.html.twig')]
class AlertMessageAddExpressionDanger extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public string $type = 'danger';

    #[LiveProp]
    public string $message = 'Tu dois être connecté pour soumettre une proposition.';
}
