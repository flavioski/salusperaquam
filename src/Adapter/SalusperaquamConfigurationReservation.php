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

class SalusperaquamConfigurationReservation implements DataConfigurationInterface
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
            'configuration_reservation_url' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_RESERVATION_URL'),
            'configuration_reservation_biding' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_RESERVATION_BIDING'),
            'configuration_reservation_resource' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_RESERVATION_RESOURCE'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $config)
    {
        if ($this->validateConfiguration($config)) {
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_RESERVATION_URL', $config['configuration_reservation_url']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_RESERVATION_BIDING', $config['configuration_reservation_biding']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_RESERVATION_RESOURCE', $config['configuration_reservation_resource']);
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function validateConfiguration(array $config)
    {
        return isset(
            $config['configuration_reservation_url'],
            $config['configuration_reservation_biding'],
            $config['configuration_reservation_resource']
        );
    }
}
