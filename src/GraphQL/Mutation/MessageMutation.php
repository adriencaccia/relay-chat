<?php

namespace App\GraphQL\Mutation;

use App\Entity\Message;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\MutationInterface;

class MessageMutation implements MutationInterface, AliasedInterface
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
    }

    public function postMessage(string $text, string $userId): array
    {
        $user = $this->userRepository->findOneBy(['relayId' => $userId]);

        $message = new Message();
        $message
            ->setCreatedAt(new \DateTime())
            ->setText($text)
            ->setUser($user)
            ->setRelayId(uniqid())
        ;

        $this->em->persist($message);
        $this->em->flush();

        return [
            'message' => $message,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getAliases(): array
    {
        return [
            'postMessage' => 'post_message',
        ];
    }
}
