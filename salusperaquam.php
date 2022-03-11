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

    /**
     * Install module and register hooks to allow grid modification.
     *
     * @see https://devdocs.prestashop.com/1.7/modules/concepts/hooks/use-hooks-on-modern-pages/
     *
     * @return bool
     */
    public function install()
    {
        return parent::install() &&
            $this->installTab()
            ;
    }

    /**
     * Uninstall module and detach hooks
     *
     * @return bool
     */
    public function uninstall()
    {
        return parent::uninstall() &&
            $this->uninstallTab()
            ;
    }

    /**
     * enable
     *
     * @param bool $force_all
     *
     * @return bool
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function enable($force_all = false)
    {
        return parent::enable($force_all)
            && $this->installTab()
            ;
    }

    /**
     * disable
     *
     * @param bool $force_all
     *
     * @return bool
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function disable($force_all = false)
    {
        return parent::disable($force_all)
            && $this->uninstallTab()
            ;
    }

    /**
     * install Tab
     *
     * @return bool
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    private function installTab()
    {
        // Main
        $MainTabId = (int) Tab::getIdFromClassName('AdminSalusPerAquam');
        if (!$MainTabId) {
            $MainTabId = null;
        }

        $MainTab = new Tab($MainTabId);
        $MainTab->active = true;
        $MainTab->class_name = 'AdminSalusPerAquam';
        $MainTab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $MainTab->name[$lang['id_lang']] = 'SalusPerAquam';
        }
        $MainTab->id_parent = 0;
        $MainTab->module = $this->name;
        $MainTab->save();

        // Sub for "Parameters"
        $ParamTabId = (int) Tab::getIdFromClassName('ParameterController');
        if (!$ParamTabId) {
            $ParamTab = null;
        }

        $ParamTab = new Tab($ParamTabId);
        $ParamTab->active = true;
        $ParamTab->class_name = 'ParameterController';
        $ParamTab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $ParamTab->name[$lang['id_lang']] = 'Parameters';
        }
        $ParamTab->id_parent = $MainTab->id;
        $ParamTab->module = $this->name;
        $ParamTab->save();

        // Sub for "Configuration"
        $ConfigurationTabId = (int) Tab::getIdFromClassName('ConfigurationController');
        if (!$ConfigurationTabId) {
            $ConfigurationTab = null;
        }

        $ConfigurationTab = new Tab($ConfigurationTabId);
        $ConfigurationTab->active = true;
        $ConfigurationTab->class_name = 'ConfigurationController';
        $ConfigurationTab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $ConfigurationTab->name[$lang['id_lang']] = 'Configurations';
        }
        $ConfigurationTab->id_parent = $MainTab->id;
        $ConfigurationTab->module = $this->name;
        $ConfigurationTab->save();

        // Sub for "Access"
        $AccessTabId = (int) Tab::getIdFromClassName('AccessController');
        if (!$AccessTabId) {
            $AccessTab = null;
        }

        $AccessTab = new Tab($AccessTabId);
        $AccessTab->active = true;
        $AccessTab->class_name = 'AccessController';
        $AccessTab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $AccessTab->name[$lang['id_lang']] = 'Accesses';
        }
        $AccessTab->id_parent = $MainTab->id;
        $AccessTab->module = $this->name;
        $AccessTab->save();

        // Sub for "Treatment"
        $TreatmentTabId = (int) Tab::getIdFromClassName('TreatmentController');
        if (!$TreatmentTabId) {
            $TreatmentTab = null;
        }

        $TreatmentTab = new Tab($TreatmentTabId);
        $TreatmentTab->active = true;
        $TreatmentTab->class_name = 'TreatmentController';
        $TreatmentTab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $TreatmentTab->name[$lang['id_lang']] = 'Treatments';
        }
        $TreatmentTab->id_parent = $MainTab->id;
        $TreatmentTab->module = $this->name;
        $TreatmentTab->save();

        return true;
    }

    /**
     * uninstall Tab
     *
     * @return bool
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    private function uninstallTab()
    {
        // Main
        $MainTabId = (int) Tab::getIdFromClassName('AdminSalusPerAquam');
        if ($MainTabId) {
            $Maintab = new Tab($MainTabId);
            $Maintab->delete();
        }

        $ParamTabId = (int) Tab::getIdFromClassName('ParameterController');
        if (!$ParamTabId) {
            return true;
        }
        $ParamTab = new Tab($ParamTabId);
        $ParamTab->delete();

        $ConfigurationTabId = (int) Tab::getIdFromClassName('ConfigurationController');
        if (!$ConfigurationTabId) {
            return true;
        }
        $ConfigurationTab = new Tab($ConfigurationTabId);
        $ConfigurationTab->delete();

        $AccessTabId = (int) Tab::getIdFromClassName('AccessController');
        if (!$AccessTabId) {
            return true;
        }
        $AccessTab = new Tab($AccessTabId);
        $AccessTab->delete();

        $TreatmentTabId = (int) Tab::getIdFromClassName('TreatmentController');
        if (!$TreatmentTabId) {
            return true;
        }
        $TreatmentTab = new Tab($TreatmentTabId);
        $TreatmentTab->delete();

        return true;
    }
}