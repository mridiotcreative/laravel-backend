<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountVerifyMail;
use App\Models\Role;
use Illuminate\Http\Response;
use App\Traits\HttpResponseTraits;
use DataTables;

class CustomerController extends Controller
{
    use HttpResponseTraits;

    // Customer List
    // public function index(Request $request)
    // {
    //     $customers = Customer::with('roles')->orderBy('id', 'DESC')->paginate(config('constants.PER_PAGE'));
    //     return view('backend.customer.index')->with(['customers' => $customers]);
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            
            $data = Customer::with('roles')->orderBy('id', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('select_orders', static function ($data) {
                    return '<input type="checkbox" name="rowID[]" class="rowID" value="'.$data->id.'"/>';
                })
                ->addColumn('role_name', function($data){
                    $role = $data->roles->first();
                    return !empty($role) ? $role->name : '';
                })->addColumn('documentData', function($data){

                    $role = $data->roles->first();

                    $documentData = '';
                    if($role->slug != 'mr') {
                        $documentData .= '<a class="btn btn-success btn-sm float-left mr-1"
                        href="'.$data->getGstDocument().'" target="_blank"
                        data-toggle="tooltip" title="GST Document"><i
                            class="fas fa-file-pdf"></i></a>
                        <a class="btn btn-success btn-sm float-left mr-1"
                            href="'.$data->getDrugDocument().'" target="_blank"
                            data-toggle="tooltip" title="Grug Document"><i
                                class="fas fa-file-pdf"></i></a>';
                    }
                    $documentData .= '<a class="btn btn-success btn-sm float-left mr-1"
                        href="'.$data->getIdProofDocument().'" target="_blank"
                        data-toggle="tooltip" title="Id Proof Document"><i
                            class="fas fa-file-pdf"></i></a>';

                    return $documentData;
                })->addColumn('info_status', function($data){
                    $checked = $data->getRawOriginal('status') == \App\Helpers\AppHelper::ACTIVE['status_code'] ? 'checked' : '';
                    $info_status = '<div class="custom-control custom-switch">
                        <input class="custom-control-input customerIsActive" type="checkbox"
                            data-id="'.$data->id.'" id="customSwitch-'.$data->id.'"
                            '.$checked.'>
                        <label class="custom-control-label" for="customSwitch-'.$data->id.'"></label>
                    </div>';
                    
                    return $info_status;
                })->addColumn('action', function($data){
                    $actionData = '<a href="'.route('customer.show', $data->id).'" class="btn btn-warning btn-sm float-left mr-1"
                    style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                    title="view" data-placement="bottom"><i class="fas fa-eye"></i></a>';

                    if($data->is_verified != \App\Helpers\AppHelper::ACTIVE['status_code']){

                        $actionData .= '<a href="'.route('customer.active', [$data->id]).'"
                            class="btn btn-success btn-sm float-left mr-1"
                            style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                            title="Active" data-placement="bottom"><i
                                class="fas fa-check-circle"></i></a>
                        <button data-id="'.$data->id.'" data-toggle="modal"
                            data-target="#accountDeclined"
                            class="btn btn-danger btn-sm float-left mr-1 btnAccountDeclined"
                            style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                            title="Declined" data-placement="bottom"><i
                                class="fas fa-times"></i></button>';
                    }

                    $actionData .= '<a href="'.route('customer.create', $data->id).'"
                        class="btn btn-primary btn-sm float-left mr-1"
                        style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                        title="edit" data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="'.route('customer.destroy', [$data->id]).'">
                    <input type="hidden" name="_method" value="delete" />
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button class="btn btn-danger btn-sm dltBtn" data-id='.$data->id.'
                            style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                            data-placement="bottom" title="Delete"><i
                                class="fas fa-trash-alt"></i></button>
                    </form>';

                    return $actionData;
                })
                ->rawColumns(['select_orders','role_name','documentData','info_status','action'])
                ->make(true);
        }
        return view('backend.customer.index');
    }

    // Customer Create Or Edit From
    public function createOrEdit(Request $request, $id = null)
    {
        $customer = $id ? Customer::with('roles')->findOrFail($id) : null;
        $roles = AppHelper::getRoles();
        $state = AppHelper::getState();
        $city = $id ? AppHelper::getCityByState($customer->state_id) : null;
        return view('backend.customer.commonCustomerPage')->with([
            'customer' => $customer,
            'roles' => $roles,
            'state' => $state,
            'city' => $city
        ]);
    }

    // Customer Store Or Update
    public function storeOrUpdate(Request $request, $id = null)
    {
        $this->validate($request, Customer::getRules($request, $id), Customer::RULES_MSG);
        $status = (new Customer)->createCustomer($request, $id);
        if ($status) {
            $msg = $id ? 'Customer successfully updated.' : 'Customer successfully created.';
            request()->session()->flash('success', $msg);
            return redirect()->route('customer.index');
        } else {
            request()->session()->flash('error', 'Please try again!');
            return back();
        }
    }


    // Make Cusotmer Account Status Active
    public function makeAccountActive(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->is_verified = AppHelper::ACTIVE['status_code'];
        $customer->status = AppHelper::ACTIVE['status_code'];
        if ($customer->save()) {
            // Send Mail
            Mail::to($customer->email)->send(new AccountVerifyMail($customer->getRawOriginal('status')));
            request()->session()->flash('success', 'Customer Account Activeted.');
        } else {
            request()->session()->flash('error', 'Please try again!!');
        }
        return redirect()->route('customer.index');
    }

    public function makeAccountDeclined(Request $request)
    {
        $id = $request->input('customer_id');
        $text = $request->input('resion');
        $customer = Customer::findOrFail($id);
        $customer->status = AppHelper::DECLINED['status_code'];
        $customer->resion = $text;
        if ($customer->save()) {
            // Send Mail
            Mail::to($customer->email)->send(new AccountVerifyMail($customer->getRawOriginal('status'), $text));
            request()->session()->flash('success', 'Customer Account Declined.');
        } else {
            request()->session()->flash('error', 'Please try again!!');
        }
        return redirect()->route('customer.index');
    }

    public function changeStatus(Request $request)
    {
        $id = $request->input('customer_id');
        $status = $request->input('status');
        $customer = Customer::find($id);
        if (empty($customer)) {
            return $this->failure('Record not found!');
        }
        $customer->status = ($status) ? AppHelper::ACTIVE['status_code'] : AppHelper::INACTIVE['status_code'];
        if ($customer->save()) {
            return $this->success('Customer staus successfully updated.');
        }
        return $this->failure('Please try again!!');
    }

    // Delete Customer
    public function destroy(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        request()->session()->flash('success', 'Customer delete successfully.');
        return redirect()->route('customer.index');
    }

    public function customerShow($id)
    {

        $customer = $id ? Customer::with(['roles','state','city'])->findOrFail($id) : null;
        $roles = AppHelper::getRoles();
        //dd($customer);
        return view('backend.customer.show')->with([
            'customer' => $customer,
            'roles' => $roles,
        ]);
    }

    public function deleteMultipleRecord(Request $request)
    {
        $status = Customer::whereIn('id', $request->ids)->delete();
        if ($status > 0) {
            return $this->success('Data successfully deleted');
        } else {
            return $this->failure('Error while deleting Data');
        }
        return $this->failure('Please try again!!');
    }
}
