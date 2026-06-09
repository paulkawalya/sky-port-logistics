<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceDetailController;
use App\Http\Controllers\InvoicePaymentController;
use App\Http\Controllers\InvoiceTaxController;
use App\Http\Controllers\PaymentAccountController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\QuotationDetailController;
use App\Http\Controllers\QuotationPaymentController;
use App\Http\Controllers\QuotationTaxController;
use App\Http\Controllers\RequisitionApproverController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\RequisitionDetailController;
use App\Http\Controllers\RequisitionDocumentController;
use App\Http\Controllers\RequisitionPaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ShippmentController;
use App\Http\Controllers\ShippmentDocumentController;
use App\Http\Controllers\ShippmentTranferController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransitController;
use App\Http\Controllers\TransitDocumentController;
use App\Http\Controllers\TransitStatusController;
use App\Http\Controllers\Usercontroller;
use App\Http\Controllers\UserPermissionController;
use App\Models\RequisitionPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    if(Auth::guest())

        return view('auth.login');

    return redirect('home');

});

// Authentication Routes...
Auth::routes();

// Public routes for accessing PDFs without authentication
Route::get('public/requisition/{hashedId}/pdf', [RequisitionController::class, 'publicPdf'])->name('requisitions.public.pdf');
Route::get('public/invoice/{hashedId}/pdf', [InvoiceController::class, 'publicPdf'])->name('invoices.public.pdf');
Route::get('public/quotation/{hashedId}/pdf', [QuotationController::class, 'publicPdf'])->name('quotations.public.pdf');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('users',Usercontroller::class);
    Route::post('users/{id}/reset-password', [Usercontroller::class, 'resetPassword'])->name('users.reset-password');
    Route::get('backup-database', [Usercontroller::class, 'backupDatabase'])->name('backup.database');
    Route::get('admin-settings', [Usercontroller::class, 'adminSettings'])->name('admin.settings');

    Route::resource('customers',CustomerController::class);

    Route::resource('agents',AgentController::class);

    // Data route must come before resource route with parameters to avoid conflicts
    Route::get('containers/data', [TransitController::class, 'getData'])->name('containers.data');
    Route::resource('containers',TransitController::class);
    Route::post('containers/{transit}/mark-as-paid', [TransitController::class, 'markAsPaid'])->name('containers.mark-as-paid');
    Route::get('containers/{transit}/details', [TransitController::class, 'details'])->name('containers.details');

    Route::resource('container_status',TransitStatusController::class);

    Route::resource('container_documents',TransitDocumentController::class);

    Route::resource('shippments',ShippmentController::class);

    Route::post('save_shipment',[ShippmentController::class,'saveManually']);

    Route::resource('shippment_documents',ShippmentDocumentController::class);

    Route::get('invoices/data', [InvoiceController::class, 'getInvoicesData'])->name('invoices.data');
    Route::get('invoices/search', [InvoiceController::class, 'search'])->name('invoices.search');
    Route::get('invoices/export/{type}', [InvoiceController::class, 'export'])->name('invoices.export');
    Route::post('invoices/export/{type}', [InvoiceController::class, 'export'])->name('invoices.export');
    Route::resource('invoices',InvoiceController::class);

    Route::get('paid_invoices',[InvoiceController::class,'paidInvoices'])->name('paid_invoices');

    Route::get('pending_invoices',[InvoiceController::class,'pendingInvoices'])->name('pending_invoices');

    Route::resource('invoice_details',InvoiceDetailController::class);

    Route::resource('payments',InvoicePaymentController::class);

    Route::get('invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    Route::get('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');

    Route::resource('transfers',ShippmentTranferController::class);

    // Encrypted routes for requisitions (primary routes)
    Route::get('secure/requisitions/{hashedId}',[RequisitionController::class,'showEncrypted'])->name('requisitions.secure.show');
    Route::get('secure/print_requisition/{hashedId}',[RequisitionController::class,'printEncrypted'])->name('requisitions.secure.print');
    Route::get('secure/pdf_requisition/{hashedId}',[RequisitionController::class,'pdfEncrypted'])->name('requisitions.secure.pdf');

    // Redirect plain ID routes to secure routes
    Route::get('requisitions/{id}', function($id) {
        $requisition = App\Models\Requisition::find($id);
        if (!$requisition) {
            abort(404);
        }
        return redirect()->route('requisitions.secure.show', $requisition->hashed_id);
    })->where('id', '[0-9]+')->name('requisitions.show');

    Route::get('print_requisition/{requisition_id}', function($requisition_id) {
        $requisition = App\Models\Requisition::find($requisition_id);
        if (!$requisition) {
            abort(404);
        }
        return redirect()->route('requisitions.secure.print', $requisition->hashed_id);
    });

    // Keep other requisition routes
    Route::resource('requisitions', RequisitionController::class)->except(['show']);
    
    // Datatable route
    Route::get('requisitions/datatable', [RequisitionController::class, 'datatable'])
        ->name('requisitions.datatable');

    Route::resource('requisition_details',RequisitionDetailController::class);

    Route::resource('approvals',RequisitionApproverController::class);

    Route::resource('requisition_documents',RequisitionDocumentController::class);

    Route::resource('expenses',ExpenseController::class);

    Route::resource('permissions',UserPermissionController::class);

    Route::get('return_number',[InvoiceController::class,'return_number']);
    Route::get('payment_status_count',[TransitController::class,'paymentStatusCount']);

    Route::resource('quotations',QuotationController::class);

    Route::get('/quotations/{id}/print', [QuotationController::class, 'print'])->name('quotations.print');
    Route::get('/quotations/{id}/pdf', [QuotationController::class, 'generatePdf'])->name('quotations.pdf');
    Route::post('/quotations/{id}/mark-as-paid', [QuotationController::class, 'markAsPaid'])->name('quotations.mark-as-paid');

    Route::get('/quotation/print/{id}', [QuotationController::class, 'printQuotation'])->name('quotation.print');

    Route::resource('quotation_details',QuotationDetailController::class);
    Route::post('quotation_details/import', [QuotationDetailController::class, 'import'])->name('quotation_details.import');

    // Example of encrypted route
    Route::get('/quotation/secure/{id}', function($id) {
        // Decrypt the ID parameter
        $decryptedId = decrypt($id);
        return redirect()->route('quotations.show', $decryptedId);
    })->name('quotation.secure');

    // Example of hashed ID route
    Route::get('/quotation/{hash}', function($hash) {
        $quotation = Quotation::findByHash($hash);
        if (!$quotation) {
            abort(404);
        }
        return view('quotation.show', compact('quotation'));
    })->name('quotation.hashed');

    //Ajax container request
    Route::get('/containers/filter', [TransitController::class, 'filter']);

    // Show profile page
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
    Route::post('/profile/update-name', [UserController::class, 'updateName'])->name('profile.update-name');

    // Update password
    Route::post('/profile/update-password', [UserController::class, 'updatePassword'])->name('profile.update-password');

    // Delete account
    Route::post('/profile/delete-account', [UserController::class, 'deleteAccount'])->name('profile.delete-account');

    Route::resource('payment_accounts',PaymentAccountController::class);

    Route::resource('transactions',TransactionController::class);

    Route::get('related_accounts/{account_id}',[TransactionController::class,'relatedAccounts']);

    Route::get('get_accounts/{mode_of_payment}/{currency}',[TransactionController::class,'getAccounts']);

    Route::resource('quotation_payments',QuotationPaymentController::class);

    Route::resource('expense_categories',ExpenseCategoryController::class);

    Route::resource('suppliers',SupplierController::class);

    Route::resource('requisition_payments',RequisitionPaymentController::class);

    Route::get('requsition_amount_detail/{detail_id}',[RequisitionDetailController::class,'detailAmount']);

    Route::resource('taxes',TaxController::class);

    Route::resource('invoice_taxes',InvoiceTaxController::class);

    Route::resource('quotation_taxes',QuotationTaxController::class);

    Route::patch('/payment_accounts/{paymentAccount}/update_balance', [PaymentAccountController::class, 'updateBalance'])->name('payment_accounts.update_balance');

    Route::get('/reports/dashboard', [ReportController::class, 'dashboard'])->name('reports.dashboard');

    // Finance Dashboard Routes
    Route::get('/finance/dashboard', [App\Http\Controllers\FinanceController::class, 'dashboard'])->name('finance.dashboard');
    Route::get('/finance/dashboard/data', [App\Http\Controllers\FinanceController::class, 'dashboardData'])->name('finance.dashboard.data');
    Route::get('/finance/dashboard/export', [App\Http\Controllers\FinanceController::class, 'exportDashboard'])->name('finance.dashboard.export');

    // Reports Routes
    Route::get('/reports/financial', [App\Http\Controllers\ReportController::class, 'financial'])->name('reports.financial');
    Route::get('/reports/sales', [App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/expenses', [App\Http\Controllers\ReportController::class, 'expenses'])->name('reports.expenses');
    Route::post('/reports/generate', [App\Http\Controllers\ReportController::class, 'generate'])->name('reports.generate');
    Route::get('/reports/export/{type}', [App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');

});
