<?php

/**
 * Created by PhpStorm.
 * User: maspanau
 * Date: 04.04.2019
 * Time: 10:13
 */
class exportFacebook
{
    use InitMainTrait;
    public $PATH;
    public $IBLOCK_ID = 2;
    public $SELECT = [
        'ID',
        'IBLOCK_ID',
        'NAME',
        'PROPERTY_ONLINER',
        'CATALOG_QUANTITY',
        'DETAIL_PICTURE',
        'DETAIL_PAGE_URL',
        'DETAIL_TEXT',
        'CATALOG_AVAILABLE',
    ];
    public $POLE_NAME = array(
        'id',
        'title',
        //'ios_url',
        //'ios_app_store_id',
        //'ios_app_name',
        //'android_url',
        //'android_package',
        //'android_app_name',
        //'windows_phone_url',
        //'windows_phone_app_id',
        //'windows_phone_app_name',
        'description',
        //'google_product_category',
        //'product_type',
        'link',
        'image_link',
        'condition',
        'availability',
        'price',
        //'sale_price_effective_date',
        //'gtin',
        'brand',
        //'mpn',
        //'item_group_id',
        //'gender',
        //'age_group',
        //'color',
        //'size',
        //'shipping',
        //'custom_label_0',
    );

    public function __construct()
    {
        $this->includeModules();
        $this->PATH = $_SERVER['DOCUMENT_ROOT'] . "/upload/EXPORT_FACEBOOK/export_facebook.csv";
    }

    public function process()
    {
        //получаем массив елементов из нашего котолога
        $dataElemCatalog = $this->getListElemCatalog();
        $dataElemCatalog = array_merge([$this->POLE_NAME], $dataElemCatalog);
        //преобразуем массив в CSV строки и записываем в файл
        $this->arrayToScv($dataElemCatalog, $this->PATH);
    }

    /**
     * @return array
     */
    private function getListElemCatalog()
    {
        $filter = [
            'IBLOCK_ID' => $this->IBLOCK_ID,
            'CATALOG_AVAILABLE' => 'Y',
            "SECTION_ID" => array(411, 114, 829, 671),
            'INCLUDE_SUBSECTIONS' => 'Y',
            'SITE_ID' => 's1',
        ];
        $arElements = [];
        $i = 0;
        $resID = \CIBlockElement::GetList(array('ID' => 'ASC'), $filter, false, false, $this->SELECT);
        while ($res1 = $resID->GetNextElement()) {
            $reOnl = $res1->GetFields();
            $reProp = $res1->GetProperties();

            $resPrice = \CCatalogProduct::GetOptimalPrice($reOnl["ID"], 1, [2], 'N', 's1');
            $arPrice["PRICE"] = round($resPrice['DISCOUNT_PRICE'], 2);
            $arPrice["PRICE"] = str_replace('.', ',', $arPrice["PRICE"]);
            $PRICE = (float)(str_replace(',', '.', $arPrice["PRICE"]));
            $description = preg_replace('/\s?<[^>]*>\s?/si', ' ', $reOnl['DETAIL_TEXT']);
            //----------------------------------------------------------------------------------------------------------
            $arElements[$reOnl['ID']]['id'] = $reOnl['ID'];
            $arElements[$reOnl['ID']]['title'] = $reOnl['NAME'];
            //$arElements[$reOnl['id']]['ios_url']=$reOnl['id'];
            //$arElements[$reOnl['id']]['ios_app_store_id']=$reOnl['id'];
            //$arElements[$reOnl['id']]['ios_app_name']=$reOnl['id'];
            //$arElements[$reOnl['id']]['android_url']=$reOnl['id'];
            //$arElements[$reOnl['id']]['android_package']=$reOnl['id'];
            //$arElements[$reOnl['id']]['android_app_name']=$reOnl['id'];
            //$arElements[$reOnl['id']]['windows_phone_url']=$reOnl['id'];
            //$arElements[$reOnl['id']]['windows_phone_app_id']=$reOnl['id'];
            //$arElements[$reOnl['id']]['windows_phone_app_name']=$reOnl['id'];
            $arElements[$reOnl['ID']]['description'] = $description;
            //$arElements[$reOnl['id']]['google_product_category']= 'dfgdfgdfg';
            //$arElements[$reOnl['id']]['product_type']=$reOnl['id'];
            $arElements[$reOnl['ID']]['link'] =  'https://'.$_SERVER['SERVER_NAME'] .$reOnl['DETAIL_PAGE_URL'];
            $arElements[$reOnl['ID']]['image_link']= CFile::GetPath($reOnl['DETAIL_PICTURE']);
            $arElements[$reOnl['ID']]['condition'] = 'new';
            $arElements[$reOnl['ID']]['availability'] = $reOnl['CATALOG_AVAILABLE'] == 'Y' ? 'in stock' : 'out of stock';
            $arElements[$reOnl['ID']]['price'] = $PRICE . ' BYN';
            //$arElements[$reOnl['id']]['sale_price_effective_date']=$reOnl['id'];
            //$arElements[$reOnl['id']]['gtin']=$reOnl['id'];
            $arElements[$reOnl['ID']]['brand']=$reProp['BRANDS']['VALUE'];
            //$arElements[$reOnl['id']]['mpn']=$reOnl['id'];
            //$arElements[$reOnl['id']]['item_group_id']=$reOnl['id'];
            //$arElements[$reOnl['id']]['gender']=$reOnl['id'];
            //$arElements[$reOnl['id']]['age_group']=$reOnl['id'];
            //$arElements[$reOnl['id']]['color']=$reOnl['id'];
            //$arElements[$reOnl['id']]['size']=$reOnl['id'];
            //$arElements[$reOnl['id']]['shipping']=$reOnl['id'];
            //$arElements[$reOnl['id']]['custom_label_0']=$reOnl['id'];
            //-----------------------------------------------------------------------------------------------------------
        }
        return $arElements;
    }

    /**
     * @param $arData
     * @param $file_path
     * @param string $delimiter
     */
    private function arrayToScv($arData, $file_path, $delimiter = ';')
    {
        $fp = fopen($file_path, 'w');
        foreach ($arData as $fields) {
            fputcsv($fp, $fields, $delimiter);
        }
    }
}
