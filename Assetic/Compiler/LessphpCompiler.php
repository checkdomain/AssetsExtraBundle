<?php

/*
 * (c) Florian Koerner <f.koerner@checkdomain.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Checkdomain\AssetsExtraBundle\Assetic\Compiler;

use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Florian Koerner <f.koerner@checkdomain.de>
 */
class LessphpCompiler extends \lessc {
    
    protected $kernel = NULL;
    
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        return $this;
    }
    
    public function getKernel()
    {
        return $this->kernel;
    }
    
	protected function findImport($url)
    {
        if ($url[0] == '@')
        {
            try {
                return $this->getKernel()->locateResource($url);
            } catch (\InvalidArgumentException $e) {
                return null;
            }
        }
        
        return parent::findImport($url);
	}
    
}