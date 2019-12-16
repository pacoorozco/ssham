{!! HTML::script(asset('plugins/select2/select2.min.js')) !!}
{!! HTML::script(asset('plugins/DataTables/media/js/jquery.dataTables.min.js')) !!}
{!! HTML::script(asset('plugins/DataTables/media/js/DT_bootstrap.js')) !!}

<script type="text/javascript">
    $(document).ready(function() {
        $('#{{ $id }}').dataTable({
            @foreach ($options as $k => $o)
                {{ json_encode($k) }}: @if(!is_array($o)) @if(preg_match("/function/", $o)) {{ $o }} @else {{ json_encode($o) }}, @endif
                @else
                [{
                    @foreach ($o as $x => $r)
                    {{ json_encode($x) }}: @if(is_array($r)) {{ json_encode($r) }}, @elseif(preg_match("/function/", $r)) {{ $r }}, @else {{ json_encode($r) }} @endif
                    @endforeach
                }],
                @endif
            @endforeach

            @foreach ($callbacks as $k => $o)
                {{ json_encode($k) }}: {{ $o }},
            @endforeach
            "oLanguage": {
                "sLengthMenu": "Show _MENU_ Rows",
                "sSearch": "",
                "oPaginate": {
                    "sPrevious": "",
                    "sNext": ""
                }
            },
            "aLengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "iDisplayLength": 10,
        } );

        $('#{{ $id }}_wrapper .dataTables_filter input').addClass("form-control input-sm").attr("placeholder", "Search");
        // modify table search input
        $('#{{ $id }}_wrapper .dataTables_length select').addClass("m-wrap small");
        // modify table per page dropdown
        $('#{{ $id }}_wrapper .dataTables_length select').select2();
        // initialzie select2 dropdown
    });
</script>

