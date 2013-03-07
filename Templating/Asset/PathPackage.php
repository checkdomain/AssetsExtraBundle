<?php

/*
 * (c) Florian Koerner <contact@florian-koerner.eu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Checkdomain\AssetsExtraBundle\Templating\Asset;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Templating\Asset\PathPackage as BasePathPackage;
use Checkdomain\AssetsExtraBundle\Interfaces\AssetsLocatorInterface;

/**
 * The path packages adds a version and a base path to asset URLs.
 *
 * @author Kris Wallsmith <kris@symfony.com>
 * @author Florian Koerner <f.koerner@checkdomain.de>
 */
class PathPackage extends BasePathPackage
{
    protected $assets_locator;
    
    /**
     * Constructor.
     *
     * @param Request $request The current request
     * @param string  $version The version
     * @param string  $format  The version format
     */
    public function __construct(Request $request, $version = null, $format = null)
    {
        parent::__construct($request->getBasePath(), $version, $format);
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
