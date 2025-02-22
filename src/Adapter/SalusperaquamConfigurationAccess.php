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

class SalusperaquamConfigurationAccess implements DataConfigurationInterface
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
            'configuration_login_url' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_LOGIN_URL'),
            'configuration_login_biding' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_LOGIN_BIDING'),
            'configuration_login_resource' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_LOGIN_RESOURCE'),
            'configuration_user_url' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_USER_URL'),
            'configuration_user_biding' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_USER_BIDING'),
            'configuration_user_resource' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_USER_RESOURCE'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $config)
    {
        if ($this->validateConfiguration($config)) {
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_LOGIN_URL', $config['configuration_login_url']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_LOGIN_BIDING', $config['configuration_login_biding']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_LOGIN_RESOURCE', $config['configuration_login_resource']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_USER_URL', $config['configuration_user_url']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_USER_BIDING', $config['configuration_user_biding']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_USER_RESOURCE', $config['configuration_user_resource']);
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function validateConfiguration(array $config)
    {
        return isset(
            $config['configuration_login_url'],
            $config['configuration_login_biding'],
            $config['configuration_login_resource'],
            $config['configuration_user_url'],
            $config['configuration_user_biding'],
            $config['configuration_user_resource']
        );
    }
}
