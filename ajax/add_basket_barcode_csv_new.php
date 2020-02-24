<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$arResult = [
    'error' => '',
    'step' => '',
    'message' => '',
    'filename' => '',
    'status' => '',
    'start' => '',
    'all' => '',
];
if (check_bitrix_sessid()) {
    $uploaddir = '/home/bitrix/www/ajax/csv/';
    $uploaddirJson = '/home/bitrix/www/ajax/json/';
    $pathWeb = '/ajax/csv/';
    $arResult['step'] = $_REQUEST['step'];
    switch ($_REQUEST['step']) {
        case '1':
            if ($file = $_FILES["file"]) {
                $fileName = md5(time() . uniqid()) . '.csv';
                $uploadfile = $uploaddir . $fileName;
                $arResult['file'] = $file;
                if ($file['type'] == 'application/vnd.ms-excel') {
                    if (copy($file['tmp_name'], $uploadfile)) {
                        $arResult['message'] = "Файл корректен и был успешно загружен.";
                        $arResult['filename'] = $fileName;
                    } else {
                        $arResult['error'] = "Ошибка! Не удалось загрузить файл на сервер!";
                    }
                } else {
                    $arResult['error'] = 'Неверный тип файла';
                }
            } else {
                $arResult['error'] = 'Отсутствует файл';
            }
            break;
        case '2';
            if ($fileName = $_REQUEST['fileName']) {
                if (file_exists($uploaddir . $fileName)) {
                    $arDataCsv = [];
                    if (($handle = fopen($uploaddir . $fileName, "r")) !== FALSE) {
                        while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
                            $arDataCsv[$data[0]] = $data[1];
                        }
                        unlink($uploaddir . $fileName);
                        // Получим данные от каталога!
                        $arCodeId = [];
                        CModule::IncludeModule("iblock");
                        CModule::IncludeModule("catalog");
                        $res = CIBlockElement::GetList(
                            [],
                            ["IBLOCK_ID" => [2, 3], 'PROPERTY_strish_kod' => array_keys($arDataCsv)],
                            false,
                            [],
                            ["NAME", "ID", "PROPERTY_strish_kod"]
                        );
                        $i = 0;
                        $arID = [];
                        while ($ob = $res->GetNext()) {
                            $arID[] = $ob['ID'];
                            $arCodeId[$ob['PROPERTY_STRISH_KOD_VALUE']][$ob['ID']] = $ob['ID'];
                        }
                        $arDataAdd = [];
                        $arError = [];
                        foreach ($arDataCsv as $bar => $count) {
                            if ($elId = $arCodeId[$bar]) {
                                foreach ($elId as $id) {
                                    $arDataAdd[$bar][$id] = $count;
                                }
                            } else {
                                $arError[] = 'Нет штрихкода в каталоге: ' . $bar;
                            }
                        }

                        // проверка на остатки!
                        $res = \Bitrix\Catalog\ProductTable::getList(
                            [
                                'filter' => ['ID' => $arID],
                                'select' => ['ID', 'QUANTITY']
                            ]
                        );
                        $arCatalog = [];
                        while ($cat = $res->fetch()) {
                            $arCatalog[$cat['ID']] = $cat['QUANTITY'];
                        }
                        $arAddVariant = [];
                        //что в корзину добовлять!
                        foreach ($arDataAdd as $bar => $rId) {
                            foreach ($rId as $id => $count) {
                                $arAddVariant[$bar]['COUNT'] = $count;
                                $arAddVariant[$bar]['ITEMS'][] = $id;
                                $arAddVariant[$bar]['ITEMS_COUNT'][$id] = $arCatalog[$id];
                            }
                        }
                        unset($arDataAdd, $arCatalog, $arDataCsv);
                        // отсечение нулей!
                        foreach ($arAddVariant as $bar => &$add) {
                            $v = max(array_values($add['ITEMS_COUNT']));
                            $key = array_search($v, $add['ITEMS_COUNT']);
                            $add = [
                                'COUNT' => $add['COUNT'],
                                'ID' => $key,
                                'QUANTITY' => $add['ITEMS_COUNT'][$key],
                            ];
                        }

                        foreach ($arAddVariant as $bar => $el) {
                            if ($el['QUANTITY'] == 0) {
                                $arError[] = 'Товара с кодом ' . $bar . ' нет на складе';
                                unset($arAddVariant[$bar]);
                            }
                        }

                        if ($arError) {
                            $arResult['status'] = implode('<br>', $arError);
                        }

                        $fileName = md5(time() . uniqid()) . '.json';
                        $uploadfile = $uploaddirJson . $fileName;
                        file_put_contents($uploadfile, json_encode($arAddVariant));
                        $arResult['filename'] = $fileName;
                        $arResult['all'] = count($arAddVariant);
                        $arResult['start'] = 0;

                    }
                } else {
                    $arResult['error'] = 'Отсутствует файл';
                }
            } else {
                $arResult['error'] = 'Отсутствует файл в запросе';
            }
            break;
        case '3':
            if ($fileName = $_REQUEST['fileName']) {
                if (file_exists($uploaddirJson . $fileName)) {
                    $start = $_REQUEST['start'];
                    $countAdd = 3;
                    $arResult['fileName'] = $_REQUEST['fileName'];
                    $arResult['start'] = $start;
                    $arResult['all'] = $_REQUEST['all'];
                    $arResult['start'] += $countAdd;
                    if ($arResult['start'] >= $arResult['all']) {
                        $arResult['start'] = $arResult['all'];
                        $arResult['message'] = '<div class="complite"><a href="javascript:window.location.reload(true)">Перезагрузить страницу чтобы увидеть товары в корзине</a></div><br>';
                    }
                    $finish = $arResult['start'];
                    $strJson = file_get_contents($uploaddirJson . $fileName);
                    $arJson = json_decode($strJson, true);
                    $i = 0;
                    $arStatus = [];
                    CModule::IncludeModule("sale");
                    CModule::IncludeModule("catalog");
                    foreach ($arJson as $bar => $j) {
                        if ($i >= $start and $i < $finish) {
                            if (Add2BasketByProductID($j['ID'], $j['COUNT'])) {
                                $arStatus[] = "Товар с кодом {$bar} добавлен";
                            } else {
                                $arStatus[] = "Товар с кодом {$bar} не добавлен";
                            }
                        }
                        $i++;
                    }
                    if ($arStatus) {
                        $arResult['status'] = implode('<br>', $arStatus);
                        $arResult['status'] = $arResult['status'] . '<hr>';
                    }
                } else {
                    $arResult['error'] = 'Отсутствует файл';
                }
            } else {
                $arResult['error'] = 'Отсутствует файл в запросе';
            }
            break;
    }

} else {
    $arResult['error'] = 'Устарела сессия';
}


function addBasket($arData)
{
    $arResult = [
        'error' => [],
        'status' => [],
    ];
    foreach ($arData as $dt) {

    }
    return $arResult;
}

echo json_encode($arResult);
