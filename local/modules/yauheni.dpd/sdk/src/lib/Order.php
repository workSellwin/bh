<?php
namespace Ipol\DPD;

use \Ipol\DPD\Utils;
use \Ipol\DPD\DB\Order\Model;
use \Ipol\DPD\API\User\User as API;
use \Ipol\DPD\Config\ConfigInterface;
use \Ipol\DPD\Currency\ConverterInterface;
use \Ipol\DPD\DB\Connection as DB;

/**
 * Класс для работы со внешним заказом DPD
 */
class Order
{
	/**
	 * Новый заказ, еще не отправлялся в DPD
	 */
	const STATUS_NEW          = 'NEW';

	/**
	 * Получена заявка
	 */
	const STATUS_OFFER_CREATE = 'OfferCreate';

	/**
	 * ​В заявке присутствует ошибка
	 */
	const STATUS_OFFER_ERROR  = 'OfferUpdating';

	/**
	 * ​Запрошены паспортные данные получателя
	 */
	const STATUS_OFFER_WAITING = 'OfferWaiting';
	
	/**
	 * ​Отмена заявки
	 */
	const STATUS_OFFER_CANCEL = 'OfferCancelled';
	
	/**
	 * Заказ создан в DPD
	 */
	const STATUS_OK              = 'OK';
	
	/**
	 * Заказ требует ручной обработки
	 */
	const STATUS_PENDING = 'OrderPending';
	
	/**
	 * ​​Заказ ожидает дату приема
	 */
	const STATUS_WAITING = 'OrderWaiting';

	/**
	 * Ошибка с заказом
	 */
	const STATUS_ERROR   = 'OrderError';

	/**
	 * Заказ отменен
	 */
	const STATUS_CANCEL  = 'OrderCancelled';

	/**
	 * Заказ отменен ранее
	 */
	const STATUS_CANCEL_PREV = 'CanceledPreviously';

	/**
	 * Заказ отменен
	 */
	const STATUS_NOT_DONE = 'NotDone';

	/**
	 * Заказ принят у отпровителя
	 */
	const STATUS_DEPARTURE = 'OnTerminalPickup';

	/**
	 * Заказ прибыл в страну доставки
	 */
	const STATUS_ARRIVED_IN_RF = 'OrderArrivedInRF';

	/**
	 * Посылка находится в пути (внутренняя перевозка DPD)
	 */
	const STATUS_TRANSIT          = 'OnRoad';

	/**
	 * Посылка находится на транзитном терминале
	 */
	const STATUS_TRANSIT_TERMINAL = 'OnTerminal';

	/**
	 * ​Заказ готов к выдаче на пункте
	 */
	const STATUS_ARRIVE_PICKUP    = 'OnTerminalDeliveryPickup';

	/**
	 * ​Заказ готов к передаче курьеру для доставки
	 */
	const STATUS_ARRIVE           = 'OnTerminalDelivery';
	const STATUS_ARRIVE_COURIER   = 'OnTerminalDelivery';
	
	/**
	 * ​Заказ следует по маршруту до терминала возврата
	 */
	const STATUS_TRANSIT_RETURN = 'OnRoadReturn';

	/**
	 * ​Заказ на возврат готов к передаче  курьеру для доставки
	 */
	const STATUS_ARRIVE_COURIER_RETURN  = 'OnTerminalDeliveryReturn';

	/**
	 *​ Заказ на возврат готов к выдаче
	 */
	const STATUS_ARRIVE_PICKUP_RETURN   = 'OnTerminalDeliveryPickupReturn';

	/**
	 * Таможенное оформление в стране отправления
	 */
	const STATUS_CUSTOMS_CLEARANCE = 'CustomClearance';
	
	/**
	 * ​Закончено таможенное оформление в стране отправления
	 */
	const STATUS_END_CUSTOMS_CLEARANCE = 'EndCustomClearance';

	/**
	 * ​Закончено таможенное оформление в стране отправления
	 */
	const STATUS_END_CUSTOMS_CLEARANCE_IN_RF = 'EndCustomClearanceInRF';

	/**
	 * Посылка выведена на доставку
	 */
	const STATUS_COURIER          = 'Delivering';
	
