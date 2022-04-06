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
            'test_url' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TEST_URL'),
            'test_username' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TEST_USERNAME'),
            'test_password' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TEST_PASSWORD'),
            'test_resource_get_treatment' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TEST_RESOURCE_GET_TREATMENT'),
            'test_resource_add_sale' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TEST_RESOURCE_ADD_SALE'),
            'production' => $this->configuration->getBoolean('SALUSPERAQUAM_CONFIGURATION_PRODUCTION'),
            'production_url' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_URL'),
            'production_username' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_USERNAME'),
            'production_password' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_PASSWORD'),
            'production_resource_get_treatment' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_RESOURCE_GET_TREATMENT'),
            'production_resource_add_sale' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_RESOURCE_ADD_SALE'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $config)
    {
        if ($this->validateConfiguration($config)) {
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TEST', (int) $config['test']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TEST_URL', $config['test_url']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TEST_USERNAME', $config['test_username']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TEST_PASSWORD', $config['test_password']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TEST_RESOURCE_GET_TREATMENT', $config['test_resource_get_treatment']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TEST_RESOURCE_ADD_SALE', $config['test_resource_add_sale']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCTION', (int) $config['production']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_URL', $config['production_url']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_USERNAME', $config['production_username']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_PASSWORD', $config['production_password']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_RESOURCE_GET_TREATMENT', $config['production_resource_get_treatment']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_RESOURCE_ADD_SALE', $config['production_resource_add_sale']);
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
            $config['test_url'],
            $config['test_username'],
            $config['test_password'],
            $config['test_resource_get_treatment'],
            $config['test_resource_add_sale'],
            $config['production'],
            $config['production_url'],
            $config['production_username'],
            $config['production_password'],
            $config['production_resource_get_treatment'],
            $config['production_resource_add_sale']
        );
    }
}
