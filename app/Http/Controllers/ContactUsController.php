<?php

namespace App\Http\Controllers;

use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use DataTables;

class ContactUsController extends Controller
{
    private $model = null;

    public function __construct()
    {
        $this->model = new ContactUs;
    }

    // Admin Side Contact Listing Page
    // public function index(Request $request)
    // {
    //     $contactUS = ContactUs::orderByDesc('created_at')->paginate(config('constants.PER_PAGE'));
    //     return view('backend.contact.index')->with(['contactUS' => $contactUS]);
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            
            $data = ContactUs::orderBy('created_at', 'DESC');
            return Datatables::of($data)
                ->addIndexColumn()->addColumn('action', function($data){
                    $actionData = '<a href="'.route('contact-us.show', $data->id).'"
                    class="btn btn-warning btn-sm float-left mr-1"
                    style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                    title="view" data-placement="bottom"><i class="fas fa-eye"></i></a>';

                    return $actionData;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.contact.index');
    }

    // Admin Side Contact View Page
    public function show(Request $request, $id)
    {
        $contactUS = ContactUs::findOrFail($id);
        return view('backend.contact.view')->with(['contactUS' => $contactUS]);
    }

    // Contact-us Form
    public function contact()
    {
        return view('frontend.pages.contact');
    }

    // Submit Contact-us Form
    public function submitContactUs(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required'
        ]);
        if ($this->model->addContactUs($request)) {
            return response()->json(Lang::get('messages.contact_submitted'), Response::HTTP_OK);
        }
        return response()->json(Lang::get('messages.something_went_wrong'), Response::HTTP_BAD_REQUEST);
    }
}
