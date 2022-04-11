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

use Flavioski\Module\SalusPerAquam\WebService\AddSale;
use Psr\Log\LoggerAwareTrait;
use PSR\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Translation\TranslatorInterface;

class WebServiceAddSaleCommand extends Command
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
     * @var AddSale
     */
    private $addSale;

    /**
     * @param LoggerInterface|null $logger
     * @param TranslatorInterface $translator
     * @param AddSale $addSale
     */
    public function __construct(LoggerInterface $logger = null, TranslatorInterface $translator, AddSale $addSale)
    {
        parent::__construct();
        $this->logger = null !== $logger ? $logger : new NullLogger();
        $this->translator = $translator;
        $this->addSale = $addSale;
    }

    protected function configure()
    {
        $this
            // The name of the command (the part after "bin/console")
            ->setName('salusperaquam:webservice:addsale')

            // the short description shown while running "php bin/console list"
            ->setDescription('Add sales to web service.')
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
        $this->logger->info($this->translator->trans(
            'WebServiceAddSaleCommand::execute begin',
            [],
            'Modules.Salusperaquam.Notification'
        ));

        $this->addSale->setCustomerFirstname('firstname');
        $this->addSale->setCustomerLastname('lastname');
        $this->addSale->setCustomerDni('mydni');
        $this->addSale->setCustomerEmail('email@email.it');
        $this->addSale->setSaleCode('AAA4567999DFA');
        $this->addSale->setSaleTotal('113.50');
        $this->addSale->addTotalDetail(['treatment_code' => 'ingresso-giornaliero-feriale-adulti', 'quantity' => 2]);
        $this->addSale->addTotalDetail(['treatment_code' => 'ingresso-sauna', 'quantity' => 3]);
        $this->addSale->Request();

        $output->writeln('Added Sale to Web Service done!');

        $this->logger->info($this->translator->trans(
            'WebServiceAddSaleCommand::execute end',
            [],
            'Modules.Salusperaquam.Notification'
        ));

        // if not released explicitly, Symfony releases the lock
        // automatically when the execution of the command ends
        $this->release();

        return 0;
    }
}
