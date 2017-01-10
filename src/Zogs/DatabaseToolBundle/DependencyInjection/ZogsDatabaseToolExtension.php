<?php

namespace Zogs\DatabaseToolBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ZogsDatabaseToolExtension extends Extension
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

        $ExporterServiceDefintion = $container->getDefinition( 'db.exporter.mysql' );        
        $ExporterServiceDefintion->addMethodCall( 'setConfig', array( $config[ 'exporter' ] ) );
        $ExporterServiceDefintion->addMethodCall( 'setMysqlPath', array( $config[ 'mysql_path' ] ) );

        $ExporterServiceDefinition = $container->getDefinition( 'db.exporter.pgsql' );        
        $ExporterServiceDefinition->addMethodCall( 'setConfig', array( $config[ 'exporter' ] ) );
        $ExporterServiceDefinition->addMethodCall( 'setPsqlPath', array( $config[ 'pgsql_path' ] ) );

        $ExporterServiceDefinition = $container->getDefinition( 'db.importer.pgsql' );        
        $ExporterServiceDefinition->addMethodCall( 'setConfig', array( $config[ 'importer' ] ) );
        $ExporterServiceDefinition->addMethodCall( 'setPgsqlPath', array( $config[ 'pgsql_path' ] ) );
    }
}
