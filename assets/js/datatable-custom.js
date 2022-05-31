$(document).ready(function () {

    //Buttons examples
    var table = $('#datatable').DataTable({
        dom: 'Bfrtip',
        pageLength: 20,
        lengthMenu: [[20, 30, 50, -1], [20, 30, 50, "All"]],
        buttons: ['pageLength',
            {
                extend: 'print',
                text: 'Print',
                autoPrint: true,
                autoWidth: false,
                exportOptions: {
                    columns: [':not(.hidden-print)']
                },

                customize: function (win) {

                    $(win.document.body).find('h1').css('text-align', 'center');
                    $(win.document.body).find('table')
                        .removeClass('table-striped table-responsive table-responsive-sm table-responsive-lg dataTable table-responsive-xl')
                        .addClass('compact')
                        .css('font-size', 'inherit', 'color', '#000');

                }
            }
        ],

    });
    table.on('order.dt search.dt', function () {
        table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
            table.cell(cell).invalidate('dom');
        });
    }).draw();
    $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel, .buttons-page-length').addClass('btn mb-2');
});