<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Expression;
use App\Entity\User;
use App\Entity\UserExpression;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private SluggerInterface $slugger;

    public function __construct(
        SluggerInterface $slugger
    ) {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $users = [];
        $expressions = [];

        // USER FIXTURES
        $userTypes = ['user', 'evocati', 'admin'];
        $userCounts = [2, 2, 1];
        $userIndex = 1;
        $evocatiIndex = 1;

        foreach ($userTypes as $index => $type) {
            for ($i = 0; $i < $userCounts[$index]; ++$i) {
                $user = new User();
                $pseudo = '';

                switch ($type) {
                    case 'evocati':
                        $pseudo = 'evocati'.$evocatiIndex;
                        $user->setEmail($pseudo.'@app.fr');
                        ++$evocatiIndex;
                        break;
                    case 'admin':
                        $pseudo = 'admin';
                        $user->setEmail($pseudo.'@app.fr');
                        break;
                    case 'user':
                        $pseudo = 'user'.$userIndex;
                        $user->setEmail($pseudo.'@app.fr');
                        ++$userIndex;
                        break;
                }

                $user->setPseudo($pseudo);
                $user->setRoles(['ROLE_'.strtoupper($type)]);
                $user->setPlainPassword('dwightshrute1984');
                $user->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-6 months', 'now')));
                $manager->persist($user);
                $users[] = $user;
            }
        }

        // EXPRESSION FIXTURES
        $expressionsCount = 25;
        for ($i = 0; $i < $expressionsCount; ++$i) {
            $expression = new Expression();
            $expression->setExpression($faker->sentence(20));
            $expression->setAuthor($faker->name());
            $expression->setPublisher($faker->randomElement($users));
            $expression->setIsValidate($faker->boolean(50));
            $expression->setIsInvalidate(false);
            $expression->setUpvote($faker->numberBetween(0, 100));
            $expression->setDownvote($faker->numberBetween(0, 100));
            $expression->setSlug($this->slugger->slug($expression->getExpression()));
            $expression->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-6 months', 'now')));
            $manager->persist($expression);
            $expressions[] = $expression;
        }

        // COMMENT FIXTURES
        $commentsCount = 100;
        for ($i = 0; $i < $commentsCount; ++$i) {
            $comment = new Comment();
            $comment->setExpression($faker->randomElement($expressions));
            $comment->setReader($faker->randomElement($users));
            $comment->setContent($faker->sentence(8));
            $comment->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-6 months', 'now')));
            $manager->persist($comment);
        }

        // USER_EXPRESSION FIXTURES
        foreach ($users as $user) {
            foreach ($expressions as $expression) {
                $userExpression = new UserExpression();
                $userExpression->setReader($user);
                $userExpression->setExpression($expression);
                $userExpression->setIsFavorite($faker->boolean(50));
                $userExpression->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-6 months', 'now')));
                $manager->persist($userExpression);
            }
        }

        $manager->flush();
    }
}