	/**
	 * Посылка выведена на доставку
	 */
	const STATUS_COURIER_RETURN   = 'DeliveringReturn';

	/**
	 * Посылка доставлена получателю
	 */
	const STATUS_DELIVERED        = 'Delivered';

	/**
	 * Посылка утеряна
	 */
	const STATUS_LOST             = 'Lost';

	/**
	 * с посылкой возникла проблемная ситуация 
	 */
	const STATUS_PROBLEM          = 'Problem';
	
	/**
	 * ​Отказ от заказа в момент доставки
	 */
	const STATUS_DELIVERY_PROBLEM = 'OrderDeliveryProblem';

	/**
	 * Посылка возвращена с доставки
	 */
	const STATUS_RETURNED         = 'ReturnedFromDelivery';

	/**
	 * Оформлен новый заказ по инициативе DPD
	 */
	const STATUS_NEW_DPD          = 'NewOrderByDPD';

	/**
	 * Оформлен новый заказ по инициативе клиента
	 */
	const STATUS_NEW_CLIENT       = 'NewOrderByClient';

	/**
	 * Передано спецперевозчику
	 */
	const STATUS_TRANSIT_SPEC = 'OrderDelivering_2310';

	/**
	 * ​Заказ утилизирован
	 */
	const STATUS_REMOVED = 'OrderWorkCompleted_3301';

	/**
	 * ​Посылка не востребована
	 */
	const STATUS_NOT_CLAIMED = 'OrderWorkCompleted_3302';

	/**
	 * Оплата у получатела налом
	 */
	const PAYMENT_TYPE_OUP = 'ОУП';

	/**
	 * Оплата у отправителя наличными
	 */
	const PAYMENT_TYPE_OUO = 'ОУО';

	/**
	 * @var \Ipol\DPD\DB\Order\Model
	 */
	protected $model;

	/**
	 * @var \Ipol\DPD\API\User\UserInterface
	 */
	protected $api;

	/**
	 * @var \Ipol\DPD\Currency\ConverterInterface
	 */
	protected $currencyConverter;

	/**
	 * @var string
	 */
	protected $sourceName;

	/**
	 * Конструктор класса
	 * 
	 * @param \Ipol\DPD\DB\Order\Model $model одна запись из таблицы
	 */
	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	/**
	 * @return \Ipol\DPD\Config\ConfigInterface
	 */
	public function getConfig()
	{
		return $this->model->getTable()->getConfig();
	}

	public function getDB()
	{
		return $this->model->getTable()->getConnection();
	}

	/**
	 * Устанавливает конвертер валюты
	 */
	public function setCurrencyConverter(ConverterInterface $converter)
	{
		$this->currencyConverter = $converter;

		return $this;
	}

	/**
	 * Возвращает конвертер валюты
	 */
	public function getCurrencyConverter()
	{
		return $this->currencyConverter;
	}

	/**
	 * Возвращает инстанс API
	 * 
	 * Если оплата идет наложенным платежем будет возвращен аккаунт привязанный к валюте заказа, 
	 * при условии что он указан. 
	 * 
	 * @return \Ipol\DPD\User\UserInterface
	 */
	public function getApi()
	{
		if ($this->api) {
			return $this->api;
		}

		$location = $this->model->getShipment()->getReceiver();
		if (API::isActiveAccount($this->getConfig(), $location['COUNTRY_CODE'])) {
			return $this->api = API::getInstanceByConfig($this->getConfig(), $location['COUNTRY_CODE']);
		}	

		return $this->api = API::getInstanceByConfig($this->getConfig());

	}

