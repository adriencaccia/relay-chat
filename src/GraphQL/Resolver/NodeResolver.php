<?php

namespace App\GraphQL\Resolver;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use GraphQL\Type\Definition\ObjectType;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;
use Overblog\GraphQLBundle\Resolver\TypeResolver;
use Overblog\GraphQLBundle\Resolver\UnresolvableException;

class NodeResolver implements ResolverInterface, AliasedInterface
{
    /** @var UserRepository */
    private $userRepository;

    /** @var MessageRepository */
    private $messageRepository;

    public function __construct(
        UserRepository $userRepository,
        MessageRepository $messageRepository
    ) {
        $this->userRepository = $userRepository;
        $this->messageRepository = $messageRepository;
    }

    public function getNode(string $relayId): object
    {
        $user = $this->userRepository->findOneBy(['relayId' => $relayId]);
        if ($user) {
            return $user;
        }
        $message = $this->messageRepository->findOneBy(['relayId' => $relayId]);
        if ($message) {
            return $message;
        }

        throw new UnresolvableException("Couldn't find Node");
    }

    public function resolveType($value, TypeResolver $typeResolver): ObjectType
    {
        if ($value instanceof User) {
            return $typeResolver->resolve('User');
        }

        if ($value instanceof Message) {
            return $typeResolver->resolve("Message");
        }

        throw new UnresolvableException("Couldn't resolve type for interface 'Node'");
    }

    /**
     * {@inheritdoc}
     */
    public static function getAliases(): array
    {
        return [
            'getNode' => 'get_node',
            'resolveType' => 'node_type',
        ];
    }
}
