<div style="" id="one-click">
    <div class="popup__ttl">Купить в 1 клик</div>
    <form class="one-click-form" data-ajax="/ajax/one_click.php">
        <input type="hidden" name="PROP[PRODUCT_ID]" value="" id="productId">
        <input type="hidden" name="PROP[PRODUCT_NAME]" value="" id="productName">
        <input type="hidden" name="PROP[PRODUCT_LINK]" value="" id="productUrl">
        <? global $USER; ?>
        <div class="one-click-form__inner">
            <label>Представьтесь*</label>
            <div class="field-wrp">
                <input class="field chk" type="text" placeholder="Ваше имя" name="NAME"
                       value="<?= $USER->GetFullName() ?>" autocomplete="off">
            </div>
            <label>Ваш номер телефона с кодом оператора*</label>
            <div class="field-wrp">
                <input class="field chk" type="tel" placeholder="Телефон" name="PROP[PHONE]"
                       autocomplete="off" data-validation-type="phone">
            </div>

            <?if($USER->IsAdmin() || true):?>
                <div class="field-wrp">
                    <label>Количество едениц товара*</label>
                    <div class="field-wrp">
                        <input class="field chk" type="number" placeholder="Количество" name="PROP[QUANTITY]"
                               autocomplete="off" data-validation-type="number" value="1" max="10">
                    </div>
                </div>
            <?endif;?>

            <div class="field-wrp">
                <label style="color: rgb(0, 0, 0); font-weight: 400;">Ваш город Минск?
                    <input class="" type="checkbox" name="PROP[MINSK]" value="Y"
                           style="vertical-align: middle; margin-left: 3px;">
                </label>
            </div>
            <?
            switch (SITE_ID) {
                case 's1':
                    $minOrderPrice = 30;
                    break;
                default:
                    $minOrderPrice = 30;//50;
                    break;
            }
            ?>
            <div class="one-click_text-form">Доставка осуществляется курьером</div>
            <div class="one-click_text-form">Минимальная сумма заказа - <?= $minOrderPrice ?> руб.</div>
            <input class="btn btn_black" type="submit" value="Купить ">
        </div>
        <div class="one-click-form__result hide">
            Ваша заявка принята<br>
            Менеджер свяжется с Вами для подтверждения заказа.<br><br><br><br>
            Наше рабочее время<br>
            ПН - ПТ 10:30 - 17:30
        </div>
    </form>
</div>
<script>
    $(function(){
        $('input[data-validation-type="phone"]').mask("+375(99) 999-99-99");
    });

    document.body.addEventListener('click', function (event) {
        if(!event.target.classList.contains('section-one-click-btn')){
            return false;
        }
        let parent = event.target.closest('.prod-item');
        let productId = parent.querySelector('[data-id]').dataset.id;
        let productUrl = parent.querySelector('.prod-name').href;
        let productName = parent.querySelector('.prod-name').textContent;

        document.getElementById('productId').value = productId;
        document.getElementById('productName').value = productName;
        document.getElementById('productUrl').value = productUrl;
    }, true);
</script>