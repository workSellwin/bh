<?php

/**
 * Interface Service1c
 */
interface Service1cOrder
{
    /**
     * @param array $json
     * @param \Bitrix\Sale\Order $order
     * @return mixed
     */
    public function run(array $json, \Bitrix\Sale\Order $order);
}
