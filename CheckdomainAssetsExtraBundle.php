<?php

/*
 * (c) Florian Koerner <f.koerner@checkdomain.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Checkdomain\AssetsExtraBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Checkdomain\AssetsExtraBundle\DependencyInjection\Compiler\AssetsExtraCompilerPass;

/**
 * @author Florian Koerner <f.koerner@checkdomain.de>
 */
class CheckdomainAssetsExtraBundle extends Bundle
{
    public function getParent()
    {
        return 'FrameworkBundle';
    }
    
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AssetsExtraCompilerPass());
    }
}
