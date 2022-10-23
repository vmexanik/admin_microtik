import $ from "jquery";
window.$ = $;
window.jQuery = $;

function checkUpdateButton(){
    let $checkboxes=$('table').find("input[type='checkbox']").not(':disabled');
    let $button=$('#update_selected');

    if ($checkboxes.length>1){
        $button.prop('disabled',false);
        $button.removeClass('disabled');
    }else{
        $button.prop('disabled',true);
        $button.addClass('disabled');
    }
}

function startStream($url, $id) {
    const eventSource = new EventSource($url);

    eventSource.onmessage = function (event) {
        const data = JSON.parse(event.data);

        if (data.status === 'close') {
            eventSource.close();
        }else{
            $('#status-' + $id).text(data.router_status);
            $('#installed_version-' + $id).text(data.installed_version);
            $('#latest_version-' + $id).text(data.latest_version);

            if (data.checkbox=='disable'){
                $("#row_check_"+$id).prop('disabled', true);
                $("#row_check_"+$id).prop('checked', false);
            }else{
                $("#row_check_"+$id).prop('disabled', false);
                $("#row_check_"+$id).prop('checked', false);
            }

            checkUpdateButton();
        }
    }
}

function CheckUpdateRouter() {
    $("td[router-id]").each(function (i,e) {
        let $id=$(e).attr('router-id');

        startStream('/check_update_router_stream/'+$id, +$id);
    });
}

function updateRoutes() {
    let $checkboxes=$('table').find("input[type='checkbox']:checked").not(':disabled').not("[name='check_all']");
    $checkboxes.each(function (i,e) {
        let $id=$(e).val();

        startStream('/update_router_stream/'+$id, +$id);
    });
}

window.updateRoutes=updateRoutes;

$(document).ready(function(){
    CheckUpdateRouter();

    $("[name='check_all']").change(function (){
        checkUpdateButton();
        var $allCheckbox=$(this).parents('table').find("input[type='checkbox']").not(':disabled');
        if ($(this).is(':checked')){
            $allCheckbox.prop('checked',true);
        }else{
            $allCheckbox.prop('checked',false);
        }
    });
})

