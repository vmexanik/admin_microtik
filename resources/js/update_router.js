import $ from "jquery";

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
        }
    }
}

function CheckUpdateRouter() {
    $("td[router-id]").each(function (i,e) {
        let $id=$(e).attr('router-id');

        startStream('/check_update_router_stream/'+$id, +$id);
    });
}

CheckUpdateRouter();
