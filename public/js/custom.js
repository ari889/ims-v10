$('.dropify').dropify(); // Initialize dropify
$('.selectpicker').selectpicker({
    dropupAuto: false
});
/**
 * checked all data in datatable
 */
function select_all() {
    if ($('#select_all:checked').length == 1) {
        $('.select_data').prop('checked', true);
        if ($('.select_data:checked').length >= 1) {
            $('.delete_btn').removeClass('d-none');
        }
    } else {
        $('.select_data').prop('checked', false);
        $('.delete_btn').addClass('d-none');
    }
}

/**
 * select single data in datatable
 */
function select_single_item(id) {
    var total = $('.select_data').length;
    var total_checked = $('.select_data:checked').length;
    (total == total_checked) ? $('#select_all').prop('checked', true): $('#select_all').prop('checked', false);
    (total_checked > 0) ? $('.delete_btn').removeClass('d-none'): $('.delete_btn').addClass('d-none');
}

/**
 * show form modal
 */
var myModal;

function showFormModal(modal_title, btn_text) {
    $('#store_or_update_form')[0].reset();
    $('#store_or_update_form #update_id').val('');
    $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
    $('#store_or_update_form').find('.error').remove();
    $('#store_or_update_form .dropify-clear').trigger('click');
    $('#store_or_update_form .selectpicker').selectpicker('refresh');
    $('#store_or_update_form table tbody').find('tr:gt(0)').remove();

    myModal = new bootstrap.Modal(document.getElementById('store_or_update_modal'), {
        keyboard: false,
        backdrop: 'static'
    });

    myModal.show();

    $("#store_or_update_modal .modal-title").html('<i class="fas fa-plus-square"></i> ' + modal_title);
    $("#store_or_update_modal #save-btn").text(btn_text);
}

/**
 * sweet alert notification
 */
function notification(status, message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: status,
        title: message
    });
}

/**
 * store or update data
 */
function store_or_update_data(table, method, url, formData) {
    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function () {
            $('#save-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
        },
        complete: function () {
            $('#save-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
        },
        success: function (data) {
            $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
            $('#store_or_update_form').find('.error').remove();
            if (data.status == false) {
                $.each(data.errors, function (key, value) {
                    $('#store_or_update_form input#' + key).addClass('is-invalid');
                    $('#store_or_update_form textarea#' + key).addClass('is-invalid');
                    $('#store_or_update_form select#' + key).parent().addClass('is-invalid');
                    $('#store_or_update_form #' + key).parent().append(
                        '<small class="error text-danger">' + value + '</small>'
                    )
                });
            } else {
                notification(data.status, data.message);
                if (data.status == 'success') {
                    if (method == 'update') {
                        table.ajax.reload(null, false);
                    } else {
                        table.ajax.reload();
                    }
                    myModal.hide();
                }
            }
        }
    });
}

/**
 * delete single data from database
 */
function delete_data(id, url, table, row, name) {
    Swal.fire({
        title: 'Are you sure to delete ' + name + ' data?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    id: id,
                    _token: _token
                },
                dataType: "JSON",

            }).done(function (response) {
                if (response.status == 'success') {
                    Swal.fire("Deleted", response.message, "success").then(function () {
                        table.row(row).remove().draw(false);
                    });
                }
                if (response.status == 'error') {
                    Swal.fire('Opps...', "Something went wrong!", "error");
                }
            }).fail(function () {
                Swal.fire('Opps...', "Something went wrong with ajax!", "error");
            });
        }
    })
}

/**
 * bulk delete data from datatable
 */
function bulk_delete(ids, url, table, rows){
    Swal.fire({
        title: 'Are you sure to delete checked data?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: "POST",
                data: {
                    ids: ids,
                    _token: _token
                },
                dataType: "JSON",

            }).done(function (response) {
                if (response.status == 'success') {
                    Swal.fire("Deleted", response.message, "success").then(function () {
                        table.rows(rows).remove().draw(false);
                        $('#select_all').prop('checked', false);
                        $('.delete_btn').addClass('d-none');
                    });
                }
                if (response.status == 'error') {
                    Swal.fire('Opps...', "Something went wrong!", "error");
                }
            }).fail(function () {
                Swal.fire('Opps...', "Something went wrong with ajax!", "error");
            });
        }
    })
}
