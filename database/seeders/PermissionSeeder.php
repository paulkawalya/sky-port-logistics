<?php
namespace Database\Seeders;

use App\Models\ModelComponent;
use App\Models\Permission;
use Illuminate\Database\Seeder;
 

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $permissions = [

            'User' => [
               ['name' => 'view_users','display_name'=>'Can View users'],
               ['name' => 'create_users','display_name'=>'Can Create user'],
               ['name' => 'edit_user','display_name'=>'Can Edit user'], 
               ['name' => 'revoke_permissions','display_name'=>'Can Revoke all user permissions'], 
               ['name' => 'view_dasbord','display_name'=>'Can view landing dashboard'],   
            ],

            'Customers' => [
                ['name' => 'view_customers','display_name'=>'Can View customers'],
                ['name' => 'create_customers','display_name'=>'Can Create customers'],
                ['name' => 'edit_customers','display_name'=>'Can Edit customers'], 
                ['name' => 'view_customers_shipments','display_name'=>'Can View customers shipments'],          
              ],

              'Agent' => [
                ['name' => 'view_agent','display_name'=>'Can View agent'],
                ['name' => 'create_agent','display_name'=>'Can Create agent'],
                ['name' => 'edit_agent','display_name'=>'Can Edit agent'], 
                ['name' => 'view_agent_shipments','display_name'=>'Can View agent shipments'],          
              ],

              'Container' => [
                ['name' => 'view_containers','display_name'=>'Can View containers'],
                ['name' => 'create_containers','display_name'=>'Can Create containers'],
                ['name' => 'edit_containers','display_name'=>'Can Edit containers'], 
                ['name' => 'view_container_documents','display_name'=>'Can View container documents'],
                ['name' => 'add_container_documents','display_name'=>'Can add container documents'],
                ['name' => 'view_container_updates','display_name'=>'Can View container updates'],
                ['name' => 'delete_container','display_name'=>'Can delete container'],
              ],

            'Shipment' => [
                ['name' => 'view_shipment','display_name'=>'Can View shipment'],
                ['name' => 'create_shipment','display_name'=>'Can Create shipment'],
                ['name' => 'edit_shipment','display_name'=>'Can Edit shipment'],
                ['name' => 'add_shipment_documents','display_name'=>'Can Add shipment documents'],
                ['name' => 'view_shipment_documents','display_name'=>'Can View shipment documents'],
                ['name' => 'add_shipment_transfers','display_name'=>'Can Add shipment transfers'],
                ['name' => 'view_shipment_transfers','display_name'=>'Can View shipment transfers'],
                ['name' => 'add_shipment_invoice','display_name'=>'Can Add shipment Invoice'],
                ['name' => 'add_payment_shipment_invoice','display_name'=>'Can record payment to Invoice'],
                ['name' => 'view_shipment_invoice','display_name'=>'Can View shipment invoice'],
                ['name' => 'edit_shipment_invoice','display_name'=>'Can Edit shipment invoice'],
                ['name' => 'delete_shipment_invoice','display_name'=>'Can Delete shipment invoice'],            
              ],            

              'Requisitions' => [             
                ['name' => 'add_requisition','display_name'=>'Can Add Requisitions'],
                ['name' => 'view_requisition','display_name'=>'Can View Requisitions'],
                ['name' => 'approve_requisition','display_name'=>'Can approve Requisitions'],
                ['name' => 'convert_requisition_to_expense','display_name'=>'Can convert Requisitions to Expense'],
                ['name' => 'can_view_expenses','display_name'=>'Can view Expenses'],
                ['name' => 'add_documents_to_requisition','display_name'=>'Can add documents to requisition'],
                ['name' => 'add_view_requisition_documents','display_name'=>'Can view requisition documenst'],
                ['name' => 'manage_expense_categories','display_name'=>'Can manage expense categories'],
                ['name' => 'add_payment_to_requisition','display_name'=>'Can add payment to requisition'],

              ],
       
            'Quotations' => [
                ['name' => 'view_quotations','display_name'=>'Can View Quotations'],
                ['name' => 'create_quotations','display_name'=>'Can Create Quotations'],
                ['name' => 'edit_quotations','display_name'=>'Can edit Quotations'],
                ['name' => 'delete_quotations','display_name'=>'Can delete Quotations'],
              ],

            'Taxes' => [
                ['name' => 'view_taxes','display_name'=>'Can View Taxes'],
                ['name' => 'create_taxes','display_name'=>'Can Create taxes'],
                ['name' => 'edit_taxes','display_name'=>'Can edit taxes'],
                ['name' => 'delete_taxes','display_name'=>'Can delete taxes'],
              ],

              'Suppliers' => [
                ['name' => 'view_suppliers','display_name'=>'Can View Suppliers'],
                ['name' => 'create_suppliers','display_name'=>'Can Create Suppliers'],
                ['name' => 'edit_suppliers','display_name'=>'Can edit Suppliers'],
              ],              

              'Payment accounts' => [
                ['name' => 'view_payment_accounts','display_name'=>'Can View payment accounts'],
                ['name' => 'view_transaction_records','display_name'=>'View account payment transactions'],
                ['name' => 'record_transaction','display_name'=>'Record account payment transaction'],
                ['name' => 'delete_transaction_record','display_name'=>'Delete  account payment transaction'],
                ['name' => 'create_payment_accounts','display_name'=>'Can Create payment accounts'],
                ['name' => 'edit_payment_accounts','display_name'=>'Can edit payment accounts'],
                ['name' => 'delete_payment_accounts','display_name'=>'Can delete payment accounts'],
              ],
         
        ];

        foreach($permissions as $key => $permission){

            try{

                $model_component = ModelComponent::saveModelComponent($key);

                foreach($permission as $perm){

                    try{

                       Permission::savePermission($model_component->id,$perm['name'],$perm['display_name']);

                    }catch(\Exception $exception){}

                }

            }catch(\Exception $exception){}

        }

        $adminUsers = \App\Models\User::whereIn('email', [
            'sifco.admin@gmail.com',
            'edrinekalungi@sifcargo.com',
            'francislule@sifcargo.com',
        ])->get();

        $allPermissionIds = Permission::pluck('id')->toArray();

        foreach ($adminUsers as $adminUser) {
            $adminUser->permissions()->sync($allPermissionIds);
        }
    }
}
