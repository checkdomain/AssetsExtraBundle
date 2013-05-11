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
    protected $formatter;
    protected $preserveComments;

    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        return $this;
    }
    
    public function getKernel()
    {
        return $this->kernel;
    }
    
    public function setPresets(array $presets) {
        $this->presets = $presets;
        return $this;
    }

    public function getPresets()
    {
        return $this->presets;
    }
    
    public function setFormatter($formatter) {
        $this->formatter = $formatter;
        return $ths;
    }
    
    public function getFormatter()
    {
        return $this->formatter;
    }
    
    public function setPreserveComments($preserveComments) {
        $this->preserveComments = $preserveComments;
        return $this;
    }
    
    public function getPreserveComments()
    {
        return $this->preserveComments;
    }
    
    public function getLoadPaths()
    {
        return $this->loadPaths;
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

        foreach ($this->getLoadPaths() as $loadPath) {
            $lc->addImportDir($loadPath);
        }

        if ($this->getFormatter()) {
            $lc->setFormatter($this->getFormatter());
        }

        if (null !== $this->getPreserveComments()) {
            $lc->setPreserveComments($this->getPreserveComments());
        }

        $asset->setContent($lc->parse($asset->getContent(), $this->getPresets()));
    }
}