	/**
	 * Создает заказ в системе DPD
	 * 
	 * @return \Ipol\DPD\Result
	 */
	public function create()
	{
		$result = new Result();

		try {
			$this->getDB()->getPDO()->beginTransaction();

			if (!$this->model->save()) {
				throw new \Exception('Failed to save data model');
			}

			$shipment = $this->model->getShipment(true);
			if ($shipment->getSelfDelivery()) {
				$terminal = $this->getDB()->getTable('terminal')->getByCode($this->model->receiverTerminalCode);
				
				if (!$terminal) {
					throw new \Exception('Терминал назначения не найден');
				}

				if ($this->model->getSumNpp() > 0 && !$terminal->checkShipmentPayment($shipment)) {
					throw new \Exception('Терминал назначения не может принять наложенный платеж');
				}
			}

			$parms = array(
				'HEADER' => array_filter(array(
					'DATE_PICKUP'        => $this->model->pickupDate,
					'SENDER_ADDRESS'     => $this->getSenderInfo(),
					'PICKUP_TIME_PERIOD' => $this->model->pickupTimePeriod,
					'REGULAR_NUM'        => $this->getConfig()->get('SENDER_REGULAR_NUM', ''),
					'PAYER'              => $this->model->paymentType == static::PAYMENT_TYPE_OUP ? '1001028502' : null,
				)),

				'ORDER' => array(
					'ORDER_NUMBER_INTERNAL' => $this->model->orderId,
					'SERVICE_CODE'          => $this->model->serviceCode,
					'SERVICE_VARIANT'       => $this->model['SERVICE_VARIANT'],
					'CARGO_NUM_PACK'        => $this->model->cargoNumPack,
					'CARGO_WEIGHT'          => $this->model->cargoWeight,
					'CARGO_VOLUME'          => $this->model->cargoVolume,
					// 'CARGO_REGISTERED'      => $this->model->cargoRegistered == 'Y',
					'CARGO_REGISTERED'      => false,
					'CARGO_CATEGORY'        => $this->model->cargoCategory,
					'DELIVERY_TIME_PERIOD'  => $this->model->deliveryTimePeriod,
					'RECEIVER_ADDRESS'      => $this->getReceiverInfo(),
					'EXTRA_SERVICE'         => $this->getExtraServices(),
					'PAYMENT_TYPE'          => in_array($this->model->paymentType, [static::PAYMENT_TYPE_OUP, static::PAYMENT_TYPE_OUO])
						? $this->model->paymentType
						: null
					,
					'CARGO_VALUE'           => $this->isToRussia() ? null : $this->model->cargoValue,
					'UNIT_LOAD'             => $this->isToRussia() ? $this->getUnits() : null,
					'EXTRA_PARAM'           => $this->getSourceName() ? array(
						'NAME'  => 'source_of_order',
						'VALUE' => $this->getSourceName(),
					) : null,
				),
			);

			foreach ($parms['ORDER'] as $k => $v) {
				if (is_null($v)) {
					unset($parms['ORDER'][$k]);
				}
			}

			$ret = $this->getApi()->getService('order')->createOrder($parms);
			
			if (!in_array($ret['STATUS'], array(static::STATUS_OK, static::STATUS_PENDING))) {
				$error = 'DPD: '. nl2br($ret['ERROR_MESSAGE']);
				throw new \Exception($error);
			}

			$this->model->orderNum    = isset($ret['ORDER_NUM']) ? $ret['ORDER_NUM'] : '';
			$this->model->orderStatus = $ret['STATUS'];

			if (!$this->model->save()) {
				throw new \Exception('Не удалось сохранить результат');
			}
			
			$result->setData([
				'ORDER_NUM'    => $this->model->orderNum,
				'ORDER_STATUS' => $this->model->orderStatus,
			]);

			$this->getDB()->getPDO()->commit();

		} catch (\Exception $e) {
			$this->getDB()->getPDO()->rollBack();

			$error = new Error($e->getMessage());
			$result->addError($error);
		}

		return $result;
	}

	/**
	 * Отменяет заказ в DPD
	 * 
	 * @return \Ipol\DPD\Result
	 */
	public function cancel()
	{
		$result = new Result();

		try {
			$ret = $this->getApi()->getService('order')->cancelOrder($this->model->orderId, $this->model->orderNum, $this->model->pickupDate);
			
			if (!$ret) {
				throw new \Exception('Не удалось отменить DPD заказ');
			}

			if (!in_array($ret['STATUS'], array(self::STATUS_CANCEL, self::STATUS_CANCEL_PREV))) {
				throw new \Exception($ret['ERROR_MESSAGE']);
			}

			$this->model->orderNum = '';
			$this->model->orderStatus = self::STATUS_CANCEL;
			$this->model->pickupDate = '';

			if (!$this->model->save()) {
				throw new \Exception('Не удалось сохранить результат');
			}

		} catch (\Exception $e) {
			$error = new Error($e->getMessage());
			$result->addError($error);
		}

		return $result;
	}

