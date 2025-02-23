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

class SalusperaquamConfigurationTreatment implements DataConfigurationInterface
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
            'configuration_treatment_url' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TREATMENT_URL'),
            'configuration_treatment_biding' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TREATMENT_BIDING'),
            'configuration_treatment_resource' => $this->configuration->get('SALUSPERAQUAM_CONFIGURATION_TREATMENT_RESOURCE'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $config)
    {
        if ($this->validateConfiguration($config)) {
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TREATMENT_URL', $config['configuration_treatment_url']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TREATMENT_BIDING', $config['configuration_treatment_biding']);
            $this->configuration->set('SALUSPERAQUAM_CONFIGURATION_TREATMENT_RESOURCE', $config['configuration_treatment_resource']);
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function validateConfiguration(array $config)
    {
        return isset(
            $config['configuration_treatment_url'],
            $config['configuration_treatment_biding'],
            $config['configuration_treatment_resource'],
       );
    }
}
