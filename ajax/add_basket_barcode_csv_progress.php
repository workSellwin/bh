<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?
echo json_encode($_SESSION['PROGRESS_CSV_FILE'] ? $_SESSION['PROGRESS_CSV_FILE'] : ['value' => 100, 'max' => 100]);
