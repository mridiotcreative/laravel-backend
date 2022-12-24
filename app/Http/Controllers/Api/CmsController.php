<?php

namespace App\Http\Controllers\Api;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsDetails;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use App\Traits\HttpResponseTraits;

class CmsController extends ApiController
{
    use HttpResponseTraits;

    // cms page listing
    public function index(Request $request){

        $cmsDetail = CmsDetails::select('id','title','slug','description','status')
            ->get();

        if(count($cmsDetail) > 0){
            $data['cmsDetail_data'] = $cmsDetail;
            return $this->success(Lang::get('messages.cms_page_details'), $data);
        }else{   
            return $this->failure(Lang::get('messages.no_cms_page_found'), Response::HTTP_NOT_FOUND);  
        }

    }
}
