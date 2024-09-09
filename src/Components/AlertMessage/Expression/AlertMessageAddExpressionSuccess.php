<?php

namespace App\Components\AlertMessage\Expression;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(template: 'components/AlertMessage/AlertMessage.html.twig')]
class AlertMessageAddExpressionSuccess extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public string $type = 'success';

    #[LiveProp]
    public string $message = 'Ta proposition a bien été soumise.';
}
