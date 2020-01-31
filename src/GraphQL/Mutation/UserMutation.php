<?php

namespace App\GraphQL\Mutation;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

class UserMutation implements MutationInterface, AliasedInterface
{
    /** @var EntityManagerInterface */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createUser(string $name): array
    {
        $user = new User();
        $user
            ->setName($name)
            ->setRelayId(uniqid())
        ;

        $this->em->persist($user);
        $this->em->flush();

        return [
            'user' => $user,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getAliases(): array
    {
        return [
            'createUser' => 'create_user',
        ];
    }
}
