<?php

use SilverStripe\ORM\DataObject;
use SilverStripe\Control\Director;

class PaymentTransaction extends DataObject
{
    private static $db = [
        'TransactionID' => 'Varchar(100)',
        'PaymentGateway' => 'Varchar(50)',
        'Amount' => 'Currency',
        'Status' => 'Varchar(50)',
        'PaymentURL' => 'Varchar(255)',
        'ResponseData' => 'Text',
    ];

    private static $has_one = [
        'Order' => Order::class,
    ];

    private static $summary_fields =
    [
        "Order.OrderCode" => "Order Code",
        "PaymentGateway" => "Payment Gateway",
        "TransactionID" => "Transaction ID",
        "Amount" => "Amount",
        "Status" => "Status",
        "ResponseData" => "Response Data",
        "CreateAt" => "Create At",
    ];

    private static $casting = [
        'OrderSummary' => 'HTMLText'
    ];

    public function getOrderSummary()
    {
        if ($this->Order()->exists()) {
            $order = $this->Order();
            $adminURL = Director::absoluteBaseURL() . 'admin/orders/Order/EditForm/field/Order/item/' . $order->ID . '/edit';
            return sprintf(
                '<a href="%s">%s — %s (Rp %s)</a>',
                $adminURL,
                $order->OrderCode,
                $order->Member()->FirstName ?? 'Unknown',
                number_format($order->getGrandTotal(), 0, ',', '.')
            );
        }
        return '—';
    }

    public function Title()
    {
        return $this->TransactionID ?: 'Transaksi #' . $this->ID;
    }
}
