<? require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?
if (check_bitrix_sessid() && $_REQUEST['BAR_CODE']) {
    if ($PRODUCT_ID = searchByBarCode($_REQUEST['BAR_CODE'])) {
        foreach ($PRODUCT_ID as $id) {
            if (Add2BasketByProductID($id, 1)) {
                echo '<div class="complite"><script>location.reload();</script></div>';
            } else {
                echo '<div class="err_mess">Товар не доступен к покупке!</div>';
            }
        }
    } else {
        echo '<div class="err_mess">Штрих код не найден!</div>';
    }
} else {
    echo '<div class="err_mess">Что то пошло не так!</div>';
}
?>
