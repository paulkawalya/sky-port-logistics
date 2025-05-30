<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ModelComponent;
use App\Models\Permission;

class AddFinanceDashboardAndReportsPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create Finance Dashboard component
        $financeComponent = ModelComponent::saveModelComponent('Finance Dashboard');
        
        // Add permissions for Finance Dashboard
        Permission::savePermission($financeComponent->id, 'view_finance_dashboard', 'View Finance Dashboard');
        Permission::savePermission($financeComponent->id, 'export_finance_reports', 'Export Finance Reports');
        
        // Create Reports component
        $reportsComponent = ModelComponent::saveModelComponent('Reports');
        
        // Add permissions for Reports
        Permission::savePermission($reportsComponent->id, 'view_reports', 'View Reports');
        Permission::savePermission($reportsComponent->id, 'generate_reports', 'Generate Reports');
        Permission::savePermission($reportsComponent->id, 'export_reports', 'Export Reports');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Find the components
        $financeComponent = ModelComponent::where('name', 'Finance Dashboard')->first();
        $reportsComponent = ModelComponent::where('name', 'Reports')->first();
        
        if ($financeComponent) {
            // Delete all permissions associated with Finance Dashboard
            Permission::where('model_component_id', $financeComponent->id)->delete();
            // Delete the component
            $financeComponent->delete();
        }
        
        if ($reportsComponent) {
            // Delete all permissions associated with Reports
            Permission::where('model_component_id', $reportsComponent->id)->delete();
            // Delete the component
            $reportsComponent->delete();
        }
    }
}
