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
 * @author    Flavio Pellizzer
 * @copyright Since 2021 Flavio Pellizzer
 * @license   https://opensource.org/licenses/MIT
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class SalusPerAquam extends Module
{
    public function __construct($name = null, Context $context = null)
    {
        $this->name = 'salusperaquam';
        $this->tab = 'administrator';
        $this->version = '1.0.0';
        $this->author = 'Flavio Pellizzer';
        $this->need_instance = 0;

        parent::__construct($name, $context);

        $this->displayName = $this->getTranslator()->trans(
            'Salus Per Aquam',
            [],
            'Modules.SalusPerAquam.Admin'
        );

        $this->description =
            $this->getTranslator()->trans(
                'Salus Per Aquam webService thermae.',
                [],
                'Modules.SalusPerAquam.Admin'
            );

        $this->ps_versions_compliancy = [
            'min' => '1.7.6.0',
            'max' => _PS_VERSION_,
        ];
    }

    /**
     * This function is required in order to make module compatible with new translation system.
     *
     * @return bool
     */
    public function isUsingNewTranslationSystem()
    {
        return true;
    }
}
