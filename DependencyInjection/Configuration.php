<?php

namespace Sherlockode\SonataAdvancedContentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $tb = new TreeBuilder('sherlockode_sonata_advanced_content');
        // BC layer for symfony/config < 4.2
        $root = \method_exists($tb, 'getRootNode') ? $tb->getRootNode() : $tb->root('sherlockode_sonata_advanced_content');

        $root
            ->children()
                ->arrayNode('use_bundle_templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('tools')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $tb;
    }
}
