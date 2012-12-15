<?php

namespace ASK\SphinxSearchBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ASKSphinxSearchExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->loadSphinxApiConfig($config, $container);
        $this->loadSphinxManagerConfig($config, $container);
    }

    /**
     * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    protected function loadSphinxApiConfig(array $config, ContainerBuilder $container)
    {
        $definition = $container->getDefinition('ask_sphinx_search.api');

        $definition->addMethodCall('SetServer', array(
            $config['host'],
            $config['port']
        ));

        $definition->addMethodCall('SetRetries', array(
            $config['retry_count'],
            $config['retry_delay']
        ));

        $definition->addMethodCall('SetConnectTimeout', array(
            $config['connect_timeout']
        ));
    }

    /**
     * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    protected function loadSphinxManagerConfig(array $config, ContainerBuilder $container)
    {
        $definition = $container->getDefinition('ask_sphinx_search.manager');
        $definition->replaceArgument(1, $config['indexes']);
    }
}
