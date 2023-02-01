<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use DataTables;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     $users = User::orderBy('id', 'ASC')->paginate(config('constants.PER_PAGE'));
    //     return view('backend.users.index')->with('users', $users);
    // }

    public function index(Request $request)
    {
        if ($request->ajax()) {


            $data = User::orderBy('id', 'ASC')->whereNotIn('role', ['admin'])->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('uimage', function($data){
                    return '<img src="'.$data->getImage().'" class="img-fluid rounded-circle"
                    style="max-width:50px" alt="'.$data->name.'">';
                })->addColumn('createdate', function($data){
                    return $data->created_at ? $data->created_at->diffForHumans() : '';
                })
                ->addColumn('info_status', function($data){
                    if ($data->status == 'active'){
                        return '<span class="badge badge-success">'.$data->status.'</span>';
                    }else{
                        return '<span class="badge badge-warning">'.$data->status.'</span>';
                    }
                })->addColumn('action', function($data){
                    $actionData = '<a href="'.route('users.create', $data->id).'"
                    class="btn btn-primary btn-sm float-left mr-1"
                    style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip" title="edit"
                    data-placement="bottom"><i class="fas fa-edit"></i></a>
                    <form method="POST" action="'.route('users.destroy', [$data->id]).'">
                    <input type="hidden" name="_method" value="delete" />
                    <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button class="btn btn-danger btn-sm dltBtn" data-id='.$data->id.'
                            style="height:30px; width:30px;border-radius:50%" data-toggle="tooltip"
                            data-placement="bottom" title="Delete"><i class="fas fa-trash-alt"></i></button>
                    </form>';

                    return $actionData;
                })
                ->rawColumns(['uimage','createdate','info_status','action'])
                ->make(true);
        }
        return view('backend.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createOrEdit($id = null)
    {
        $user = $id ? User::findOrFail($id) : null;
        return view('backend.users.userCommonPage')->with('user', $user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeOrUpdate(Request $request, $id = null)
    {
        $fileCheck = null;
        if (!empty($request->previous_image)) {
            $fileCheck += 1;
        }

        if (!empty($request->filter_image)) {
            $fileCheck += 1;
        }

        $user = $id ? User::findOrFail($id) : new User;
        $validPhoto = $fileCheck ? 'image|sometimes' : 'image|required';
        $validPassword = $id ? 'string|sometimes' : 'string|required';
        $this->validate(
            $request,
            [
                'name' => 'string|required|max:30',
                'email' => 'string|required|unique:users,email,' . $id,
                'password' => $validPassword,
                'role' => 'required|in:admin,user',
                'status' => 'required|in:active,inactive',
                'photo' => $validPhoto,
            ]
        );
        $data = $request->all();
        if ($id == '') {
            $data['password'] = Hash::make($request->password);
        }

        $filter_image_data = '';
        if (!empty($request->filter_image)) {
            $image = $request->filter_image;
            $explode_value = explode(",", $image);
            $filter_image_data = $explode_value[1];
        }

        $remove_deleted_value_from_image_array = '';
        if (!empty($request->photo)) {
            $image_all = $request->photo;
            $image1 = base64_encode(file_get_contents($image_all));
            if ($image1 == $filter_image_data) {
                $remove_deleted_value_from_image_array = $image_all;
            }
        }

        $image_name = '';

        if ($remove_deleted_value_from_image_array != '') {
            $imagefile = $remove_deleted_value_from_image_array;
            $now = date('ymds') . '-';
            $imageName = $now . AppHelper::replaceSpaceIntoDash($imagefile->getClientOriginalName());
            $imagefile->storeAs(config('path.user'), $imageName);
            $image_name = $imageName;
        }
        if (!empty($request->previous_image)) {
            $image_name = $request->previous_image;
        }
        $data['photo'] = $image_name;

        // Upload Image
        // if ($request->hasFile('photo')) {
        //     $now = date('ymds') . '-';
        //     $photo = $request->file('photo');
        //     $photoName = $now . AppHelper::replaceSpaceIntoDash($photo->getClientOriginalName());
        //     $request->photo->storeAs(config('path.user'), $photoName);
        //     $data['photo'] = $photoName;
        // }
        $status = $user->fill($data)->save();
        if ($status) {
            $msg = $id ? 'Successfully updated' : 'Successfully added user';
            request()->session()->flash('success', 'Successfully added user');
        } else {
            request()->session()->flash('error', 'Error occurred while adding user');
        }
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = User::findorFail($id);
        $status = $delete->delete();
        if ($status) {
            request()->session()->flash('success', 'User Successfully deleted');
        } else {
            request()->session()->flash('error', 'There is an error while deleting users');
        }
        return redirect()->route('users.index');
    }
}
