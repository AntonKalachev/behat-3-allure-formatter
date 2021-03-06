<?php

namespace Antonkalachev\Behat3AllureFormatter;

use Behat\Testwork\Exception\ServiceContainer\ExceptionExtension;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class Behat3AllureFormatterExtension implements ExtensionInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        //do nothing
    }

    /**
     * Returns the extension config key.
     *
     * @return string
     */
    public function getConfigKey()
    {
        return 'allure';
    }

    /**
     * Initializes other extensions.
     *
     * This method is called immediately after all extensions are activated but
     * before any extension `configure()` method is called. This allows extensions
     * to hook into the configuration of other extensions providing such an
     * extension point.
     *
     * @param ExtensionManager $extensionManager
     */
    public function initialize(ExtensionManager $extensionManager)
    {
        //do nothing
    }

    /**
     * Setups configuration for the extension.
     *
     * @param ArrayNodeDefinition $builder
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder->children()->scalarNode("name")->defaultValue('allure');
        $builder->children()->scalarNode("issue_tag_prefix")->defaultValue(null);
        $builder->children()->scalarNode("ignored_tags")->defaultValue(null);
    }

    /**
     * Loads extension services into temporary container.
     *
     * @param ContainerBuilder $container
     * @param array $config
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $definition = new Definition(Behat3AllureFormatter::class);

        $definition->addArgument($config['name']);
        $definition->addArgument($config['issue_tag_prefix']);
        $definition->addArgument($config['ignored_tags']);
        $definition->addArgument('%paths.base%');

        $presenter = new Reference(ExceptionExtension::PRESENTER_ID);
        $definition->addArgument($presenter);
        $container->setDefinition("allure.formatter", $definition)
            ->addTag("output.formatter");
    }
}
