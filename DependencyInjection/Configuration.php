<?php

/*
 * (c) Florian Koerner <f.koerner@checkdomain.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Checkdomain\AssetsExtraBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class validate and merge configuration
 * 
 * @author Florian Koerner <f.koerner@checkdomain.de>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('assets_extra');

        $rootNode->children()
                 ->booleanNode('encrypt_bundle')->defaultFalse()->end()
                 ->scalarNode('write_to')->defaultValue('web')->end()
                 ->scalarNode('assets_path')->defaultValue('bundles')->end()
                 ->end();
        
        return $treeBuilder;
    }
}
