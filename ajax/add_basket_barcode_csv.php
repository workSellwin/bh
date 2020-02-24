<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?

if (check_bitrix_sessid() && $_FILES["file"]) {


    $obAddBasketProductBarCsv = new addBasketProductBarCsv($_FILES['file']);
    $obAddBasketProductBarCsv->Start();
    // \Bitrix\Main\Diag\Debug::writeToFile($_FILES['file']['type']);
    /*
        if ($_FILES['file']['type'] == 'application/vnd.ms-excel') {
            if (($handle = fopen($_FILES['file']['tmp_name'], "r")) !== FALSE) {
                $i = 1;
                while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
                    if ($i > 0 && $data[0]) {
                        if ($arId = searchByBarCodeArray($data[0])) {
                            if (count($arId) == 1) {
                                $PRODUCT_ID = $arId[0];
                                unset($count);
                                $count = returnQuantityProduct($PRODUCT_ID);
                                if ($count < $data[1] || $count == 0) {
                                    if (!Add2BasketByProductID($PRODUCT_ID, $count)) {
                                        $ERROR .= 'Товар <strong>' . $data[0] . '</strong> не доступен к покупке!<br>';
                                    } else {
                                        $ERROR .= 'Товар <strong>' . $data[0] . '</strong> на складе в количестве <strong>' . $count . '</strong>!<br>';
                                    }
                                } else {
                                    if (!Add2BasketByProductID($PRODUCT_ID, $data[1])) {
                                        $ERROR .= 'Товар <strong>' . $data[0] . '</strong> не доступен к покупке!<br>';
                                    }
                                }
                            } else {
                                PR($arId);
                            }
                        } else {
                            $ERROR .= 'Товар  <strong>' . $data[0] . '</strong> не найдена<br>';
                        }
                    }
                    $i++;
                }
            }
            if ($ERROR) {
                echo '<div class="err_mess">' . $ERROR . '</div>';
                echo '<div class="complite"><a href="javascript:window.location.reload(true)">Перезагрузить страницу чтобы увидеть товары в корзине</a></div><br>';
            } else {
                echo '<div class="complite"><script>location.reload();</script></div>';
            }
        } else {
            echo '<div class="err_mess">Загружен некорректный файл!</div>';
        }
        */
} else {
    echo '<div class="err_mess">Что то пошло не так!</div>';
}


class addBasketProductBarCsv
{
    protected $arDataCsv = [];
    protected $arErrorStr = [];

    /**
     * addBasketProductBarCsv constructor.
     * @param $file
     */
    public function __construct($file)
    {
        if ($file['type'] == 'application/vnd.ms-excel') {
            if (($handle = fopen($file['tmp_name'], "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
                    $this->arDataCsv[] = [
                        'CODE' => $data[0],
                        'COUNT' => $data[1],
                    ];
                }
            }
        } else {
            $this->addError('FILE_ERROR');
        }
    }

    /**
     *
     */
    public function Start()
    {
        if (!$this->arErrorStr) {
            $arCatalog = self::GetCatalog(array_column($this->arDataCsv, 'CODE'));
            $_SESSION['PROGRESS_CSV_FILE']['value'] = 0;
            $_SESSION['PROGRESS_CSV_FILE']['max'] = count($this->arDataCsv);
            $i = 0;
            foreach ($this->arDataCsv as $dt) {
                $code = $dt['CODE'];
                if ($arProduct = $arCatalog[$code]) {
                   $this->AddProductToBasket([
                        'PRODUCT' => $arProduct,
                        'CSV' => $dt,
                    ]);
                } else {
                    $this->addError('NO_PRODUCT', $code);
                }

                $_SESSION['PROGRESS_CSV_FILE']['value'] = $i;
                $i++;
            }
            echo $this->ShowError();
            echo $this->ShowFinish();
        }
    }

    /**
     * @param $arData
     */
    protected function AddProductToBasket($arData)
    {
        $product = $arData['PRODUCT'];
        $countAdd = $arData['CSV']['COUNT'];
        $code = $arData['CSV']['CODE'];

        foreach ($product as $id => $prod) {
            $count = $prod['COUNT'];
            if ($countAdd > $count) {
                if (!self::AddBasket($id, $count)) {
                    $this->addError('NO_ADD_BASKET', $code);
                } else {
                    $this->addError('NO_ADD_BASKET_COUNT', $code, $count);
                    $countAdd = $countAdd - $count;
                }
            } else {
                if (!self::AddBasket($id, $countAdd)) {
                    $this->addError('NO_ADD_BASKET', $code);
                }
            }
            if (!$countAdd) {
                break;
            }
        }
    }


    /**
     * @param $id
     * @param $count
     * @return bool|int
     */
    protected static function AddBasket($id, $count)
    {
        return Add2BasketByProductID($id, $count);
    }

    /**
     * @param $arBar
     * @return array
     */
    protected static function GetCatalog($arBar)
    {
        $arData = [];
        $arID = [];
        CModule::IncludeModule("iblock");
        CModule::IncludeModule("catalog");
        $res = CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => [2, 3], 'PROPERTY_strish_kod' => $arBar],
            false,
            [],
            ["NAME", "ID", "PROPERTY_strish_kod"]
        );
        while ($ob = $res->GetNext()) {
            $arID[] = $ob['ID'];
            $arData[$ob['PROPERTY_STRISH_KOD_VALUE']][$ob['ID']] = ['NAME' => $ob['NAME']];
        }
        if ($arID) {
            $arCatalog = [];
            $res = CCatalogProduct::GetList(array(), array("ID" => $arID), false, array(), array());
            while ($ob = $res->GetNext()) {
                $arCatalog[$ob['ID']] = $ob['QUANTITY'];
            }
            foreach ($arData as &$tr) {
                foreach ($tr as $id => &$dt) {
                    $t = $arCatalog[$id];
                    $dt['COUNT'] = $t ? $t : '0';
                }
            }
        }
        return $arData;
    }

    /**
     * @param $type
     * @param string $mes
     * @param string $count
     */
    protected function addError($type, $mes = '', $count = '')
    {
        $error = '';
        switch ($type) {
            case  'NO_FILE':
                $error = '<div class="err_mess">Что то пошло не так!</div>';
                break;
            case  'FILE_ERROR':
                $error = '<div class="err_mess">Загружен некорректный файл!</div>';
                break;
            case  'NO_ADD_BASKET':
                $error = 'Товар <strong>' . $mes . '</strong> не доступен к покупке!';
                break;
            case  'NO_ADD_BASKET_COUNT':
                $error = 'Товар <strong>' . $mes . '</strong> на складе в количестве <strong>' . $count . '</strong>!';
                break;
            case  'NO_PRODUCT':
                $error = 'Товар  <strong>' . $mes . '</strong> не найден';
                break;
        }
        if ($error) {
            $this->arErrorStr[] = $error;
        }
    }

    /**
     * @return string
     */
    protected function ShowFinish()
    {
        if ($this->arErrorStr) {
            $str = '<div class="complite"><a href="javascript:window.location.reload(true)">Перезагрузить страницу чтобы увидеть товары в корзине</a></div><br>';
        } else {
            $str = '<div class="complite"><script>location.reload();</script></div>';
        }
        return $str;
    }

    /**
     * @return string
     */
    protected function ShowError()
    {
        $error = '';
        if (!empty($this->arErrorStr)) {
            $error = implode('<br>', $this->arErrorStr);
            $error = '<div class="err_mess">' . $error . '</div>';
        }
        return $error;
    }

}
