<?php
/**
 * This File is Part of Mailchimp Bundle Provided by Prolix Technikos
 *
 * @author  Ravindra Khokharia <ravindrakhokharia@gmail.com>
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version 1.0
 * @link    https://github.com/prolixtechnikos/mailchimp-bundle
 * @since   version 1.0
 */
namespace Prolix\MailchimpBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ProlixMailchimpExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('prolix_mailchimp.default_list', $config['default_list']);
        $container->setParameter('prolix_mailchimp.api_key', $config['api_key']);
        $container->setParameter('prolix_mailchimp.ssl', $config['ssl']);
        $container->setParameter('prolix_mailchimp.curl_options', $config['curl_options']);

    }

}
