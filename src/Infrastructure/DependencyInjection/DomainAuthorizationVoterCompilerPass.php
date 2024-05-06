<?php

namespace App\Infrastructure\DependencyInjection;

use App\Infrastructure\Authorization\DomainAuthorizationVoter;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DomainAuthorizationVoterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition(DomainAuthorizationVoter::class);
        $taggedServices = $container->findTaggedServiceIds('domain.authorization');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addDomainAuthorization', [$container->getDefinition($id)]);
        }
    }
}
