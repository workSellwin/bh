$(document).ready(function(){

        alert('Структура страницы сформирована, можно приступать!');

});

function ajax_discount(url, input_name, data_elem_id, status_checked) {
    $.post(
        url,
        {
            input_name: input_name,
            data_elem_id: data_elem_id,
            status_checked: status_checked,
            action_checked_ajax: 'Y'
        },
        onAjaxSuccess
    );
    function onAjaxSuccess(data)
    {
        console.log(data);
    }
}

function ajax_select_discount(ar_elem_id, ar_option_id, url, name, lid) {
    $.post(
        url,
        {
            ar_elem_id: ar_elem_id,
            ar_option_id: ar_option_id,
            name: name,
            lid: lid,
            ajax_select_discount: 'Y'
        },
        onAjaxSuccess
    );
    function onAjaxSuccess(data)
    {
        console.log(data);
    }
}