	/**
	 * Проверяет статус заказа
	 * 
	 * @return \Ipol\DPD\Result
	 */
	public function checkStatus()
	{
		$result = new Result();

		try {
			$ret = $this->getApi()->getService('order')->getOrderStatus($this->model->orderId, $this->model->pickupDate);

			if (!$ret) {
				throw new \Exception('Не удалось получить данные о статусе заказа');
			}

			$this->model->orderNum    = isset($ret['ORDER_NUM'])     ? $ret['ORDER_NUM'] : '';
			$this->model->orderError  = isset($ret['ERROR_MESSAGE']) ? $ret['ERROR_MESSAGE'] : '';
			$this->model->orderStatus = isset($ret['STATUS'])        ? $ret['STATUS'] : '';

			if (!$this->model->save()) {
				throw new \Exception('Не удалось сохранить результат');
			}

			$result->setData([
				'ORDER_NUM'    => $this->model->orderNum,
				'ORDER_ERROR'  => $this->model->orderError,
				'ORDER_STATUS' => $this->model->orderStatus,
			]);
		} catch(\Exception $e) {
			$error = new Error($e->getMessage());
			$result->addError();
		}

		return $result; 
	}

	/**
	 * Запрашивает файл с наклейками DPD
	 * 
	 * @return \Ipol\DPD\Result
	 */
	public function getLabelFile($count = 1, $fileFormat = 'PDF', $pageSize = 'A5')
	{
		$result = new Result();
		try {
			if (empty($this->model->orderNum)) {
				throw new \Exception('Нельзя напечатать наклейки. Заказ не создан в системе DPD!');
			}

			$ret = $this->getApi()->getService('label-print')->createLabelFile($this->model->orderNum, $count, $fileFormat, $pageSize);
			
			if (!is_array($ret) || !isset($ret['FILE'])) {
				throw new \Exception('Не удалось получить файл');
			} elseif (isset($ret['ORDER'])) {
				throw new \Exception($ret['ORDER']['ERROR_MESSAGE']);
			}

			$fileName = 'sticker.'. strtolower($fileFormat);
			$result   = $this->saveFile('labelFile', $fileName, $ret['FILE']);

		} catch (\Exception $e) {
			$result->addError(new Error($e->getMessage()));
		}

		return $result;
	}

	/**
	 * Получает файл накладной
	 * 
	 * @return \Ipol\DPD\Result;
	 */
	public function getInvoiceFile()
	{
		$result = new Result();

		try {
			if (empty($this->model->orderNum)) {
				throw new \Exception('Нельзя напечатать наклейки. Заказ не создан в системе DPD!');
			}

			$ret = $this->getApi()->getService('order')->getInvoiceFile($this->model->orderNum);
			
			if (!is_array($ret) || !isset($ret['FILE'])) {
				throw new \Exception('Не удалось получить файл');
			}

			$fileName = 'invoice.pdf';
			$result   = $this->saveFile('invoiceFile', $fileName, $ret['FILE']);

		} catch (\Exception $e) {
			$error = new Error($e->getMessage());
			$result->addError($error);
		}

		return $result;
	}
	
	/**
	 * Возвращает название источника
	 *
	 * @return string
	 */
	public function getSourceName()
	{
		return $this->sourceName ?: $this->getConfig()->get('SOURCE_NAME', null);
	}

	/**
	 * Устанавливает название источника
	 *
	 * @param string $value
	 * 
	 * @return self
	 */
	public function setSourceName($value)
	{
		$this->sourceName = $value;

		return $this;
	}

	/**
	 * Вспомогательный метод для сохранения файла
	 * 
	 * @param  $fieldToSave
	 * @param  $fileName
	 * @param  $fileContent
	 * 
	 * @return \Ipol\DPD\Result
	 */
	protected function saveFile($fieldToSave, $fileName, $fileContent)
	{
		$result = new Result();

		try {
			if (!($dirName  = $this->getSaveDir(true))) {
				throw new \Exception('Не удалось получить директорию для записи!');
			}

			$ret = file_put_contents($dirName . $fileName , $fileContent);
			
			if ($ret === false) {
				throw new \Exception('Не удалось записать файл');
				return $result;
			}

			$this->model->{$fieldToSave} = $this->getSaveDir() . $fileName;

			if (!$this->model->save()) {
				throw new \Exception('Не удалось сохранить результат');
			}

			$result->setData(['file' => $this->model->{$fieldToSave}]);
			
		} catch (\Exception $e) {
			$result->addError(new Error($e->getMessage()));
		}
		
		return $result;
	}

