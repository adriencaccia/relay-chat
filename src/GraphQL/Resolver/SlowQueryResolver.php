<?php

namespace App\GraphQL\Resolver;

use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

class SlowQueryResolver implements ResolverInterface, AliasedInterface
{
    public function getSlowQuery(int $timeout): array
    {
        usleep($timeout * 1000);

        return ['result' => sprintf('Waited for %s seconds', $timeout / 1000)];
    }

    /**
     * {@inheritdoc}
     */
    public static function getAliases(): array
    {
        return [
            'getSlowQuery' => 'get_slow_query',
        ];
    }
}
