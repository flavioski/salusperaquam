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

use Carrier;
use Cart;
use Configuration;
use Context;
use Currency;
use Customer;
use Employee;
use Language;
use Order;
use OrderHistory;
use OrderState;
use PrestaShop\PrestaShop\Adapter\LegacyContext as ContextAdapter;
use PrestaShop\PrestaShop\Core\Domain\Order\Exception\ChangeOrderStatusException;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Shop;
use StockAvailable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class WebServiceChangeOrderStatusCommand extends Command
{
    use LoggerAwareTrait;
    use LockableTrait;

    /**
     * @var ContextAdapter
     */
    public $context;

    /**
     * @param LoggerInterface|null $logger
     * @param ContextAdapter $contextAdapter
     */
    public function __construct(LoggerInterface $logger = null,
                                ContextAdapter $contextAdapter)
    {
        parent::__construct();
        $this->logger = null !== $logger ? $logger : new NullLogger();
        $this->context = $contextAdapter->getContext();
    }

    protected function configure()
    {
        $this
            // The name of the command (the part after "bin/console")
            ->setName('salusperaquam:webservice:change-order-status')

            // the short description shown while running "php bin/console list"
            ->setDescription('Change order status. Please use \'-h\' to display option params.')

            ->addArgument(
                'idOrder', InputArgument::REQUIRED, 'The Id of the Order that you desire change status.'
            )

            ->addOption(
                'idOrderStatus',
                null,
                InputOption::VALUE_OPTIONAL,
                'Id of Order Status',
                5
            )

            ->addOption(
                'idEmployee',
                null,
                InputOption::VALUE_OPTIONAL,
                'Id of Employee',
                1
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
        $output->writeln('WebServiceChangeOrderStatusCommand::execute begin');

        $orderId = $input->getArgument('idOrder') ? $input->getArgument('idOrder') : 0;
        $optionValueIdOrderStatus = $input->getOption('idOrderStatus');
        $optionValueIdEmployee = $input->getOption('idEmployee');

        $this->context->employee = new Employee((int) $optionValueIdEmployee);

        $ordersWithFailedToUpdateStatus = [];
        $ordersWithFailedToSendEmail = [];
        $ordersWithAssignedStatus = [];

        if ($orderId) {
            $order = new Order((int) $orderId);

            if ($order->current_state !== $optionValueIdOrderStatus) {
                $orderState = new OrderState((int) $optionValueIdOrderStatus);
                $currentOrderState = $order->getCurrentOrderState();

                if ($currentOrderState->id === $orderState->id) {
                    $ordersWithAssignedStatus[] = $orderId;
                }

                if ($currentOrderState->id != $orderState->id) {
                    // $this->context->language = new Language((int) $order->id_lang);
                    // $this->context->cart = new Cart((int) $order->id_cart);
                    // $this->context->shop = new Shop((int) $this->context->cart->id_shop);
                    // $this->context->customer = new Customer((int) $this->context->cart->id_customer);
                    $this->context->currency = new Currency((int) $order->id_currency, null, (int) $this->context->shop->id);

                    $history = new OrderHistory();
                    $history->id_order = $order->id;
                    $history->id_employee = (int) $this->context->employee->id;

                    $useExistingPayment = !$order->hasInvoice();
                    $history->changeIdOrderState((int) $orderState->id, $order, $useExistingPayment);

                    $carrier = new Carrier($order->id_carrier, (int) $order->id_lang);
                    $templateVars = [];

                    if ($history->id_order_state == Configuration::get('PS_OS_SHIPPING') && $order->shipping_number) {
                        $templateVars['{followup}'] = str_replace('@', $order->shipping_number, $carrier->url);
                    }

                    if (!$history->add()) {
                        $ordersWithFailedToUpdateStatus[] = $orderId;
                    }

                    if (!$history->sendEmail($order, $templateVars)) {
                        $ordersWithFailedToSendEmail[] = $orderId;
                    }

                    if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                        foreach ($order->getProducts() as $product) {
                            if (StockAvailable::dependsOnStock($product['product_id'])) {
                                StockAvailable::synchronize($product['product_id'], (int) $product['id_shop']);
                            }
                        }
                    }

                    if (!empty($ordersWithFailedToUpdateStatus)
                        || !empty($ordersWithFailedToSendEmail)
                        || !empty($ordersWithAssignedStatus)
                    ) {
                        throw new ChangeOrderStatusException($ordersWithFailedToUpdateStatus, $ordersWithFailedToSendEmail, $ordersWithAssignedStatus, 'Failed to update status or sent email when changing order status.');
                    }
                }
            }
        }

        // if not released explicitly, Symfony releases the lock
        // automatically when the execution of the command ends
        $this->release();

        return 0;
    }
}
