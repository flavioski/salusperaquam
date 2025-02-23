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

class SalusperaquamConfigurationSale implements DataConfigurationInterface
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
            'configuration_sale_url' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_SALE_URL'),
            'configuration_sale_biding' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_SALE_BIDING'),
            'configuration_sale_resource' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_SALE_RESOURCE'),
            'configuration_sale_resource_add_sale' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_SALE_RESOURCE_ADD_SALE'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $config)
    {
        if ($this->validateConfiguration($config)) {
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_SALE_URL', $config['configuration_sale_url']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_SALE_BIDING', $config['configuration_sale_biding']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_SALE_RESOURCE', $config['configuration_sale_resource']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_SALE_RESOURCE_ADD_SALE', $config['configuration_sale_resource_add_sale']);
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function validateConfiguration(array $config)
    {
        return isset(
            $config['configuration_sale_url'],
            $config['configuration_sale_biding'],
            $config['configuration_sale_resource'],
            $config['configuration_sale_resource_add_sale']
        );
    }
}
