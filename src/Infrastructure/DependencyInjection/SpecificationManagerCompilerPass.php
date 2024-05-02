<?php

namespace App\Infrastructure\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SpecificationManagerCompilerPass implements CompilerPassInterface
{

    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container): void
    {
        $definition = $container->findDefinition('App\Infrastructure\Voter\SpecificationManagerInterface');
        $taggedServices = $container->findTaggedServiceIds('domain.specification');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addSpecification', [$container->getDefinition($id)]);
        }
    }
}
