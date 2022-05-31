$(document).ready(function () {

    var table = $('#datatable').DataTable({
        dom: 'Bfrtip',
        pageLength: 20,
        columnDefs: [
            { "orderable": false, "targets": '' }
        ],
        lengthMenu: [[20, 30, 50, -1], [20, 30, 50, "All"]],
        buttons: ['pageLength',
            {
                extend: 'print',
                text: 'Print',
                autoPrint: true,
                exportOptions: {
                    columns: [':not(.hidden-print)']
                },

                customize: function (win) {

                    $(win.document.body).find('h1').css('text-align', 'center');
                    $(win.document.body).find('table')
                        .removeClass('table-striped table-responsive-sm table-responsive-lg dataTable table-responsive-xl table-responsive')
                        .addClass('compact')
                        .css('font-size', 'inherit', 'color', '#000');

                }
            }
        ]

    });

    table.on('order.dt search.dt', function () {
        table.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
            table.cell(cell).invalidate('dom');
        });
    }).draw();

    $('#datatable thead tr').clone(true).appendTo( '#datatable thead' );
    var total = $('#datatable thead tr:eq(0) th').length;
    $('#datatable thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();

        if(i === total-1 || i == 0){
            $(this).html('');
        } else {

            var select = $('<select class="select2"><option value="">Select ' + title + '</option></select>')
                .appendTo($(this).empty())
                .on('change', function () {
                    var term = $(this).val();
                    table.column(i).search(term, false, false).draw();
                });
            table.column(i).data().unique().sort().each(function (d, j) {
                select.append('<option value="' + d + '">' + d + '</option>')
            });
        }
    });
    // $('#datatable thead tr:eq(0)').addClass('hidden-print');
    $('#datatable thead select').css('width','100%');
    $('#datatable thead select option').css('width','100%');
    $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel, .buttons-page-length').addClass('btn mb-2');
    $('.select2').select2();
});
