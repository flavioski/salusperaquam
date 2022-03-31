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
            'test_port' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TEST_PORT'),
            'test_facility_id' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TEST_FACILITY_ID'),
            'test_username' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TEST_USERNAME'),
            'test_password' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TEST_PASSWORD'),
            'test_client_id' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TEST_CLIENT_ID'),
            'test_user_type' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TEST_USER_TYPE'),
            'production' => $this->configuration->getBoolean('SALUSPERAQUAM_CONFIGURATION_PRODUCTION'),
            'production_protocol' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_PROTOCOL'),
            'production_host' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_HOST'),
            'production_port' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_PORT'),
            'production_facility_id' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_FACILITY_ID'),
            'production_username' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_USERNAME'),
            'production_password' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_PASSWORD'),
            'production_client_id' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_CLIENT_ID'),
            'production_user_type' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_USER_TYPE'),
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
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TEST_PORT', $config['test_port']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TEST_FACILITY_ID', $config['test_facility_id']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TEST_USERNAME', $config['test_username']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TEST_PASSWORD', $config['test_password']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TEST_CLIENT_ID', $config['test_client_id']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TEST_USER_TYPE', $config['test_user_type']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCTION', (int) $config['production']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_PROTOCOL', $config['production_protocol']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_HOST', $config['production_host']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_PORT', $config['production_port']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_FACILITY_ID', $config['production_facility_id']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_USERNAME', $config['production_username']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_PASSWORD', $config['production_password']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_CLIENT_ID', $config['production_client_id']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_PRODUCTION_USER_TYPE', $config['production_user_type']);
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
            $config['test_port'],
            $config['test_facility_id'],
            $config['test_username'],
            $config['test_password'],
            $config['test_client_id'],
            $config['test_user_type'],
            $config['production'],
            $config['production_protocol'],
            $config['production_host'],
            $config['production_port'],
            $config['production_facility_id'],
            $config['production_username'],
            $config['production_password'],
            $config['production_client_id'],
            $config['production_user_type']
        );
    }
}
