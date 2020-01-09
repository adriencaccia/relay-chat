<?php

namespace App\GraphQL\Resolver;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class MessageResolver implements ResolverInterface
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

    public function resolve(string $id): Message
    {
        return $this->em->find(Message::class, $id);
    }

    public function text(Message $message): string
    {
        return $message->getText();
    }

    public function createdAt(Message $message): string
    {
        return $message->getCreatedAt()->format('H:i:s');
    }

    public function user(Message $message): User
    {
        return $message->getUser();
    }
}
