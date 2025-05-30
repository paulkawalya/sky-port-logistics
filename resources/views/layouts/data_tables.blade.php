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

        /* Button styling */
        .dt-buttons .btn {
            margin-right: 8px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 13px;
            padding: 8px 16px;
            transition: all 0.2s ease;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                text-align: left;
                padding: 10px;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: 100%;
                margin: 10px 0;
            }

            .dt-buttons {
                margin-bottom: 10px;
                text-align: left;
            }
        }

        /* Custom styling for action buttons */
        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            margin: 0 2px;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }

        /* Status badges */
        .badge {
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 4px;
            font-weight: 500;
            text-transform: capitalize;
        }

        /* Ensure proper spacing in table cells */
        td, th {
            white-space: nowrap;
        }

        /* Hide the status filter column */
        .status-column {
            display: none;
        }
    </style>
@endsection

@push('scripts')
    <!-- DataTables scripts are already loaded in the main app.blade.php file -->
    <script>
        $(document).ready(function() {
            // Initialize tables with custom options from data attributes if available
            $('#table_two').each(function() {
                const $table = $(this);
                const options = {
                    responsive: true,
                    pageLength: $table.data('page-length') || 10,
                    lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                    dom: '<"d-flex justify-content-between align-items-center mb-3"<"d-flex align-items-center"l><"d-flex align-items-center"f>>rt<"d-flex justify-content-between align-items-center mt-3"<"d-flex align-items-center"i><"d-flex align-items-center"p>>B',
                    deferRender: true,
                    order: $table.data('order') || [[0, 'desc']], // Default to first column (date) descending
                language: {
                    search: "<i class='ri-search-line'></i>",
                    searchPlaceholder: $table.data('search-placeholder') || "Search...",
                    lengthMenu: "<span class='me-4'>Show</span> _MENU_ <span class='ms-2'>entries</span>",
                    info: $table.data('info-text') || "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: '<i class="ri-arrow-left-s-line"></i><i class="ri-arrow-left-s-line"></i>',
                        last: '<i class="ri-arrow-right-s-line"></i><i class="ri-arrow-right-s-line"></i>',
                        next: '<i class="ri-arrow-right-s-line"></i>',
                        previous: '<i class="ri-arrow-left-s-line"></i>'
                    },
                    emptyTable: '<div style="padding: 30px; text-align: center;"><div style="width: 80px; height: 80px; border-radius: 50%; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;"><i class="ri-inbox-line" style="font-size: 40px; color: #adb5bd;"></i></div><h5 style="margin-bottom: 10px; color: #495057;">No Data Found</h5><p style="color: #6c757d; max-width: 300px; margin: 0 auto;">There are no records to display at this time.</p></div>'
                },
                buttons: [
                    {
                        extend: 'excelHtml5',
                        messageTop: '{{$title}}',
                        className: 'excel-button',
                        text: '<i class="ri-file-excel-2-line"></i> Excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        messageTop: '{{$title}}',
                        className: 'pdf-button',
                        text: '<i class="ri-file-pdf-line"></i> PDF',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        className: 'csv-button',
                        text: '<i class="ri-file-text-line"></i> CSV',
                        exportOptions: {
                            columns: ':visible'
                        }
                    }
                ],
                // Apply custom classes to status and action buttons
                createdRow: function(row, data, dataIndex) {
                    // Apply status badge class to elements with status indicators
                    $(row).find('td span.badge, td div[style*="border-radius: 20px"]').addClass('status-badge');

                    // Apply action button classes
                    $(row).find('td a.btn, td button.btn').addClass('action-btn');
                    $(row).find('td a.btn:contains("View"), td button.btn:contains("View")').addClass('view-btn');
                    $(row).find('td a.btn:contains("Edit"), td button.btn:contains("Edit")').addClass('edit-btn');
                }
                };

                // Initialize DataTable with options
                $table.DataTable(options);
            });
        });

        $(document).ready(function() {
            $('#table_unsorted').dataTable({
                "aaSorting": [],
                pageLength: 1000,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        className: 'excel-button',
                        text: '<i class="fa fa-file-excel-o"></i> Excel'
                    },
                    {
                        extend: 'pdfHtml5',
                        className: 'pdf-button',
                        text: '<i class="fa fa-file-pdf-o"></i> PDF'
                    },
                    {
                        extend: 'csvHtml5',
                        className: 'csv-button',
                        text: '<i class="fa fa-file-text-o"></i> CSV'
                    }
                ]
            });
        });

        $(document).ready(function() {
            $('#marks').dataTable({
                pageLength: 1000,
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        className: 'excel-button',
                        text: '<i class="fa fa-file-excel-o"></i> Excel'
                    },
                    {
                        extend: 'pdfHtml5',
                        className: 'pdf-button',
                        text: '<i class="fa fa-file-pdf-o"></i> PDF'
                    },
                    {
                        extend: 'csvHtml5',
                        className: 'csv-button',
                        text: '<i class="fa fa-file-text-o"></i> CSV'
                    }
                ]
            });
        })

	</script>
@endpush
