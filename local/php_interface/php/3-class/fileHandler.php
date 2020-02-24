<?php

use Asdrubael\S3\CloudStorageClient;
use Bitrix\Main\Loader;

class FileHandler
{

    const BUCKET = 'bhby';
    const PROTOCOL = "https://";
    const CLOUD_HOST = '.io.activecloud.com/';
    private $propertyId = [13, 19];

    static function OnGetFileSRCHandler($ar)
    {

        if (strpos($ar['MODULE_ID'], 'cloud') === false) {
            return '';
        }

        $src = self::PROTOCOL . self::BUCKET . self::CLOUD_HOST . $ar['SUBDIR'] . '/' . $ar['FILE_NAME'];

        return $src;
    }

    static function OnFileSaveHandler(&$arFile)
    {

        if (empty($arFile['product_xml_id'])) {
            return false;
        }

//    if( !CFile::IsImage($arFile['tmp_name']) ){
//        return false;
//    }

        //\Bitrix\Main\Loader::includeModule('kocmo.exchange');//composer

        $f = fopen($arFile['tmp_name'], "r");

        if (!$f) {
            return false;
        }
        $contents = fread($f, filesize($arFile['tmp_name']));
        fclose($f);

        if ($contents === false) {
            return false;
        }

        $imgArray = getimagesize($arFile['tmp_name']);

        if (!$imgArray[0] || !$imgArray[1]) {
            return false;
        }

        $arFile["SUBDIR"] = $arFile['product_xml_id'] . '/' . $imgArray[0] . 'x' . $imgArray[1];
        $arFile["FILE_NAME"] = $arFile['name'];
        $arFile['MODULE_ID'] = 'my_cloud';
        $arFile['HANDLER_ID'] = 'my_cloud';

        $storageClient = new Asdrubael\S3\CloudStorageClient();
        $result = $storageClient->putObject(
            self::BUCKET,
            $arFile["SUBDIR"] . '/' . $arFile["FILE_NAME"],
            $contents
        );

        if (is_array($imgArray)) {
            $arFile["WIDTH"] = $imgArray[0];
            $arFile["HEIGHT"] = $imgArray[1];
        } else {
            $arFile["WIDTH"] = 0;
            $arFile["HEIGHT"] = 0;
        }

        return true;
    }

