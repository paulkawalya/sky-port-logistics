@push('styles')
<style>
    /* Custom styles for DataTable */
    .dataTables_wrapper {
        width: 100% !important;
        margin: 0 !important;
        padding: 1rem 1.5rem !important;
    }
    
    /* Search Input Styling */
    .dataTables_filter {
        float: right;
        margin-bottom: 1rem;
    }
    
    .dataTables_filter label {
        display: flex;
        align-items: center;
        margin: 0;
    }
    
    .dataTables_filter input {
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 0.5rem 1rem 0.5rem 2.5rem !important;
        margin-left: 0.5rem !important;
        width: 280px !important;
        height: 38px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Ccircle cx='11' cy='11' r='8'%3E%3C/circle%3E%3Cline x1='21' y1='21' x2='16.65' y2='16.65'%3E%3C/line%3E%3C/svg%3E") no-repeat 12px center;
        background-size: 18px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    .dataTables_filter input:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        outline: none;
    }
    
    /* Length Menu Styling */
    .dataTables_length {
        margin-bottom: 1rem;
    }
    
    .dataTables_length label {
        display: flex;
        align-items: center;
        margin: 0;
        font-size: 0.875rem;
        color: #4b5563;
    }
    
    .dataTables_length select {
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 0.5rem 2rem 0.5rem 1rem !important;
        margin: 0 0.5rem !important;
        height: 38px;
        font-size: 0.875rem;
        background-color: #fff;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        appearance: none;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }
    
    .dataTables_length select:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        outline: none;
    }
    
    /* Pagination Styling */
    .dataTables_paginate {
        margin-top: 1rem;
    }
    
    .dataTables_paginate .paginate_button {
        border: 1px solid #e2e8f0 !important;
        border-radius: 6px !important;
        padding: 0.5rem 0.75rem !important;
        margin: 0 0.25rem !important;
        background-color: #fff !important;
        color: #4b5563 !important;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .dataTables_paginate .paginate_button.current {
        background-color: #3b82f6 !important;
        border-color: #3b82f6 !important;
        color: #fff !important;
    }
    
    .dataTables_paginate .paginate_button:hover:not(.current) {
        background-color: #f3f4f6 !important;
        border-color: #d1d5db !important;
        color: #1f2937 !important;
    }
    
    /* Status Badge Styling */
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.35em 0.65em;
        border-radius: 0.375rem;
    }
    
    /* Action Button Styling */
    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
    }
</style>
@endpush
