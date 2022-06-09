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

use Address;
use Context;
use Customer;
use Doctrine\ORM\EntityManager;
use Flavioski\Module\SalusPerAquam\Entity\Treatment;
use Flavioski\Module\SalusPerAquam\WebService\AddSale;
use Flavioski\Module\SalusPerAquam\WebService\Exception\WebServiceException;
use Language;
use Mail;
use Order;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
     * @var AddSale
     */
    private $addSale;

    /**
     * @param LoggerInterface|null $logger
     * @param TranslatorInterface $translator
     * @param EntityManager $entityManager
     * @param int $defaultLanguageId
     * @param string $shopEmail
     * @param int $defaultShopId
     * @param AddSale $addSale
     */
    public function __construct(LoggerInterface $logger = null, TranslatorInterface $translator,
                                EntityManager $entityManager = null,
                                $defaultLanguageId,
                                $shopEmail,
                                $defaultShopId,
                                AddSale $addSale)
    {
        parent::__construct();
        $this->logger = null !== $logger ? $logger : new NullLogger();
        $this->translator = $translator;
        $this->entityManager = $entityManager;
        $this->defaultLanguageId = $defaultLanguageId;
        $this->shopEmail = $shopEmail;
        $this->defaultShopId = $defaultShopId;
        $this->addSale = $addSale;
    }

    protected function configure()
    {
        $this
            // The name of the command (the part after "bin/console")
            ->setName('salusperaquam:webservice:add-sale')

            // the short description shown while running "php bin/console list"
            ->setDescription('Add sales to web service. Please use \'-h\' to display option params.')

            ->addOption(
                'dateFrom',
                'f',
                InputOption::VALUE_OPTIONAL,
                'Date from get order (Y-m-d)',
                date('Y-m-d', time())
            )

            ->addOption(
                'dateTo',
                't',
                InputOption::VALUE_OPTIONAL,
                'Date to get order (Y-m-d)',
                date('Y-m-d', time())
            )

            ->addOption(
                'idOrderStatus',
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'Id of order status',
                [2, 11]
            )

            ->addOption(
                'idCustomer',
                null,
                InputOption::VALUE_OPTIONAL,
                'Id customer'
            )

            ->addOption(
                'typeOrder',
                null,
                InputOption::VALUE_OPTIONAL,
                'Type order (like shipping)'
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
        $output->writeln('WebServiceAddSaleCommand::execute begin');

        $optionValueDateFrom = $input->getOption('dateFrom');
        $optionValueDateTo = $input->getOption('dateTo');
        $optionValueIdOrderStatus = $input->getOption('idOrderStatus');
        $optionValueIdCustomer = $input->getOption('idCustomer');
        $optionValueTypeOrder = $input->getOption('typeOrder');

        // $dateFrom = ($optionValueDateFrom !== false);
        // $dateTo = ($optionValueDateTo !== false);
        // $idOrderStatus = ($optionValueIdOrderStatus !== false);
        // $idCustomer = ($optionValueIdCustomer !== false);
        // $typeOrder = ($optionValueTypeOrder !== false);

        $commandChangeOrderStatus = $this->getApplication()->find('salusperaquam:webservice:change-order-status');

        $orders = Order::getOrdersIdByDate($optionValueDateFrom, $optionValueDateTo, $optionValueIdCustomer, $optionValueTypeOrder);
        $treatmentEntityRepository = $this->entityManager->getRepository(Treatment::class);

        if ($orders) {
            foreach ($orders as $order_id) {
                $order = new Order($order_id);
                if (in_array($order->current_state, $optionValueIdOrderStatus)) {
                    $customer = new Customer($order->id_customer);
                    $customer_address_invoice = new Address($order->id_address_invoice);
                    $customer_address_delivery = (($order->id_address_invoice != $order->id_address_delivery) ?
                        new Address($order->id_address_delivery) : $customer_address_invoice);

                    $customer_dni = $customer_address_invoice->dni !== '' ? strtoupper($customer_address_invoice->dni) : 'AAAAAAAAA';

                    $this->addSale->setCustomerFirstname($customer_address_invoice->firstname);
                    $this->addSale->setCustomerLastname($customer_address_invoice->lastname);
                    $this->addSale->setCustomerDni($customer_dni);
                    $this->addSale->setCustomerEmail($customer->email);
                    $this->addSale->setSaleCode($order->reference);
                    $this->addSale->setSaleTotal($order->total_paid_tax_incl);

                    $order_details = $order->getOrderDetailList();

                    foreach ($order_details as $order_detail) {
                        $treatment = $treatmentEntityRepository->findOneBy([
                            'productId' => $order_detail['product_id'],
                            'productAttributeId' => $order_detail['product_attribute_id'] ?: null,
                            'active' => 1,
                        ]);

                        if ($treatment instanceof Treatment) {
                            $this->addSale->addTotalDetail([
                                'treatment_code' => $treatment->getCode(),
                                'quantity' => $order_detail['product_quantity'],
                            ]);
                        } else {
                            $this->logger->warning($this->translator->trans(
                                'No active treatment association for Order no. %d',
                                [$order_id],
                                'Modules.Salusperaquam.Notification'
                            ));
                            $output->writeln('no active treatment association for Order no. ' . $order_id);
                        }
                    }

                    if ($this->addSale->getTotalDetail()) {
                        $response = $this->addSale->Request();

                        if ($response instanceof WebServiceException) {
                            $this->logger->error($this->translator->trans(
                                'There are some problems with Web Service while handle Order no. %d!',
                                [$order_id],
                                'Modules.Salusperaquam.Notification'
                            ), ['object_type' => 'WebServiceAddSaleCommand']);

                            $this->sendError($order_id, $order->reference, $response->getMessage());

                            throw new WebServiceException(sprintf('Some problems with web Service "%s"', $response->getMessage()), $response->getCode());
                        }

                        if ($response->Success == 1) {
                            $this->logger->info($this->translator->trans(
                                'Added Sale to Web Service done for Order no. %d!',
                                [$order_id],
                                'Modules.Salusperaquam.Notification'
                            ));

                            $arguments = [
                                'command' => 'salusperaquam:webservice:change-order-status',
                                'idOrder' => $order_id,
                            ];

                            $orderInput = new ArrayInput($arguments);

                            $returnCode = $commandChangeOrderStatus->run($orderInput, $output);

                            $params = [
                                'firstname' => $customer_address_invoice->firstname,
                                'lastname' => $customer_address_invoice->lastname,
                                'id_language' => (int) $order->id_lang,
                                'id_shop' => (int) $order->id_shop,
                            ];
                            $this->sendVoucher($customer->email, $order->reference, $params);
                        } else {
                            $this->logger->warning($this->translator->trans(
                                'Added Sale to Web Service problem for Order no. %d!',
                                [$order_id],
                                'Modules.Salusperaquam.Notification'
                            ));

                            $this->sendError($order_id, $order->reference);
                        }

                        $output->writeln('Added Sale to Web Service done for Order no. ' . $order_id . '!');
                    }
                }
            }
        }

        $output->writeln('WebServiceAddSaleCommand::execute end');

        // if not released explicitly, Symfony releases the lock
        // automatically when the execution of the command ends
        $this->release();

        return 0;
    }

    protected function sendVoucher($email, $code, $params)
    {
        $language = new Language($params['id_language']);

        return Mail::send(
            $params['id_language'],
            'spa',
            Context::getContext()->getTranslator()->trans(
                'SPA voucher',
                [],
                'Emails.Subject',
                $language->locale
            ),
            [
                '{code}' => $code,
            ],
            $email,
            null,
            null,
            null,
            null,
            null,
            dirname(__FILE__) . '/mails/',
            false,
            $params['id_shop']
        );
    }

    protected function sendError($orderId, $code, $details = '')
    {
        $language = new Language((int) $this->defaultLanguageId);

        return Mail::send(
            (int) $this->defaultLanguageId,
            'spa_error',
            Context::getContext()->getTranslator()->trans(
                'SPA Error for Order id %d',
                [$orderId],
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
