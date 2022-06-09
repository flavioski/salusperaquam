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

namespace Flavioski\Module\SalusPerAquam\Command;

use Context;
use Doctrine\ORM\EntityManager;
use Flavioski\Module\SalusPerAquam\Entity\Treatment;
use Flavioski\Module\SalusPerAquam\WebService\GetTreatment;
use Flavioski\Module\SalusPerAquam\WebService\Exception\WebServiceException;
use Language;
use Mail;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Translation\TranslatorInterface;

class WebServiceGetTreatmentCommand extends Command
{
    use LoggerAwareTrait;
    use LockableTrait;

    /**
     * Translator.
     *
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * EntityManager for module treatment.
     *
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var int
     */
    private $defaultLanguageId;

    /**
     * @var string
     */
    private $shopEmail;

    /**
     * @var int
     */
    private $defaultShopId;

    /**
     * @var GetTreatment
     */
    private $getTreatment;

    public function __construct(LoggerInterface $logger = null, TranslatorInterface $translator,
                                EntityManager $entityManager = null,
                                $defaultLanguageId,
                                $shopEmail,
                                $defaultShopId,
                                GetTreatment $getTreatment)
    {
        parent::__construct();
        $this->logger = null !== $logger ? $logger : new NullLogger();
        $this->translator = $translator;
        $this->entityManager = $entityManager;
        $this->defaultLanguageId = $defaultLanguageId;
        $this->shopEmail = $shopEmail;
        $this->defaultShopId = $defaultShopId;
        $this->getTreatment = $getTreatment;
    }

    protected function configure()
    {
        $this
            // The name of the command (the part after "bin/console")
            ->setName('salusperaquam:webservice:get-treatment')

            // the short description shown while running "php bin/console list"
            ->setDescription('Get treatments from web service. Please use \'-h\' to display option params.')

            ->addOption(
                'treatmentCode',
                null,
                InputOption::VALUE_OPTIONAL,
                'Treatment code'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->lock()) {
            $output->writeln('The command is already running in another process.');

            return 0;
        }

        // If you prefer to wait until the lock is released, use this:
        // $this->lock(null, true);

        // Here your business logic.
        // ...

        // this is a demo of implement
        $output->writeln('WebServiceGetTreatmentCommand::execute begin');

        $optionValueTreatmentCode = $input->getOption('treatmentCode');


        $output->writeln('WebServiceGetTreatmentCommand::execute end');

        // if not released explicitly, Symfony releases the lock
        // automatically when the execution of the command ends
        $this->release();

        return 0;
    }

    protected function sendError($treatmentCode, $code, $details = '')
    {
        $language = new Language((int) $this->defaultLanguageId);

        return Mail::send(
            (int) $this->defaultLanguageId,
            'spa_error',
            Context::getContext()->getTranslator()->trans(
                'SPA Error for treatment code %s',
                [$treatmentCode],
                'Emails.Subject',
                $language->locale
            ),
            [
                '{reference}' => $code,
                '{details}' => $details,
            ],
            $this->shopEmail,
            null,
            null,
            null,
            null,
            null,
            dirname(__FILE__) . '/mails/',
            false,
            (int) $this->defaultShopId
        );
    }
}
