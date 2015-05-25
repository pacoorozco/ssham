var TableData = function () {
    //function to initiate DataTable
    //DataTable is a highly flexible tool, based upon the foundations of progressive enhancement, 
    //which will add advanced interaction controls to any HTML table
    //For more information, please visit https://datatables.net/
    var runDataTable = function ( datatable ) {
        var oTable = $( datatable ).dataTable({
            "aoColumnDefs": [{
                    "aTargets": [0]
            }],
            "oLanguage": {
                "sLengthMenu": "Show _MENU_ Rows",
                "sSearch": "",
                "oPaginate": {
                    "sPrevious": "",
                    "sNext": ""
                }
            },
            "aaSorting": [
                [1, 'asc']
            ],
            "aLengthMenu": [
                [5, 10, 15, 20, -1],
                [5, 10, 15, 20, "All"] // change per page values here
            ],
            // set the initial value
            "iDisplayLength": 10,
        } );
        
        return oTable;
        
        
        //Set the defaults for DataTables initialisation
        /* $.extend( true, $.fn.dataTable.defaults, {
            "aoColumnDefs": [{
                "aTargets": [0]
            }],
            "oLanguage": {
                "sLengthMenu": "Show _MENU_ Rows",
                "sSearch": "",
                "oPaginate": {
                    "sPrevious": "",
                    "sNext": ""
                }
            },
            "aaSorting": [
                [1, 'asc']
            ],
        } ); 

        $.fn.dataTable.defaults.aLengthMenu = [
            [5, 10, 15, 20, -1],
            [5, 10, 15, 20, "All"]
        ];*/

        //Default class modification
        //$(datatableId + '_wrapper .dataTables_filter input').addClass("form-control input-sm").attr("placeholder", "Search");
        // modify table search input
        //$(datatableId + '_wrapper .dataTables_length select').addClass("m-wrap small");
        // modify table per page dropdown
        //$(datatableId + '_wrapper .dataTables_length select').select2();
        // initialzie select2 dropdown
    };  
    
    return {
        //main function to initiate template pages
        init: function (datatable) {
            return runDataTable(datatable);
        }
    };
}();