import $ from "jquery";
window.$ = $;
window.jQuery = $;

function startStream($url, $id) {
    const eventSource = new EventSource($url);

    eventSource.onmessage = function (event) {
        const data = JSON.parse(event.data);

        $('#uptime-' + $id).text(data.uptime);
        $('#os_version-' + $id).text(data.os_version);

        if (data.error.length===0) {
            var text="<span class=\"badge bg-success\">"+data.router_status+"</span>";
        }else {
            var text="<span class=\"badge bg-danger\">"+data.router_status+"</span>\n" +
                "<span class=\"invalid-feedback d-block\">"+data.error+"</span>";
        }

        $('#router_status-' + $id).html(text);
        eventSource.close();
    }
}

function CheckUpdateRouter() {
    $("td[router-id]").each(function (i,e) {
        let $id=$(e).attr('router-id');

        startStream('/check_status_router_stream/'+$id, +$id);
    });
}


$(document).ready(function(){
    CheckUpdateRouter();
})

