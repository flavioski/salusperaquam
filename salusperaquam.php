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
use Flavioski\Module\SalusPerAquam\MailTemplate\Transformation\CustomMessageColorTransformation;
use PrestaShop\PrestaShop\Core\MailTemplate\Layout\Layout;
use PrestaShop\PrestaShop\Core\MailTemplate\Layout\LayoutInterface;
use PrestaShop\PrestaShop\Core\MailTemplate\Layout\LayoutVariablesBuilderInterface;
use PrestaShop\PrestaShop\Core\MailTemplate\MailTemplateInterface;
use PrestaShop\PrestaShop\Core\MailTemplate\MailTemplateRendererInterface;
use PrestaShop\PrestaShop\Core\MailTemplate\ThemeCatalogInterface;
use PrestaShop\PrestaShop\Core\MailTemplate\ThemeCollectionInterface;
use PrestaShop\PrestaShop\Core\MailTemplate\ThemeInterface;
use PrestaShop\PrestaShop\Core\MailTemplate\Transformation\TransformationCollectionInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

if (!defined('_CAN_LOAD_FILES_')) {
    exit;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

class SalusPerAquam extends Module
{
    public $configurationList = [
        'SALUSPERAQUAM_CONFIGURATION_TEST' => '1',
        'SALUSPERAQUAM_CONFIGURATION_TEST_URL' => 'https://wcf.test:8443/wsdl.php?WSDL',
        'SALUSPERAQUAM_CONFIGURATION_TEST_USERNAME' => 'demo',
        'SALUSPERAQUAM_CONFIGURATION_TEST_PASSWORD' => 'demo',
        'SALUSPERAQUAM_CONFIGURATION_TEST_RESOURCE_GET_TREATMENT' => 'GetTreatment',
        'SALUSPERAQUAM_CONFIGURATION_TEST_RESOURCE_ADD_SALE' => 'AddSale',
        'SALUSPERAQUAM_CONFIGURATION_PRODUCTION' => '0',
        'SALUSPERAQUAM_CONFIGURATION_PRODUCTION_URL' => '',
        'SALUSPERAQUAM_CONFIGURATION_PRODUCTION_USERNAME' => '',
        'SALUSPERAQUAM_CONFIGURATION_PRODUCTION_PASSWORD' => '',
        'SALUSPERAQUAM_CONFIGURATION_PRODUCTION_RESOURCE_GET_TREATMENT' => '',
        'SALUSPERAQUAM_CONFIGURATION_PRODUCTION_RESOURCE_ADD_SALE' => '',
    ];

    /** @var array */
    private $hookList;

    /** @var array */
    private $layoutList;

    public function __construct()
    {
        $this->name = 'salusperaquam';
        $this->tab = 'administrator';
        $this->version = '1.0.0';
        $this->author = 'Flavio Pellizzer';
        $this->need_instance = 0;

        parent::__construct();

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

        $this->hookList = [
            ThemeCatalogInterface::LIST_MAIL_THEMES_HOOK,
            LayoutVariablesBuilderInterface::BUILD_MAIL_LAYOUT_VARIABLES_HOOK,
            MailTemplateRendererInterface::GET_MAIL_LAYOUT_TRANSFORMATIONS,
        ];

        $this->layoutList = [
            'spa',
            'spa_error',
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
        return $this->installTables() &&
            $this->installConfiguration() && parent::install() &&
            $this->registerHooks() &&
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
        foreach (array_keys($this->configurationList) as $name) {
            Configuration::deleteByName($name);
        }

        return $this->removeTables() &&
            $this->uninstallConfiguration() && parent::uninstall() &&
            $this->unregisterHooks() &&
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
            && $this->registerHooks()
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
            && $this->unregisterHooks()
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
            $installer = $this->get('flavioski.module.saluperaquam.treatment.install');
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

        $ConfigurationTabId = (int) Tab::getIdFromClassName('AdminSalusperaquamConfiguration');
        if (!$ConfigurationTabId) {
            return true;
        }
        $ConfigurationTab = new Tab($ConfigurationTabId);
        $ConfigurationTab->delete();

        $TreatmentTabId = (int) Tab::getIdFromClassName('AdminSalusperaquamTreatment');
        if (!$TreatmentTabId) {
            return true;
        }
        $TreatmentTab = new Tab($TreatmentTabId);
        $TreatmentTab->delete();

        return true;
    }

    /**
     * @return bool
     */
    private function registerHooks()
    {
        foreach ($this->hookList as $hookName) {
            if (!$this->registerHook($hookName)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    private function unregisterHooks()
    {
        foreach ($this->hookList as $hookName) {
            if (!$this->unregisterHook($hookName)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Install configuration for each shop
     *
     * @return bool
     */
    public function installConfiguration()
    {
        $result = true;

        foreach (\Shop::getShops(false, null, true) as $shopId) {
            foreach ($this->configurationList as $name => $value) {
                if (false === Configuration::hasKey($name, null, null, (int) $shopId)) {
                    $result = $result && (bool) Configuration::updateValue(
                            $name,
                            $value,
                            false,
                            null,
                            (int) $shopId
                        );
                }
            }
        }

        return $result;
    }

    /**
     * Uninstall configuration
     *
     * @return bool
     */
    public function uninstallConfiguration()
    {
        foreach($this->configurationList as $name => $value) {
            if (!Configuration::deleteByName($name)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return void
     */
    public function getContent()
    {
        // This controller actually does not exist, it is used in the tab
        // and is accessible thanks to routing settings with _legacy_link
        Tools::redirectAdmin(
            Context::getContext()->link->getAdminLink('AdminSalusperaquamConfiguration')
        );
    }

    /**
     * @param array $hookParams
     */
    public function hookActionListMailThemes(array $hookParams)
    {
        if (!isset($hookParams['mailThemes'])) {
            return;
        }

        /** @var ThemeCollectionInterface $themes */
        $themes = $hookParams['mailThemes'];

        $this->addAdditionalLayout($themes);
        $this->extendOrderConfLayout($themes);
    }

    /**
     * @param ThemeCollectionInterface $themes
     */
    private function addAdditionalLayout(ThemeCollectionInterface $themes)
    {
        /** @var ThemeInterface $theme */
        foreach ($themes as $theme) {
            if (!in_array($theme->getName(), ['classic', 'modern'])) {
                continue;
            }

            // Add a layout to each theme (don't forget to specify the module name)
            foreach ($this->layoutList as $layoutName) {
                $theme->getLayouts()->add(new Layout(
                    $layoutName,
                    __DIR__ . '/mails/layouts/' . $layoutName . '_' . $theme->getName() . '_layout.html.twig',
                    '',
                    $this->name
                ));
            }
        }
    }

    /**
     * @param ThemeCollectionInterface $themes
     */
    private function extendOrderConfLayout(ThemeCollectionInterface $themes)
    {
        /** @var ThemeInterface $theme */
        foreach ($themes as $theme) {
            if (!in_array($theme->getName(), ['modern'])) {
                continue;
            }

            // First parameter is the layout name, second one is the module name (empty value matches the core layouts)
            $orderConfLayout = $theme->getLayouts()->getLayout('order_conf', '');
            if (null === $orderConfLayout) {
                return;
            }

            // The layout collection extends from ArrayCollection, so it has more feature than it seems.
            // It allows to REPLACE the existing layout easily
            $orderIndex = $theme->getLayouts()->indexOf($orderConfLayout);
            $theme->getLayouts()->offsetSet($orderIndex, new Layout(
                $orderConfLayout->getName(),
                __DIR__ . '/mails/layouts/extended_' . $theme->getName() . '_order_conf_layout.html.twig',
                ''
            ));
        }
    }

    /**
     * @param array $hookParams
     */
    public function hookActionBuildMailLayoutVariables(array $hookParams)
    {
        if (!isset($hookParams['mailLayout'])) {
            return;
        }

        /** @var LayoutInterface $mailLayout */
        $mailLayout = $hookParams['mailLayout'];
        if ($mailLayout->getModuleName() != $this->name || ($mailLayout->getName() != 'spa' && $mailLayout->getName() != 'spa_error')) {
            return;
        }

        $locale = $hookParams['mailLayoutVariables']['locale'];
        if (strpos($locale, 'it') === 0) {
            $hookParams['mailLayoutVariables']['customMessage'] = 'I nostri sistemi hanno aggiunto la tua prenotazione. Non dimenticarti di stampare l\'ordine e portartelo appresso quando verrai da noi.';
            $hookParams['mailLayoutVariables']['customErrorMessageReference'] = 'Codice Ordine: {reference}';
            $hookParams['mailLayoutVariables']['customErrorMessageDetail'] = 'Dettagli: {details}';
        } else {
            $hookParams['mailLayoutVariables']['customMessage'] = 'Our system has added your reservation. Don\'t forget to print your order and bring it to us.';
            $hookParams['mailLayoutVariables']['customErrorMessageReference'] = 'Order Reference: {reference}';
            $hookParams['mailLayoutVariables']['customErrorMessageDetail'] = 'Details: {details}';
        }
    }

    /**
     * @param array $hookParams
     */
    public function hookActionGetMailLayoutTransformations(array $hookParams)
    {
        if (!isset($hookParams['templateType']) ||
            MailTemplateInterface::HTML_TYPE !== $hookParams['templateType'] ||
            !isset($hookParams['mailLayout']) ||
            !isset($hookParams['layoutTransformations'])) {
            return;
        }

        /** @var LayoutInterface $mailLayout */
        $mailLayout = $hookParams['mailLayout'];
        if ($mailLayout->getModuleName() != $this->name) {
            return;
        }

        /** @var TransformationCollectionInterface $transformations */
        $transformations = $hookParams['layoutTransformations'];
        $transformations->add(new CustomMessageColorTransformation('#FF0000'));
    }
}
