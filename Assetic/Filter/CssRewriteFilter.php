<?php

/*
 * (c) Florian Koerner <f.koerner@checkdomain.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Checkdomain\AssetsExtraBundle\Assetic\Filter;

use Assetic\Asset\AssetInterface;
use Assetic\Filter\BaseCssFilter;

use Checkdomain\AssetsExtraBundle\Interfaces\AssetsLocatorInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @author Florian Koerner <f.koerner@checkdomain.de>
 */
class CssRewriteFilter extends BaseCssFilter
{
    protected $asset = NULL;
    protected $assets_locator = NULL;
    
    public function setAssetsLocator(AssetsLocatorInterface $assets_locator)
    {
        $this->assets_locator = $assets_locator;
        return $this;
    }
    
    public function getAssetsLocator()
    {
        return $this->assets_locator;
    }
    
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        return $this;
    }
    
    public function getKernel()
    {
        return $this->kernel;
    }
    
    public function filterLoad(AssetInterface $asset)
    {
    }
    
    public function filterDump(AssetInterface $asset)
    {
        $this->asset = $asset;
        $base_path = $this->getBasePath();
        $asset_locator = $this->getAssetsLocator();
        
        $content = $this->filterReferences($asset->getContent(), function($matches) use ($base_path, $asset_locator)
        {
            $dont_change = array(
                strpos($matches['url'], '://') !== false,
                strpos($matches['url'], '//') === 0,
                strpos($matches['url'], 'data:') === 0
            );
            
            if (in_array(TRUE, $dont_change))
            {
                return $matches[0];
            }
            
            $url = $matches['url'];
            
            if ($matches['url'][0] !== '/' && $matches['url'][0] !== '@')
            {
                $matches['url'] = $base_path . $matches['url'];
            }

            $matches['url'] = $asset_locator->convertFilePath($matches['url']);

            return str_replace($url, $matches['url'], $matches[0]);
         });
         
         $asset->setContent($content);
    }
    
    private function getBasePath()
    {
        foreach ($this->kernel->getBundles() as $bundle)
        {
            if (strstr($this->asset->getSourceRoot(), $bundle->getPath()) !== false)
            {
                $path = preg_replace('%^Resources/public/%i', '', dirname($this->asset->getSourcePath()));

                if ($path == dirname($this->asset->getSourcePath()))
                {
                    $path = '';
                }

                return sprintf('@%s/%s/', $bundle->getName(), $path);
            }
        }

        return null;
    }
}
