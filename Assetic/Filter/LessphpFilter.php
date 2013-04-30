<?php

/*
 * (c) Florian Koerner <f.koerner@checkdomain.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Checkdomain\AssetsExtraBundle\Assetic\Filter;

use Assetic\Asset\AssetInterface;

use Assetic\Filter\LessphpFilter as LessphpFilterBase;
use Checkdomain\AssetsExtraBundle\Assetic\Compiler\LessphpCompiler;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Florian Koerner <f.koerner@checkdomain.de>
 */
class LessphpFilter extends LessphpFilterBase
{
    protected $kernel = NULL;
    protected $presets = array();

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        return $this;
    }
    
    public function getKernel()
    {
        return $this->kernel;
    }
    
    public function setPresets(array $presets)
    {
        $this->presets = $presets;
    }
    
    public function getPresets()
    {
        return $this->presets;
    }
    
    public function filterLoad(AssetInterface $asset)
    {
        $root = $asset->getSourceRoot();
        $path = $asset->getSourcePath();

        $lc = new LessphpCompiler();
        $lc->setKernel($this->getKernel());
        
        if ($root && $path) {
            $lc->importDir = dirname($root.'/'.$path);
        }

        foreach ($this->loadPaths as $loadPath) {
            $lc->addImportDir($loadPath);
        }

        if ($this->formatter) {
            $lc->setFormatter($this->formatter);
        }

        if (null !== $this->preserveComments) {
            $lc->setPreserveComments($this->preserveComments);
        }

        $asset->setContent($lc->parse($asset->getContent(), $this->getPresets()));
    }
}
