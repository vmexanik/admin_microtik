import $ from "jquery";

function updateUserPasswords() {
    let $checkboxes = $('table').find("input[type='checkbox']:checked").not(':disabled').not("[name='check_all']");
    let $username = $('#login').val();
    let $password = $('#password').val();

    if ($checkboxes.length) {
        var $id = [];
        $checkboxes.each(function (i, e) {
            $id.push($(e).val());
        });

        $.ajax({
            url: '/user_router_update_password',
            data: {
                username: $username,
                password: $password,
                routers: $id
            }
        }).done(function (data) {
            $id.forEach(function (currentValue, index) {
                $('table').find("input[type='checkbox'][value=" + currentValue + "]").parents('tr').find('td.status').html(data[currentValue]);
            })
        });
    }
}

window.updateUserPasswords = updateUserPasswords;

function checkUpdateButton() {
    let $checkboxes = $('table').find("input[type='checkbox']:checked").not(':disabled').not("[name='check_all']");
    let $button=$('#update_password_selected');

    if ($checkboxes.length>0){
        $button.prop('disabled',false);
        $button.removeClass('disabled');
    }else{
        $button.prop('disabled',true);
        $button.addClass('disabled');
    }
}

window.checkUpdateButton = checkUpdateButton;

$(document).ready(function () {
    checkUpdateButton();
    $("[name='check_all']").change(function () {
        checkUpdateButton();
        var $allCheckbox = $(this).parents('table').find("input[type='checkbox']").not(':disabled');
        if ($(this).is(':checked')) {
            $allCheckbox.prop('checked', true);
        } else {
            $allCheckbox.prop('checked', false);
        }
    });

    $("input[type='checkbox']").change(function () {
        checkUpdateButton();
    });
})
