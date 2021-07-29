<?php
namespace App\DependencyInjection\Security\Factory;

use App\Security\Authentication\Provider\JwtProvider;
use App\Security\Firewall\JwtListener;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

class JwtFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, string $id, array $config, string $userProvider, ?string $defaultEntryPoint): array
    {
        $providerId = 'security.authentication.provider.jwt.'.$id;
        $container
            ->setDefinition($providerId, new ChildDefinition(JwtProvider::class))
            ->setArgument(0, new Reference($userProvider))
        ;

        $listenerId = 'security.authentication.listener.jwt.'.$id;
        $container->setDefinition($listenerId, new ChildDefinition(JwtListener::class));

        return [$providerId, $listenerId, $defaultEntryPoint];
    }

    public function getPosition(): string
    {
        return 'pre_auth';
    }

    public function getKey(): string
    {
        return 'jwt';
    }

    public function addConfiguration(NodeDefinition $node): void
    {
    }
}
