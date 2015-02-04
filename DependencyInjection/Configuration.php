<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Bundle\DoctrineConsoleBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;

/**
 * Configuration of the securitybundle to get the sonatra_security options.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sonatra_doctrine_console');

        $rootNode
            ->append($this->getCommands())
        ;

        return $treeBuilder;
    }

    /**
     * Get expression node.
     *
     * @return NodeDefinition
     */
    protected function getCommands()
    {
        $node = static::createNode('commands');
        $node
            ->fixXmlConfig('command')
            ->useAttributeAsKey('name', false)
            ->normalizeKeys(false)
            ->prototype('array')
                ->children()
                    ->append($this->getAdapterConfig())
                    ->append($this->createCommand('view'))
                    ->append($this->createCommand('create'))
                    ->append($this->createCommand('edit'))
                    ->append($this->createCommand('delete'))
                    ->append($this->createCommand('undelete'))
                ->end()
            ->end()
        ;

        return $node;
    }

    /**
     * Get the node of command config.
     *
     * @return ArrayNodeDefinition
     */
    protected function getAdapterConfig()
    {
        $node = static::createNode('adapter');
        $this->configAdapterValidation($node);

        $node
            ->isRequired()
        ->children()
            ->scalarNode('service_id')->defaultNull()->end()
            ->scalarNode('service_manager')->cannotBeEmpty()->defaultNull()->end()
            ->scalarNode('short_name')->cannotBeEmpty()->defaultNull()->end()
            ->scalarNode('command_prefix')->cannotBeEmpty()->defaultNull()->end()
            ->scalarNode('command_description')->cannotBeEmpty()->defaultValue('The "%s" command of <comment>"%s"</comment> class')->end()
            ->scalarNode('identifier_field')->cannotBeEmpty()->defaultValue('id')->end()
            ->scalarNode('identifier_argument')->cannotBeEmpty()->defaultValue('identifier')->end()
            ->scalarNode('identifier_argument_description')->cannotBeEmpty()->defaultValue('The unique identifier of %s')->end()
            ->scalarNode('display_name_method')->cannotBeEmpty()->defaultNull()->end()
            ->scalarNode('create_method')->defaultNull()->end()
            ->scalarNode('get_method')->defaultNull()->end()
            ->scalarNode('update_method')->defaultNull()->end()
            ->scalarNode('delete_method')->defaultNull()->end()
            ->scalarNode('undelete_method')->defaultNull()->end()
        ->end();

        return $node;
    }

    /**
     * Configure the node definition of adapter.
     *
     * @param NodeDefinition $node The node definition of adapter
     */
    protected function configAdapterValidation(NodeDefinition $node)
    {
        $node
            ->beforeNormalization()
            ->always(function ($v) {
                if (is_string($v)) {
                    $v = array('service_id' => $v);
                } elseif (is_array($v)) {
                    $v = array_merge($v, array(
                        'service_id' => null,
                    ));
                }

                return $v;
            })
            ->end()
            ->validate()
            ->always(function ($v) use ($node) {
                if (null !== $v['service_id']) {
                    $v = $v['service_id'];
                } else {
                    unset($v['service_id']);
                    $fields = array(
                        'service_manager',
                        'short_name',
                        'command_prefix',
                        'command_description',
                        'identifier_field',
                        'identifier_argument',
                        'identifier_argument_description',
                    );

                    foreach ($fields as $fieldRequired) {
                        if (null === $v[$fieldRequired]) {
                            throw new InvalidConfigurationException(sprintf('The child node "%s" at path "%s" must be configured.', $fieldRequired, $node->getNode()->getPath()));
                        }
                    }
                }

                return $v;
            })
            ->end()
        ;
    }

    /**
     * Create the full config of command.
     *
     * @param string $name The command name
     *
     * @return ArrayNodeDefinition
     */
    protected function createCommand($name)
    {
        $node = static::createNode($name);
        $node
            ->canBeEnabled()
            ->children()
                ->append($this->createFieldArguments())
                ->append($this->createFieldOptions())
            ->end()
        ;

        return $node;
    }

    /**
     * Create the field arguments for a command.
     *
     * @return ArrayNodeDefinition
     */
    protected function createFieldArguments()
    {
        $node = static::createNode('field_arguments');
        $node
            ->fixXmlConfig('field_argument')
            ->useAttributeAsKey('name', false)
            ->normalizeKeys(false)
            ->prototype('array')
                ->children()
                    ->integerNode('mode')->defaultNull()->end()
                    ->scalarNode('description')->defaultValue('')->end()
                    ->scalarNode('default')->defaultNull()->end()
                ->end()
            ->end();

        return $node;
    }

    /**
     * Create the field options for a command.
     *
     * @return ArrayNodeDefinition
     */
    protected function createFieldOptions()
    {
        $node = static::createNode('field_options');
        $node
            ->fixXmlConfig('field_option')
            ->useAttributeAsKey('name', false)
            ->normalizeKeys(false)
            ->prototype('array')
                ->children()
                    ->arrayNode('shortcut')
                        ->prototype('scalar')->end()
                        ->beforeNormalization()
                        ->ifString()
                            ->then(function ($v) {
                                return array($v);
                            })
                        ->end()
                    ->end()
                    ->integerNode('mode')->defaultNull()->end()
                    ->scalarNode('description')->defaultValue('')->end()
                    ->scalarNode('default')->defaultNull()->end()
                ->end()
            ->end();

        return $node;
    }

    /**
     * Create the root node.
     *
     * @param string $name The node name
     * @param string $type The type of node
     *
     * @return ArrayNodeDefinition|NodeDefinition
     */
    protected static function createNode($name, $type = 'array')
    {
        $treeBuilder = new TreeBuilder();
        /* @var ArrayNodeDefinition $node */
        $node = $treeBuilder->root($name, $type);

        return $node;
    }
}
