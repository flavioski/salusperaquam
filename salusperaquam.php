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
declare(strict_types=1);

use Flavioski\Module\SalusPerAquam\Database\TreatmentInstaller;

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
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
            'Modules.Salusperaquam.Admin'
        );

        $this->description =
            $this->getTranslator()->trans(
                'Salus Per Aquam webService thermae.',
                [],
                'Modules.Salusperaquam.Admin'
            );

        $this->ps_versions_compliancy = [
            'min' => '1.7.8.4',
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
        return $this->installTables() && parent::install() &&
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
        return $this->removeTables() && parent::uninstall() &&
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
     * @return bool
     */
    private function installTables()
    {
        /** @var TreatmentInstaller $installer */
        $installer = $this->getInstaller();
        $errors = $installer->createTables();

        return empty($errors);
    }

    /**
     * @return bool
     */
    private function removeTables()
    {
        /** @var TreatmentInstaller $installer */
        $installer = $this->getInstaller();
        $errors = $installer->dropTables();

        return empty($errors);
    }

    /**
     * @return TreatmentInstaller
     */
    private function getInstaller()
    {
        try {
            $installer = $this->get('prestashop.module.saluperaquam.treatment.install');
        } catch (Exception $e) {
            // Catch exception in case container is not available, or service is not available
            $installer = null;
        }

        // During install process the module's service is not available yet, so we build it manually
        if (!$installer) {
            $installer = new TreatmentInstaller(
                $this->get('doctrine.dbal.default_connection'),
                $this->getContainer()->getParameter('database_prefix')
            );
        }

        return $installer;
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
            $MainTab->name[$lang['id_lang']] = 'Salus Per Aquam';
        }
        $MainTab->id_parent = 0;
        $MainTab->module = $this->name;
        $MainTab->save();

        // Sub for "Configuration"
        $ConfigurationTabId = (int) Tab::getIdFromClassName('AdminSalusperaquamConfiguration');
        if (!$ConfigurationTabId) {
            $ConfigurationTab = null;
        }

        $ConfigurationTab = new Tab($ConfigurationTabId);
        $ConfigurationTab->active = true;
        $ConfigurationTab->class_name = 'AdminSalusperaquamConfiguration';
        $ConfigurationTab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $ConfigurationTab->name[$lang['id_lang']] = 'Configuration';
        }
        $ConfigurationTab->id_parent = $MainTab->id;
        $ConfigurationTab->module = $this->name;
        $ConfigurationTab->save();

        // Sub for "Treatment"
        $TreatmentTabId = (int) Tab::getIdFromClassName('AdminSalusperaquamTreatment');
        if (!$TreatmentTabId) {
            $TreatmentTab = null;
        }

        $TreatmentTab = new Tab($TreatmentTabId);
        $TreatmentTab->active = true;
        $TreatmentTab->class_name = 'AdminSalusperaquamTreatment';
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

        $ConfigurationTabId = (int) Tab::getIdFromClassName('ConfigurationController');
        if (!$ConfigurationTabId) {
            return true;
        }
        $ConfigurationTab = new Tab($ConfigurationTabId);
        $ConfigurationTab->delete();

        $TreatmentTabId = (int) Tab::getIdFromClassName('TreatmentsController');
        if (!$TreatmentTabId) {
            return true;
        }
        $TreatmentTab = new Tab($TreatmentTabId);
        $TreatmentTab->delete();

        return true;
    }
}
