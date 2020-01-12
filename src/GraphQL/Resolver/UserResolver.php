<?php

namespace App\GraphQL\Resolver;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;

class UserResolver implements ResolverInterface, AliasedInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(
        EntityManagerInterface $em,
        UserRepository $userRepository
    ) {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    public function __invoke(ResolveInfo $info, $value, Argument $args)
    {
        $method = $info->fieldName;

        return $this->{$method}($value, $args);
    }

    public function id(User $user): int
    {
        return $user->getId();
    }

    public function name(User $user): string
    {
        return $user->getName();
    }

    public function messages(User $user, Argument $args): Connection
    {
        $messages = $user->getMessages();
        $paginator = new Paginator(function ($offset, $limit) use ($messages) {
            return $messages->slice($offset, $limit ?? 10);
        });

        return $paginator->auto($args, count($messages));
    }

    /**
     * {@inheritdoc}
     */
    public static function getAliases(): array
    {
        return [
            'getUser' => 'get_user',
            'getUsers' => 'get_users',
        ];
    }

    public function getUser(int $id): User
    {
        return $this->em->find(User::class, $id);
    }

    public function getUsers(Argument $args): Connection
    {
        $users = new ArrayCollection($this->userRepository->findAll());

        $paginator = new Paginator(function ($offset, $limit) use ($users) {
            return $users->slice($offset, $limit ?? 10);
        });

        return $paginator->auto($args, count($users));
    }
}
