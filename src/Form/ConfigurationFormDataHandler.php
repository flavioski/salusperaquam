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

namespace Flavioski\Module\SalusPerAquam\Form;

use PrestaShop\PrestaShop\Core\Form\Handler;
use PrestaShopBundle\Entity\Repository\TabRepository;

final class ConfigurationFormDataHandler extends Handler
{
    /**
     * @var TabRepository
     */
    private $tabRepository;

    /**
     * @param TabRepository $tabRepository
     */
    public function setTabRepository(TabRepository $tabRepository)
    {
        $this->tabRepository = $tabRepository;
    }

    /**
     * @param array $data
     * @return void
     */
    public function save(array $data)
    {
        $errors = parent::save($data);

        $this->hookDispatcher->dispatchWithParameters(
            'actionConfigurationPageFormSave',
            ['errors' => &$errors, 'form_data' => &$data]
        );
    }
}
