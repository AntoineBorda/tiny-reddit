<?php

namespace App\Components;

use App\Entity\Comment;
use App\Entity\Expression;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ExpressionComment extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    public function __construct(
        private CommentRepository $commentRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[LiveProp()]
    public ?int $expressionId = null;

    #[LiveProp]
    public ?Comment $initialFormData = null;

    public ?array $commentsByExpression = [];

    public function getCommentsByExpression(): array
    {
        return $this->commentRepository->findBy(
            ['expression' => $this->expressionId],
            ['createdAt' => 'DESC']
        );
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(CommentType::class, $this->initialFormData, [
            'attr' => [
                'id' => 'comment-form-'.$this->expressionId,
            ],
        ]);
    }

    #[LiveProp]
    public ?bool $isNotConnected = false;

    #[LiveAction]
    public function saveComment(): void
    {
        $expression = $this->entityManager->getRepository(Expression::class)->find($this->expressionId);

        if (!$this->getUser()) {
            $this->isNotConnected = true;
            $this->commentsByExpression = $this->getCommentsByExpression();

            return;
        }

        $this->submitForm();
        $comment = $this->getForm()->getData();
        $comment->setExpression($expression);
        $comment->setReader($this->getUser());
        $this->entityManager->persist($comment);
        $this->entityManager->flush();
        $this->initialFormData = new Comment();
        $this->resetForm();
        $this->commentsByExpression = $this->getCommentsByExpression();
    }
}
