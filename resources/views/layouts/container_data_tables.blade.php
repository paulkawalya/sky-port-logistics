@section('styles')

   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
   <style>
        /* DataTable wrapper styling */
        .dataTables_wrapper {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 20px;
        }

        /* DataTable controls styling */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            color: #495057;
            font-size: 14px;
            padding: 16px 20px;
        }

        /* Length dropdown styling */
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 8px 12px;
            background-color: white;
            margin: 0 8px;
            font-weight: 500;
            color: #495057;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        /* Search box styling */
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #dee2e6;
            border-radius: 6px;
            padding: 10px 16px;
            background-color: white;
            margin-left: 8px;
            width: 250px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        /* Table styling */
        table.dataTable {
            border-collapse: collapse;
            width: 100%;
            margin: 0 !important;
        }

        table.dataTable thead th {
            padding: 16px 20px;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #e9ecef;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: linear-gradient(to right, #f8f9fa, #f1f3f5);
        }

        table.dataTable tbody td {
            padding: 16px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #eaeaea;
            color: #495057;
        }

        table.dataTable tbody tr:hover {
            background-color: rgba(0, 99, 247, 0.02);
        }

        /* Pagination styling */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 6px;
            border: 1px solid #dee2e6;
            background: white;
            padding: 8px 14px;
            margin: 0 3px;
            color: #495057 !important;
            font-weight: 500;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #0063f7 !important;
            color: white !important;
            border-color: #0063f7;
            box-shadow: 0 2px 5px rgba(0, 99, 247, 0.3);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f8f9fa !important;
            color: #0063f7 !important;
            border-color: #0063f7;
            transform: translateY(-2px);
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }

        /* Export buttons styling */
        .dt-buttons {
            padding: 16px 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #eaeaea;
        }

        .dt-buttons .dt-button {
            padding: 8px 16px;
            border-radius: 6px;
            margin-right: 10px;
            border: none;
            font-size: 13px;
            font-weight: 500;
            color: white;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: all 0.2s;
        }

        .dt-button i {
            margin-right: 6px;
        }

        .dt-button.excel-button {
            background-color: #217346;
        }

        .dt-button.pdf-button {
            background-color: #dc3545;
        }

        .dt-button.csv-button {
            background-color: #0dcaf0;
        }

        .dt-button:hover {
            opacity: 0.9;
            transform: translateY(-2px);
            box-shadow: 0 3px 6px rgba(0,0,0,0.15);
        }

        /* Status badge styling */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 13px;
        }

        /* Action buttons styling */
        .action-btn {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 13px;
            margin-right: 6px;
            border: none;
            transition: all 0.2s;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }

        .action-btn.view-btn {
            background-color: rgba(0, 99, 247, 0.1);
            color: #0063f7;
        }

        .action-btn.edit-btn {
            background-color: rgba(108, 117, 125, 0.1);
            color: #6c757d;
        }
   </style>

 @endsection



 @push('scripts')
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="{{ asset('https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js') }}"></script>
	<script>
        // We'll initialize the DataTable in the container.blade.php file
	</script>
@endpush
