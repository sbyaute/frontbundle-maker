<?php

namespace Sbyaute\FrontBundleMakerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('frontbundle-maker');
        $rootNode = $treeBuilder->getRootNode();
        
        $rootNode
        ->children()
            ->scalarNode('base_layout')
                ->defaultValue("base.html.twig")
            ->end()
            ->scalarNode('skeleton_dir')
                ->defaultValue(__DIR__ . "/../Resources/skeleton/")
            ->end()
        ->end();
        return $treeBuilder;
    }
}
