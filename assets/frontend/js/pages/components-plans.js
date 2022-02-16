"use strict";
function update_amount(count) {
    var id = $('#tenure_'+count+'_price').val();
    if (id != '') {
        $.ajax({
            type: 'POST',
            url: base_url + 'admin/billing/get_tenure_by_id/' + id,
            data: csrfName + '=' + csrfHash,
            dataType: "json",
            success: function (result) {
                csrfName = csrfName;
                csrfHash = result.csrfHash;
                $('.tenure_'+count+'_price').html(result.rate);
                $('.tenure_'+count+'_months').html(result.months);
            }
        });
    } else {
        $('.item_' + count + '_tax_title').html('');
    }
}
