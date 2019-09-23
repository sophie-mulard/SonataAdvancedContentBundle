<?php

namespace Sherlockode\SonataAdvancedContentBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class SherlockodeSonataAdvancedContentExtension
 */
class SherlockodeSonataAdvancedContentExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        if ($config['use_bundle_templates']['tools']) {
            $container->setParameter('sherlockode_advanced_content.templates.tools', '@SherlockodeSonataAdvancedContent/Tools/index.html.twig');
        }

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
    }
}
