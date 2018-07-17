<?php

namespace Sherlockode\SonataAdvancedContentBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AdminPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $mapping = $container->getParameter('sherlockode_advanced_content.entity_class_mapping');

        $container->getDefinition('sherlockode_advanced_content.admin.content_type')
            ->replaceArgument(1, $mapping['content_type']);
        $container->getDefinition('sherlockode_advanced_content.admin.content')
            ->replaceArgument(1, $mapping['content'])
            ->addMethodCall('setContentTypeClass', [$mapping['content_type']]);
    }
}
