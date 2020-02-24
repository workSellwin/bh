<?php

include $_SERVER['DOCUMENT_ROOT'] . '/1c_exchange/Service1cOrder.php';
class Service1cUpdateStatus implements Service1cOrder
{

    /**
     * @param array $json
     * @param \Bitrix\Sale\Order $order
     * @return array|mixed
     */
    public function run(array $json, \Bitrix\Sale\Order $order)
    {
        $arResponse = ['status' => 'ok', 'error' => [],];
        if (!$status = (string)trim($json['status'])) $arResponse['error'][] = 'No status!';
        $arStatus = $this->GetAllStatus();
        if(!in_array($status,$arStatus)){
            $arResponse['error'][] = 'No Status code!';
        }else{
            $order->setField('STATUS_ID',$status);
        }
        return $arResponse;
    }


    protected function GetAllStatus()
    {
        $db_statuses = array();
        $arOrder = array('ID', 'NAME');
        $db_sales = \CSaleStatus::GetList($arOrder, [], false, false, []);
        while ($existStatus = $db_sales->Fetch()) {
            $db_statuses[] = $existStatus['ID'];
        }
        return $db_statuses;
    }
}
