<?php

/*
 * (c) Florian Koerner <f.koerner@checkdomain.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Checkdomain\AssetsExtraBundle\Templating\Asset;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\Asset\UrlPackage as UrlPathPackage;
use Checkdomain\AssetsExtraBundle\Interfaces\AssetsLocatorInterface;

/**
 * The URL packages adds a version and a base URL to asset URLs.
 *
 * @author Kris Wallsmith <kris@symfony.com>
 * @author Florian Koerner <f.koerner@checkdomain.de>
 */
class UrlPackage extends UrlPathPackage
{
    protected $assets_locator;
    
    /**
     * Constructor.
     *
     * @param string|array $baseUrls Base asset URLs
     * @param string       $version  The package version
     * @param string       $format   The format used to apply the version
     */
    public function __construct($baseUrls = array(), $version = null, $format = null)
    {
        parent::__construct($baseUrls, $version, $format);
    }
    
    /**
     * @param \Checkdomain\AssetsExtraBundle\Interfaces\AssetsLocatorInterface $assets_locator
     * @return \Checkdomain\AssetsExtraBundle\Templating\Asset\PathPackage
     */
    public function setAssetsLocator(AssetsLocatorInterface $assets_locator)
    {
        $this->assets_locator = $assets_locator;
        return $this;
    }
    
    /**
     * @return \Checkdomain\AssetsExtraBundle\Interfaces\AssetsLocatorInterface
     */
    public function getAssetsLocator()
    {
        return $this->assets_locator;
    }
    
    public function getUrl($path)
    {
        return parent::getUrl($this->getAssetsLocator()->convertFilePath($path));
    }
}
