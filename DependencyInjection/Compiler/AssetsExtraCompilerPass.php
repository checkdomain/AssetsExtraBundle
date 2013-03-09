<?php

/*
 * (c) Florian Koerner <f.koerner@checkdomain.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Checkdomain\AssetsExtraBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Florian Koerner <f.koerner@checkdomain.de>
 */
class AssetsExtraCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container) 
    {
        $container->getDefinition('templating.asset.path_package')
                  ->addMethodCall('setAssetsLocator', array(
                      new Reference('assets_locator')
                  ));
        
        if ($container->has('assetic.filter.lessphp'))
        {
            $container->getDefinition('assetic.filter.lessphp')
                      ->addMethodCall('setKernel', array(
                          new Reference('kernel')
                      ));
        }
        
        if ($container->has('assetic.filter.cssrewrite'))
        {
            $container->getDefinition('assetic.filter.cssrewrite')
                      ->addMethodCall('setKernel', array(
                          new Reference('kernel')
                      ))
                      ->addMethodCall('setAssetsLocator', array(
                          new Reference('assets_locator')
                      ));
        }
    }
}