    static function OnAfterResizeImageHandler($file, $data, $callbackData, &$cacheImageFile, &$cacheImageFileTmp, &$arImageSize)
    {

        if (strpos($file['MODULE_ID'], 'cloud') !== false) {

            global $USER;
//            if ($USER->GetId() == 4043 || true) {
//                $cacheImageFile = $file['SRC'];
//                return false;
//            }

            preg_match("#([\s\S]+)\/(\d+x\d+)#", $file["SUBDIR"], $parseData);
            $resizeResolution = $data[0]['width'] . 'x' . $data[0]['height'];

            if (!count($parseData)) {
                return false;
            }

            if ( $USER->GetId() == 4043 || true ) {//

                $storageClient = new Asdrubael\S3\CloudStorageClient();

                $path = $storageClient->getFilePath(
                    self::BUCKET,
                    ['id' => $parseData[1], 'resolution' => $resizeResolution, 'strict' => false, 'file_name' => $file['FILE_NAME']]
                );

               // pr($path, 1);
            } else {

                $path = 'https://bhby.io.activecloud.com/' . $parseData[1] . '/' . $resizeResolution . '/' . $file['FILE_NAME'];

                if(!file_get_contents($path)){
//                    global $USER;
//                    if($USER->GetId() == 4043){
//                        pr($path, 1);
//                    }
                    $cacheImageFile = $file['SRC'];
                    return false;
                }
            }



            if (is_string($path) && strlen($path) > 3) {

                preg_match("#(\d+)x(\d+)#", $path, $resolutionMatch);

                if( !empty($resolutionMatch[1]) && !empty($resolutionMatch[2]) ) {

                    $arImageSize = [
                        $resolutionMatch[1], $resolutionMatch[2]
                    ];

                    global $arCloudImageSizeCache;
                    $arCloudImageSizeCache[$path] = $arImageSize;
                    $cacheImageFile = $path;
                    return true;
                }
            }
            else{
                $cacheImageFile = $file['SRC'];
                try {
                    Asdrubael\S3\ImageTable::add(["WIDTH" => $data[0]['width'], "HEIGHT" => $data[0]['height'], 'IMG_ID' => $file['ID']]);
                }
                catch (Bitrix\Main\DB\SqlQueryException $e) {

                }
            }
//            if ( $USER->GetId() == 4043 ) {
//                pr($file['SRC'], 1);
//            }

            /*$fileId = CFile::SaveFile(CFile::MakeFileArray($file['SRC']), 'tmp');

            if (intval($fileId) > 0) {

                $rf = CFile::ResizeImageGet(
                    $fileId,
                    $data[0],
                    $data[1]
                );

                if (!is_array($rf) || empty($rf['src'])) {
                    return false;
                }

                $rf['src'] = $_SERVER['DOCUMENT_ROOT'] . $rf['src'];

                //$imgArray = getimagesize($rf['src']);
                $size = filesize($rf['src']);

//                if (!$imgArray[0] || !$imgArray[1]) {
//                    return false;
//                }

                $f = fopen($rf['src'], "r");

                if (!$f) {
                    return false;
                }

                $contents = fread($f, $size);
                fclose($f);
                CFile::Delete($fileId);

                if ($contents === false) {
                    return false;
                }

                $resizeSubDir = $parseData[1] . '/' . $data[0]['width'] . 'x' . $data[0]['height'];

                if(!$storageClient){
                    $storageClient = new Asdrubael\S3\CloudStorageClient();
                }

                $result = $storageClient->putObject(
                    self::BUCKET,
                    $resizeSubDir . '/' . $file["FILE_NAME"],
                    $contents
                );

                $metadata = $result->get('@metadata');

                if ($metadata['statusCode'] == 200) {

                    $arImageSize = [
                        $data[0]['width'], $data[0]['height'], intval($size)
                    ];

                    $cacheImageFile = $metadata['effectiveUri'];

                    global $arCloudImageSizeCache;
                    $arCloudImageSizeCache[$cacheImageFile] = $arImageSize;
                    return true;
                }
                return false;
            }*/
        }
    }

    static function OnFileDeleteHandler(&$arFile)
    {

        preg_match("#([\d\w\-]+)\/(\d+x\d+)#", $arFile['SUBDIR'], $matches);

        if (count($matches)) {
            //Loader::includeModule('kocmo.exchange');//composer
            $storageClient = new Asdrubael\S3\CloudStorageClient();
            $storageClient->deleteObjects(self::BUCKET, $matches[1], [$arFile['FILE_NAME']]);
        }
    }

    function getBindFiles($filter, $select = [])
    {

        try {
            Loader::includeModule('iblock');
//        $filter = ["IBLOCK_ID" => 2, "ID" => 38301];
//        $select = ["ID", "DETAIL_PICTURE", "PREVIEW_PICTURE", "PROPERTY_13"];
            $res = \CIBlockElement::GetList([], $filter, false, false, $select);
            $FILES = [];

            while ($fields = $res->fetch()) {

                if (intval($fields['PREVIEW_PICTURE']) > 0 && empty($FILES[$fields["EXTERNAL_ID"]]['PREVIEW_PICTURE'])) {
                    $FILES[$fields["EXTERNAL_ID"]]['PREVIEW_PICTURE'] = intval($fields['PREVIEW_PICTURE']);
                }
                if (intval($fields['DETAIL_PICTURE']) > 0 && empty($FILES[$fields["EXTERNAL_ID"]]['DETAIL_PICTURE'])) {
                    $FILES[$fields["EXTERNAL_ID"]]['DETAIL_PICTURE'] = intval($fields['DETAIL_PICTURE']);
                }

                foreach ($fields as $key => $field) {
                    preg_match("#^PROPERTY_(\d+)_VALUE$#", $key, $matches);

                    if (count($matches)) {
                        if (!in_array(intval($field), $FILES[$fields["EXTERNAL_ID"]][$matches[1]])) {
                            $FILES[$fields["EXTERNAL_ID"]][$matches[1]][] = intval($field);
                        }
                    }
                    unset($matches);
                }
            }

            //echo '<pre>' . print_r($FILES, true) . '</pre>';
        } catch (\Bitrix\Main\LoaderException $e) {
            return [];
        }
        //pr($FILES, 14);
        return $FILES;
    }

