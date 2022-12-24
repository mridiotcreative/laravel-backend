<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class ReviewController extends ApiController
{
    use HttpResponseTraits;
    // store product review
    public function store(Request $request){
        
        // validation
        if ($this->apiValidation($request, ProductReview::RULES)) {
            return $this->errors(Lang::get('messages.validation_error'), $this->errorsMessages);
        }

        $dataArray = [
            'product_id' => $request->product_id,
            'customer_id' => $request->user()->id,
            'rate' => $request->rate,
            'review' => $request->review,
        ];

        $review_result = ProductReview::create($dataArray);

        if($review_result){
            return $this->success(Lang::get('messages.product_review_add'));
        }
        return $this->failure(Lang::get('messages.product_review_add_failed'), Response::HTTP_CONFLICT);

    }

    // delete product review
    public function destroy(Request $request)
    {
        $review = ProductReview::query();
        if($request->has('review_id')){
            $review->where('id',$request->review_id);
        }
       
        if(!$review->first()){
            return $this->failure(Lang::get('messages.product_review_empty'), Response::HTTP_NOT_FOUND);
        }

        if ($review->delete()) {
            return $this->success(Lang::get('messages.product_review_remove'));
        }
        return $this->failure(Lang::get('messages.product_review_remove_failed'), Response::HTTP_CONFLICT);
    }
}
