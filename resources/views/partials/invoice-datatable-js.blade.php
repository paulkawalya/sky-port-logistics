@push('scripts')
<script>
console.log('DataTable initialization script loaded');

// Wait for document ready
$(document).ready(function() {
    console.log('Document ready, initializing DataTable');
    
    // Show/hide loading overlay functions
    function showLoading() {
        $('#loadingOverlay').show();
        console.log('Loading overlay shown');
    }
    
    function hideLoading() {
        $('#loadingOverlay').hide();
        console.log('Loading overlay hidden');
    }
    
    // Force hide loading overlay after a timeout (failsafe)
    function forceHideLoading() {
        setTimeout(function() {
            hideLoading();
            console.log('Loading overlay force hidden by timeout');
        }, 5000); // 5 seconds timeout
    }
    
    // Call force hide immediately to prevent infinite loading
    forceHideLoading();
    
    // Simple debug function
    function logDebug(message) {
        console.log(message);
    }
    
    // Get the active filter from URL
    var urlParams = new URLSearchParams(window.location.search);
    var activeFilter = urlParams.get('filter') || 'all';
    console.log('Active filter:', activeFilter);
    
    try {
        // Check if table element exists
        if ($('#invoices-table').length === 0) {
            console.error('Table element #invoices-table not found');
            hideLoading();
            return;
        }
        
        console.log('Initializing DataTable with client-side processing');
        
        // Show loading overlay
        showLoading();
        
        // Set a timeout to handle cases where the AJAX request hangs
        var ajaxTimeout = setTimeout(function() {
            console.error('AJAX request timed out');
            hideLoading();
            
            // Initialize DataTable with empty data as fallback
            if ($.fn.DataTable.isDataTable('#invoices-table')) {
                $('#invoices-table').DataTable().destroy();
            }
            
            $('#invoices-table').DataTable({
                data: [],
                columns: [
                    { title: 'Invoice No' },
                    { title: 'Client' },
                    { title: 'Date' },
                    { title: 'Total Amount', className: 'text-end' },
                    { title: 'Paid Amount', className: 'text-end' },
                    { title: 'Balance', className: 'text-end' },
                    { title: 'Status' },
                    { title: 'Actions' }
                ],
                language: {
                    emptyTable: 'No data available. Please refresh or try again later.'
                }
            });
            
            // Show error message to user
            alert('Failed to load invoice data. Please refresh the page or try again later.');
        }, 10000); // 10 second timeout
        
        // Initialize DataTable with client-side processing
        var table = $('#invoices-table').DataTable({
            processing: true,
            serverSide: false, // Use client-side processing
            ajax: {
                url: '{{ route("invoices.data") }}',
                type: 'GET',
                data: function(d) {
                    d.filter = activeFilter;
                    console.log('Sending data to server:', d);
                    return d;
                },
                dataSrc: function(json) {
                    // Clear the timeout since we got a response
                    clearTimeout(ajaxTimeout);
                    
                    // Check if data exists and is an array
                    if (json && json.data && Array.isArray(json.data)) {
                        console.log('Data loaded successfully:', json.data.length, 'records');
                        return json.data;
                    } else {
                        console.error('Invalid data format received:', json);
                        return [];
                    }
                },
                beforeSend: function() {
                    showLoading();
                },
                complete: function() {
                    console.log('AJAX request completed');
                    hideLoading();
                    // Clear the timeout since the request completed
                    clearTimeout(ajaxTimeout);
                },
                error: function(xhr, error, thrown) {
                    console.error('DataTables error:', error);
                    console.error('Error thrown:', thrown);
                    console.error('XHR Response:', xhr.responseText);
                    
                    // Clear the timeout since we got an error response
                    clearTimeout(ajaxTimeout);
                    
                    // Hide loading overlay
                    hideLoading();
                    
                    // Show user-friendly error message
                    var errorMessage = 'Error loading data';
                    if (xhr.status === 404) {
                        errorMessage = 'The data endpoint was not found. Please check if the route is defined correctly.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Server error occurred. Please try again later or contact support.';
                    } else if (xhr.status === 403) {
                        errorMessage = 'You do not have permission to access this data.';
                    } else if (error === 'timeout') {
                        errorMessage = 'Request timed out. Please check your connection and try again.';
                    } else if (error === 'parsererror') {
                        errorMessage = 'Invalid response format. Please contact support.';
                    }
                    
                    // Display error in table
                    $('#invoices-table tbody').html('<tr><td colspan="8" class="text-center text-danger">'+errorMessage+'</td></tr>');
                }
            },
            columns: [
                { 
                    data: 'invoice_number', 
                    name: 'invoice_number',
                    render: function(data, type, row) {
                        return data ? '<a href="' + (row.view_url || '#') + '" class="text-primary text-decoration-underline">' + data + '</a>' : 'N/A';
                    }
                },
                { 
                    data: 'customer_name', 
                    name: 'customers.name',
                    render: function(data) {
                        return data || 'N/A';
                    }
                },
                { 
                    data: 'date_invoiced', 
                    name: 'invoices.date_invoiced',
                    render: function(data) {
                        return data || '-';
                    }
                }, 
                { 
                    data: 'amount', 
                    name: 'amount', 
                    className: 'text-end',
                    render: function(data) {
                        return data || '0.00';
                    }
                },
                { 
                    data: 'paid_amount', 
                    name: 'paid_amount', 
                    className: 'text-end',
                    render: function(data) {
                        return data || '0.00';
                    }
                },
                { 
                    data: 'balance', 
                    name: 'balance', 
                    className: 'text-end',
                    render: function(data) {
                        return data || '0.00';
                    }
                },
                { 
                    data: 'status', 
                    name: 'status', 
                    orderable: false,
                    render: function(data) {
                        return data || '<span class="badge bg-secondary">Unknown</span>';
                    }
                },
                { 
                    data: 'action', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false,
                    defaultContent: '<div class="text-center">No actions available</div>'
                }
            ],
            order: [[0, 'desc']],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            language: {
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                zeroRecords: 'No matching records found',
                emptyTable: 'No data available',
                info: 'Showing _START_ to _END_ of _TOTAL_ invoices',
                infoEmpty: 'Showing 0 to 0 of 0 invoices',
                infoFiltered: '(filtered from _MAX_ total invoices)',
                search: '<i class="ri-search-line"></i>',
                searchPlaceholder: 'Search invoices...',
                paginate: {
                    first: '<i class="ri-arrow-left-s-line"></i>',
                    last: '<i class="ri-arrow-right-s-line"></i>',
                    next: '<i class="ri-arrow-right-s-line"></i>',
                    previous: '<i class="ri-arrow-left-s-line"></i>'
                }
            },
            drawCallback: function() {
                // Ensure loading overlay is hidden after table is drawn
                hideLoading();
                console.log('Table drawn, hiding loading overlay');
            },
            initComplete: function() {
                // Hide loading overlay when initialization is complete
                hideLoading();
                console.log('DataTable initialization complete');
                
                // Add custom styling to the search box
                $('.dataTables_filter input').addClass('form-control');
                $('.dataTables_filter input').attr('placeholder', 'Search invoices...');
                
                // Initialize export buttons if they exist
                initExportButtons();
            }
        });
        
        console.log('DataTable initialized successfully');
        
        // Ensure loading overlay is hidden after initialization
        hideLoading();
        
        // Additional failsafe to hide loading overlay after a short delay
        setTimeout(function() {
            hideLoading();
            console.log('Final failsafe: hiding loading overlay');
        }, 1000);
        
        // Initialize export buttons
        function initExportButtons() {
            // Excel export
            $('#export-excel').on('click', function(e) {
                e.preventDefault();
                console.log('Exporting to Excel');
                
                // Create a temporary form to submit the export request
                var csrfToken = '{{ csrf_token() }}';
                var form = $('<form action="{{ route("invoices.export", ["type" => "excel"]) }}" method="POST"></form>');
                form.append('<input type="hidden" name="_token" value="' + csrfToken + '">');
                form.append('<input type="hidden" name="filter" value="' + activeFilter + '">');
                $('body').append(form);
                form.submit();
                form.remove();
            });
            
            // PDF export
            $('#export-pdf').on('click', function(e) {
                e.preventDefault();
                console.log('Exporting to PDF');
                window.open('{{ route("invoices.export", ["type" => "pdf"]) }}?filter=' + activeFilter, '_blank');
            });
            
            // CSV export
            $('#export-csv').on('click', function(e) {
                e.preventDefault();
                console.log('Exporting to CSV');
                window.open('{{ route("invoices.export", ["type" => "csv"]) }}?filter=' + activeFilter, '_blank');
            });
            
            // Print
            $('#export-print').on('click', function(e) {
                e.preventDefault();
                console.log('Printing table');
                
                // Use a simpler print approach
                var $table = $('#invoices-table').clone();
                
                // Create a new window for printing
                var printWindow = window.open('', '_blank', 'width=800,height=600');
                printWindow.document.write('<html><head><title>Invoices</title>');
                printWindow.document.write('<style>');
                printWindow.document.write('body { font-family: Arial, sans-serif; padding: 20px; }');
                printWindow.document.write('h2 { color: #333; margin-bottom: 20px; }');
                printWindow.document.write('table { width: 100%; border-collapse: collapse; }');
                printWindow.document.write('th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }');
                printWindow.document.write('th { background-color: #f2f2f2; }');
                printWindow.document.write('.text-end { text-align: right; }');
                printWindow.document.write('.badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; color: white; }');
                printWindow.document.write('.bg-success { background-color: #28a745; }');
                printWindow.document.write('.bg-warning { background-color: #ffc107; color: #212529; }');
                printWindow.document.write('.bg-danger { background-color: #dc3545; }');
                printWindow.document.write('.bg-secondary { background-color: #6c757d; }');
                printWindow.document.write('</style>');
                printWindow.document.write('</head><body>');
                
                // Add title with filter info
                var filterTitle = activeFilter.charAt(0).toUpperCase() + activeFilter.slice(1);
                printWindow.document.write('<h2>Invoices - ' + filterTitle + '</h2>');
                
                // Add the table content
                printWindow.document.write('<table>');
                printWindow.document.write($table.html());
                printWindow.document.write('</table>');
                
                // Add print script
                printWindow.document.write('<script>');
                printWindow.document.write('window.onload = function() { window.print(); setTimeout(function() { window.close(); }, 500); }');
                printWindow.document.write('</script>');
                printWindow.document.write('</body></html>');
                
                printWindow.document.close();
            });
        }
        
        // Handle filter buttons
        $('.filter-btn').on('click', function(e) {
            e.preventDefault();
            var filter = $(this).data('filter');
            console.log('Filter button clicked:', filter);
            
            // Update active filter
            activeFilter = filter;
            
            // Update active button state
            $('.filter-btn').removeClass('btn-primary').addClass('btn-outline-primary');
            $(this).removeClass('btn-outline-primary').addClass('btn-primary');
            
            // Show loading overlay
            showLoading();
            
            // Reload table with new filter
            table.ajax.reload(function() {
                // Hide loading overlay when data is loaded
                hideLoading();
            });
            
            // Update URL
            var url = new URL(window.location.href);
            url.searchParams.set('filter', filter);
            window.history.pushState({}, '', url);
        });
        
        // Refresh table
        $('#refresh-table').on('click', function() {
            console.log('Refresh button clicked');
            
            // Show loading spinner in button
            var $btn = $(this);
            var originalHtml = $btn.html();
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            $btn.prop('disabled', true);
            
            // Show loading overlay
            showLoading();
            
            // Reload table
            table.ajax.reload(function() {
                // Hide loading overlay when data is loaded
                hideLoading();
                
                // Restore button
                setTimeout(function() {
                    $btn.html(originalHtml);
                    $btn.prop('disabled', false);
                }, 500);
            });
        });
    } catch (error) {
        console.error('Error initializing DataTable:', error);
        hideLoading(); // Make sure to hide loading overlay on error
        
        // Show error message in table
        $('#invoices-table tbody').html('<tr><td colspan="8" class="text-center text-danger">Error initializing table: ' + error.message + '</td></tr>');
    }
});
</script>
@endpush