    function moveToCloud(array $filter)
    {

        //$filter = ["IBLOCK_ID" => 2, "ID" => 38301];
        $filter["!EXTERNAL_ID"] = false;
        $bindFiles = $this->getBindFiles($filter, ["ID", "EXTERNAL_ID", "DETAIL_PICTURE", "PREVIEW_PICTURE", "PROPERTY_13", "PROPERTY_19"]);

        if (!is_array($bindFiles) && !count($bindFiles)) {
            return false;
        }

        $allFiles = [];
        $binds = [];
        $fileNames = [];

        if (count($bindFiles)) {

            foreach ($bindFiles as $productId => $fileIds) {

                foreach ($fileIds as $name => $fileId) {

                    if (empty($fileId)) {
                        continue;
                    }

                    if (is_array($fileId)) {

                        if (count($fileId)) {

                            if (count($fileId) == 1 && empty($fileId[0])) {
                                continue;
                            }

                            foreach ($fileId as $index => $fi) {

                                $allFiles[] = $fi;
                                $binds[$fi] = $productId;
                                $fileNames[$fi] = "PROPERTY_" . $name . '_' . $index;
                            }
                        }
                    } else {
                        $allFiles[] = $fileId;
                        $binds[$fileId] = $productId;
                        $fileNames[$fileId] = $name;
                    }
                }
            }
        }

        $fAr = Asdrubael\Bx\FileTable::getList(["filter" => ["!MODULE_ID" => "%cloud", "ID" => $allFiles]])->fetchAll();

        if (count($fAr)) {

            foreach ($fAr as $fileAr) {

                $path = $_SERVER['DOCUMENT_ROOT'] . CFile::GetPath($fileAr['ID']);
                $f = fopen($path, "r");

                if (!$f) {
                    continue;
                }
                $contents = fread($f, filesize($path));
                fclose($f);

                if ($contents === false) {
                    continue;
                }
                if (in_array($fileNames[$fileAr["ID"]], ["DETAIL_PICTURE", "PREVIEW_PICTURE"])) {
                    $fileAr["FILE_NAME"] = $fileNames[$fileAr["ID"]] . $this->getExtansion($fileAr["FILE_NAME"]);
                } else {
                    $fileAr["FILE_NAME"] = $fileNames[$fileAr["ID"]] . $this->getExtansion($fileAr["FILE_NAME"]);
                }

                $fileAr["SUBDIR"] = $binds[$fileAr["ID"]] . '/' . $fileAr['WIDTH'] . 'x' . $fileAr['HEIGHT'];
                $fileAr['MODULE_ID'] = 'my_cloud';
                $fileAr['HANDLER_ID'] = 'my_cloud';

                $storageClient = new Asdrubael\S3\CloudStorageClient();

                $result = $storageClient->putObject(
                    self::BUCKET,
                    $fileAr["SUBDIR"] . '/' . $fileAr["FILE_NAME"],
                    $contents
                );

                Asdrubael\Bx\FileTable::update($fileAr["ID"], $fileAr);
            }
        }

    }

    private function getFileName($key)
    {

        if (!is_string($key)) {
            return false;
        }

        preg_match("#\/([^\/]+)$#", $key, $matches);

        if (isset($matches[1])) {
            return $matches[1];
        }
        return false;
    }

    private function getExtansion($name)
    {

        $arr = explode('.', $name);
        $extension = $arr[count($arr) - 1];

        if ($extension) {
            return '.' . $extension;
        } else {
            return "";
        }
    }

//    static function OnAfterResizeImageHandler2 (/*&$file, $data, $callbackData, &$cacheImageFile, &$cacheImageFileTmp, &$arImageSize*/)
//    {
//
//        global $USER;
//        if ($USER->GetId() == 4043) {
//            pr('echo2', 1, 1);
//        }
//    }
}