	/**
	 * Возвращает директорию для сохранения файлов
	 * 
	 * @param  boolean $absolute
	 * 
	 * @return string
	 */
	protected function getSaveDir($absolute = false)
	{
		if (!$this->model->id) {
			return false;
		}

		$dirName    = rtrim($this->getConfig()->get('UPLOAD_DIR'), '/') .'/'. $this->model->id .'/';
		$dirNameAbs = $dirName;

		if (!empty($_SERVER['DOCUMENT_ROOT']) 
			&& strpos($dirName, $_SERVER['DOCUMENT_ROOT']) === 0
		) {
			$dirName = substr($dirName, strlen($_SERVER['DOCUMENT_ROOT']));
		}

		$created = true;
		if (!is_dir($dirNameAbs)) {
			$created = mkdir($dirNameAbs, 0755, true);
		}

		if (!$created) {
			return false;
		}

		return $absolute ? $dirNameAbs : $dirName;
	}

	/**
	 * Возвращает описание адреса отправителя
	 * 
	 * @return array
	 */
	protected function getSenderInfo()
	{
		$location = $this->model->getShipment()->getSender();

		$ret = array(
			'NAME'          => $this->model->senderName,
			'CONTACT_FIO'   => $this->model->senderFio,
			'CONTACT_PHONE' => $this->model->senderPhone,
			'CONTACT_EMAIL' => $this->model->senderEmail,
			'NEED_PASS'     => $this->model->senderNeedPass == 'Y' ? 1 : 0,
		);

		if ($this->model->getShipment()->getSelfPickup()) {
			return array_merge($ret, array(
				'TERMINAL_CODE' => $this->model->senderTerminalCode,
			));
		}

		return array_merge($ret, array_filter(array(
			'COUNTRY_NAME'  => $location['COUNTRY_NAME'],
			'REGION'        => $location['REGION_NAME'],
			'CITY'          => $location['CITY_NAME'],
			'STREET'        => $this->model->senderStreet,
			'STREET_ABBR'   => $this->model->senderStreetabbr,
			'HOUSE'         => $this->model->senderHouse,
			'HOUSE_KORPUS'  => $this->model->senderKorpus,
			'STR'           => $this->model->senderStr,
			'VLAD'          => $this->model->senderVlad,
			'OFFICE'        => $this->model->senderOffice,
			'FLAT'          => $this->model->senderFlat,
		)));
	}

	/**
	 * Возвращает описание адреса получателя
	 * 
	 * @return array
	 */
	protected function getReceiverInfo()
	{
		$location = $this->model->getShipment()->getReceiver();

		$ret = array(
			'NAME'          => $this->model->receiverName,
			'CONTACT_FIO'   => $this->model->receiverFio,
			'CONTACT_PHONE' => $this->model->receiverPhone,
			'CONTACT_EMAIL' => $this->model->receiverEmail,
			'NEED_PASS'     => $this->model->receiverNeedPass == 'Y' ? 1 : 0,
			'INSTRUCTIONS'  => $this->model->receiverComment,
		);

		if ($this->model->getShipment()->getSelfDelivery()) {
			return array_merge($ret, array(
				'TERMINAL_CODE' => $this->model->receiverTerminalCode,
				'INSTRUCTIONS'  => $this->model->receiverComment,
			));
		}

		return array_merge($ret, array_filter(array(
			'COUNTRY_NAME'  => $location['COUNTRY_NAME'],
			'REGION'        => $location['REGION_NAME'],
			'CITY'          => $location['CITY_NAME'],
			'STREET'        => $this->model->receiverStreet,
			'STREET_ABBR'   => $this->model->receiverStreetabbr,
			'HOUSE'         => $this->model->receiverHouse,
			'HOUSE_KORPUS'  => $this->model->receiverKorpus,
			'STR'           => $this->model->receiverStr,
			'VLAD'          => $this->model->receiverVlad,
			'OFFICE'        => $this->model->receiverOffice,
			'FLAT'          => $this->model->receiverFlat,
			'INSTRUCTIONS'  => $this->model->receiverComment,
		)));
	}

