<?php
include $_SERVER['DOCUMENT_ROOT'] . '/1c_exchange/Service1cOrder.php';
include $_SERVER['DOCUMENT_ROOT'] . '/1c_exchange/Service1cUpdateStatus.php';
include $_SERVER['DOCUMENT_ROOT'] . '/1c_exchange/Service1cUpdatePayment.php';

class Service1cUpdateOrder implements Service1cOrder
{
    /**
     * @param array $json
     * @param \Bitrix\Sale\Order $order
     * @return array|mixed
     */
    public function run(array $json, \Bitrix\Sale\Order $order)
    {
        $oStatus = new Service1cUpdateStatus();
        $oPayment = new Service1cUpdatePayment();

        $arResponse1 = $oStatus->run($json, $order);
        $arResponse2 = $oPayment->run($json, $order);

        return array_merge($arResponse1, $arResponse2);
    }
}
