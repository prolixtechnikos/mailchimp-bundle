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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('prolix_mailchimp');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        $rootNode
            ->children()
                ->scalarNode('api_key')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('default_list')->isRequired()->cannotBeEmpty()->end()
                ->booleanNode('ssl')->defaultTrue()->end()
                ->arrayNode('curl_options')
                    ->defaultValue(array('curlopt_useragent'=>'ProlixMailchimp'))
                    ->useAttributeAsKey('name')
                    ->prototype('scalar')
                ->end()
            ->end();

        return $treeBuilder;
    }

}