	/**
	 * Возвращает список доп услуг
	 * 
	 * @return array
	 */
	protected function getExtraServices()
	{
		$ret = array();

		if (!empty($this->model->sms)) {
			$ret['SMS'] = array('esCode' => 'SMS', 'param' => array('name' => 'phone', 'value' => $this->model->sms));
		}

		if (!empty($this->model->eml)) {
			$ret['EML'] = array('esCode' => 'EML', 'param' => array('name' => 'email', 'value' => $this->model->eml));
		}

		if (!empty($this->model->esd)) {
			$ret['ESD'] = array('esCode' => 'ЭСД', 'param' => array('name' => 'email', 'value' => $this->model->esd));
		}

		if (!empty($this->model->esz)) {
			$ret['ESZ'] = array('esCode' => 'ЭСЗ', 'param' => array('name' => 'email', 'value' => $this->model->esz));
		}

		if ($this->model->pod != '') {
			$ret['POD'] = array('esCode' => 'ПОД', 'param' => array('name' => 'email', 'value' => $this->model->pod));
		}

		if ($this->model->dvd == 'Y') {
			$ret['DVD'] = array('esCode' => 'ДВД', 'param' => array());
		}

		if ($this->model->trm == 'Y') {
			$ret['TRM'] = array('esCode' => 'ТРМ', 'param' => array());
		}

		if ($this->model->prd == 'Y') {
			$ret['PRD'] = array('esCode' => 'ПРД', 'param' => array());
		}

		if ($this->model->vdo == 'Y') {
			$ret['VDO'] = array('esCode' => 'ВДО', 'param' => array());
		}

		if ($this->model->ogd != '') {
			$ret['OGD'] = array('esCode' => 'ОЖД', 'param' => array('name' => 'reason_delay', 'value' => $this->model->ogd));
		}

		if ($this->model->npp == 'Y' && !$this->isToRussia()) {
			$ret['NPP'] = array('esCode' => 'НПП', 'param' => array('name' => 'sum_npp', 'value' => $this->model->sumNpp));
		}

		return array_values($ret);
	}

	/**
	 * Возвращает список вложений для ФЗ 54
	 * 
	 * @return array
	 */
	protected function getUnits()
	{
		$currencyFrom = $this->model->currency;
		$currencyTo   = $this->getApi()->getClientCurrency();
		$currencyDate = $this->model->orderDate ?: date('Y-m-d H:i:s');
		$converter    = $this->getCurrencyConverter();

		return array_map(function($item) use ($currencyFrom, $currencyTo, $currencyDate, $converter) {
			$item['VAT'] = $item['VAT'] == 'Без НДС' ? '' : $item['VAT'];

			if ($converter) {
				$item['CARGO'] = $converter->convert($item['CARGO'], $currencyFrom, $currencyTo, $currencyDate);
				$item['NPP']   = $converter->convert($item['NPP'], $currencyFrom, $currencyTo, $currencyDate);
			} elseif ($currencyFrom != $currencyTo) {
				throw new \Exception('Currency converter is not defined');
			}

			return array_merge(
				[
					'descript'       => $item['NAME'],
					'declared_value' => $item['CARGO'],
					'npp_amount'     => $item['NPP'],
					'count'          => $item['QUANTITY'],
				],

				empty($item['VAT']) ? [] : ['vat_percent' => $item['VAT']],
				empty($item['VAT']) ? ['without_vat' => 1] : [],

				[]
			);
		}, $this->model->unitLoads);
	}

	/**
	 * Проверяет идет ли доставка в Россию
	 * 
	 * @return boolean
	 */
	protected function isToRussia()
	{
		$location = $this->model->getShipment()->getReceiver();

		return (isset($location['COUNTRY_CODE']) && mb_strtoupper($location['COUNTRY_CODE']) == 'RU')
			|| mb_strtolower($location['COUNTRY_NAME']) == 'россия'
		;
	}
}