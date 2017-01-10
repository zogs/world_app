<?php

namespace Zogs\WorldBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ZogsWorldExtension extends Extension
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

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('admin.yml');

        // Define parameters from config.yml
        $container->setParameter('world.importer.load_fixtures', $config['importer']['load_fixtures']);
        $container->setParameter('world.importer.files_to_import', $config['importer']['files_to_import']);

        // Inject config parameters on services
        // Once the services definition are read, get your service and add a method call to setConfig()
        $ExporterServiceDefintion = $container->getDefinition( 'world.exporter' );
        $ExporterServiceDefintion->addMethodCall( 'setConfig', array( $config[ 'exporter' ] ) );
        
        $ImporterServiceDefintion = $container->getDefinition( 'world.importer' );
        $ImporterServiceDefintion->addMethodCall( 'setConfig', array( $config[ 'importer' ] ) );
    }
}
