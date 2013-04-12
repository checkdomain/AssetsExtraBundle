<?php

/*
 * (c) Fabien Potencier <fabien@symfony.com>
 * (c) Florian Koerner <f.koerner@checkdomain.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Checkdomain\AssetsExtraBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\AssetsInstallCommand as BaseCommand;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

/**
 * Command that places bundle web assets into a given directory.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Florian Koerner <f.koerner@checkdomain.de>
 */
class AssetsInstallCommand extends BaseCommand
{
    protected $config = array();

    protected $assets_locator;
    protected $filesystem;
    protected $bundles;

    protected function configure()
    {
        $this->setName('assets:install')
             ->addArgument('write_to', InputArgument::OPTIONAL, 'Override the configured asset root')
             ->addOption('symlink', null, InputOption::VALUE_NONE, 'Symlinks the assets instead of copying it')
             ->addOption('relative', null, InputOption::VALUE_NONE, 'Make relative symlinks');
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $basePath = ($input->getArgument('write_to') ?: $this->getContainer()->getParameter('checkdomain_assets_extra.write_to'))
                  . '/' . $this->getContainer()->getParameter('checkdomain_assets_extra.assets_path');
        
        $this->config = array(
            'basePath'  => $basePath,
            'symlink'   => $input->getOption('symlink'),
            'relative'  => $input->getOption('relative')
        );
        
        $this->assets_locator = $this->getContainer()->get('assets_locator');
        $this->filesystem = $this->getContainer()->get('filesystem');
        $this->bundles = $this->getContainer()->get('kernel')->getBundles();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $basePath = rtrim($this->config['basePath'], '/');

        if (!function_exists('symlink') && $this->config['symlink'])
        {
            throw new \InvalidArgumentException('The symlink() function is not available on your system. You need to install the assets without the --symlink option.');
        }
        
        if (!is_dir($basePath))
        {
            $this->filesystem->mkdir($basePath, 0777);
        }
        
        $output->writeln(sprintf("Installing assets using the <comment>%s</comment> option", $this->config['symlink'] ? 'symlink' : 'hard copy'));

        foreach ($this->bundles as $bundle)
        {
            if (is_dir($originDir = $bundle->getPath().'/Resources/public'))
            {
                $assetsDir = $basePath . '/' . $this->assets_locator->convertBundleName($bundle->getName());

                $output->writeln(sprintf('Installing assets for <comment>%s</comment> into <comment>%s</comment>', $bundle->getNamespace(), $assetsDir));

                $this->filesystem->remove($assetsDir);

                if ($this->config['symlink'])
                {
                    if ($this->config['relative'])
                    {
                        $relativeOriginDir = $this->filesystem->makePathRelative($originDir, realpath($assetsDir));
                    }
                    else
                    {
                        $relativeOriginDir = $originDir;
                    }
                    $this->filesystem->symlink($relativeOriginDir, $assetsDir);
                }
                else
                {
                    $this->filesystem->mkdir($assetsDir, 0777);
                    // We use a custom iterator to ignore VCS files
                    $this->filesystem->mirror($originDir, $assetsDir, Finder::create()->in($originDir));
                }
            }
        }
    }
}
