<?php

/*
 * (c) Florian Koerner <f.koerner@checkdomain.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Checkdomain\AssetsExtraBundle\Interfaces;

/**
 * @author Florian Koerner <f.koerner@checkdomain.de>
 */
interface AssetsLocatorInterface {
    
    public function __construct($assets_path, $encrypt_bundle);
    
    public function convertBundleName($bundle);
    
    public function convertFilePath($path);
    
}