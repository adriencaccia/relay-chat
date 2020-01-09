<?php

namespace App\GraphQL\Resolver;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\Argument;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class MessageResolver implements ResolverInterface, AliasedInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var MessageRepository */
    private $messageRepository;

    public function __construct(
        EntityManagerInterface $em,
        MessageRepository $messageRepository
    ) {
        $this->em = $em;
        $this->messageRepository = $messageRepository;
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
        return $message->getCreatedAt()->format('Y-m-d H:i:s');
    }

    public function user(Message $message): User
    {
        return $message->getUser();
    }

    /**
     * {@inheritdoc}
     */
    public static function getAliases(): array
    {
        return [
            'getMessages' => 'get_messages',
        ];
    }

    /**
     * @return ArrayCollection|Message[]
     */
    public function getMessages(): ArrayCollection
    {
        return $this->messageRepository->findAllAndSort();
    }
}
