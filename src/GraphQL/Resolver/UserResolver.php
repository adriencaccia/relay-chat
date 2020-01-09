<?php

namespace App\GraphQL\Resolver;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Relay\Connection\Output\Connection;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;

class UserResolver implements ResolverInterface, AliasedInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function __invoke(ResolveInfo $info, $value, Argument $args)
    {
        $method = $info->fieldName;

        return $this->{$method}($value, $args);
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
        ];
    }

    public function getUser(int $id): User
    {
        return $this->em->find(User::class, $id);
    }
}
