<?php
/**
 * Salus per Aquam
 * Copyright since 2021 Flavio Pellizzer and Contributors
 * <Flavio Pellizzer> Property
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to flappio.pelliccia@gmail.com so we can send you a copy immediately.
 *
 * @author    Flavio Pellizzer <flappio.pelliccia@gmail.com>
 * @copyright Since 2021 Flavio Pellizzer
 * @license   https://opensource.org/licenses/MIT
 */
declare(strict_types=1);

namespace Flavioski\Module\SalusPerAquam\Adapter;

use PrestaShop\PrestaShop\Adapter\Configuration;
use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;

class SalusperaquamConfiguration implements DataConfigurationInterface
{
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        return [
            'test' => $this->configuration->getBoolean('SALUSPERAQUAM_CONFIGURATION_TEST'),
            'test_protocol' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TEST_PROTOCOL'),
            'test_host' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TEST_HOST'),
            'test_username' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TEST_USERNAME'),
            'test_password' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TEST_PASSWORD'),
            'production' => $this->configuration->getBoolean('SALUSPERAQUAM_CONFIGURATION_PRODUCT'),
            'production_protocol' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_PRODUCT_PROTOCOL'),
            'production_host' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_PRODUCT_HOST'),
            'production_username' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_PRODUCT_USERNAME'),
            'production_password' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_PRODUCT_PASSWORD'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $config)
    {
        if ($this->validateConfiguration($config)) {
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TEST', (int) $config['test']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TEST_PROTOCOL', $config['test_protocol']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TEST_HOST', $config['test_host']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TEST_USERNAME', $config['test_username']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TEST_PASSWORD', $config['test_password']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCT', (int) $config['production']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCT_PROTOCOL', $config['production_protocol']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCT_HOST', $config['production_host']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCT_USERNAME', $config['production_username']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCT_PASSWORD', $config['production_password']);
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function validateConfiguration(array $config)
    {
        return isset(
            $config['test'],
            $config['test_protocol'],
            $config['test_host'],
            $config['test_username'],
            $config['test_password'],
            $config['production'],
            $config['production_protocol'],
            $config['production_host'],
            $config['production_username'],
            $config['production_password']
        );
    }
}
