
"use strict";

function queryParams(p) {
    return {
        "workspace_id": $("#workspace_id").val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
function queryParams1(p) {
    return {
        "user_type": 3,
        "workspace_id": $("#clients_workspace_id").val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
$(document).on('click', '#btn-add-workspace', function (e) {
    $("#modal-add-workspace").trigger("click");
});
