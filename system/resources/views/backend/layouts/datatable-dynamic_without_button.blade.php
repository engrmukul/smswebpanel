<script>
    $(document).ready(function () {
        var datatable_options = {
            processing: true,
            serverSide: true,
            // dom: 'Bfrtip',
            language: {
                processing: "<img width='75px' src='{{ asset('assets/images/loading.gif') }}'>",
            },
            columnDefs: [
                {
                    targets: 0,
                    render: function (data, type, row, meta) {
                        return type != 'filter' ? meta.row + 1 : data;
                    }
                }
            ],

            columns: [
                @foreach($tableHeaders as $key=>$tableHeader)
                {data: "{{$key}}"},
                @endforeach
            ],

            pageLength: 20,
            lengthMenu: [[20, 30, 50, -1], [20, 30, 50, "All"]],
            searching:false,
            ajax: "{{ $ajaxUrl }}",
            rowCallback: function( row, data ) {
                $(row).find('.ajax-form, .ajax-loading').loadAjaxContents();
            }
        };


        if ($.fn.dataTable.isDataTable('#datatable')) {
            var table = $('#datatable').DataTable();
        } else {
            var table = $('#datatable').DataTable(datatable_options);
        }

        var frm = $('#reportGet');
        frm.submit(function (e) {
            e.preventDefault();
            var url = frm.serialize()
            table.ajax.url('{{ $ajaxUrl }}?' + url).load();
        });
        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel, .buttons-page-length').addClass('btn mb-2');

    });

</script>
