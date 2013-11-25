<?php

/*
 * (c) Florian Koerner <f.koerner@checkdomain.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Checkdomain\AssetsExtraBundle\Services;

use Checkdomain\AssetsExtraBundle\Interfaces\AssetsLocatorInterface;

/**
 * Converts bundle names and paths.
 * @author Florian Koerner <f.koerner@checkdomain.de>
 */
class AssetsLocator implements AssetsLocatorInterface
{
    protected $assets_path;
    protected $encrypt;

    /**
     * @param type $assets_path
     * @param type $encrypt_bundle
     */
    public function __construct($assets_path, $encrypt_bundle)
    {
        $this->assets_path = $assets_path;
        $this->encrypt = $encrypt_bundle;
    }
    
    /**
     * Convert a logical bundle name to folder name
     * 
     * @param string $bundle
     * @return string
     */
    public function convertBundleName($bundle)
    {
        $bundle = strtolower($bundle);
        return ($this->encrypt) ? substr(md5($bundle), 0, 8) : preg_replace('/bundle$/', '', $bundle);
    }
    
    /**
     * @param string $path
     * @return string
     */
    public function convertFilePath($path)
    {
        $parts = explode('/', trim($path, '/'));
        
        // Not an ordinary file path
        if ($parts[0] == false || ($parts[0][0] !== '@' && $parts[0] !== 'bundles')) {
            return $path;
        }
        
        if ($parts[0] === 'bundles')
        {
            // Old assets path workaround
            array_shift($parts);
            $parts[0] .= 'bundle';
        }
        else
        {
            $parts[0] = substr($parts[0], 1);
        }
        
        $parts[0] = $this->convertBundleName($parts[0]);

        // Add assets path to array
        if ($this->assets_path)
        {
            array_unshift($parts, $this->assets_path);
        }
        
        $url = '/' . implode('/', $parts);

        
        // Normalize url
        $url = preg_replace('~(/+)~', '/', $url);
        while (preg_match($pattern = '/\w+\/\.\.\//', $url))
        {
            $url = preg_replace($pattern, '', $url);
        }
        
        return $url;
    }
}
