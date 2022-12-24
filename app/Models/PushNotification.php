<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;
use App\Traits\HttpResponseTraits;

class PushNotification extends Model
{
    use HttpResponseTraits;

    protected $fillable = [
        'title',
        'body',
        'to',
        'type',
        'table_rec_id',
        'is_single',
        'customer_id',
        'is_sent',
    ];
    public function getPushNotificationList($request)
    {
        $customer = request()->user();
        $notificationList = PushNotification::where(function ($query) use ($customer) {
            $query->where('customer_id', $customer->id);
            $query->orWhere('is_single', 0);
        })
            ->where('is_sent', 1)
            ->orderByDesc('id')
            ->get();

        // Return Not Found
        if ($notificationList->isEmpty()) {
            return $this->failure(Lang::get('messages.not_found'), Response::HTTP_NOT_FOUND);
        }
        Customer::where('id', $customer->id)->update(['is_new_push' => 0]);
        $newList = [];
        // Making Custom Response
        foreach ($notificationList as $items) {
            $data = [];
            $date = \Carbon\Carbon::parse($items->created_at)->format('d-M-Y');
            $data['type'] = $items->type;
            $data['title'] = $items->title;
            $data['body'] = $items->body;
            if ($items->type == config('constants.PUSH_TYPE.NEW_ARTICLE')) {
                $data['slug'] = $items->slug;
            } elseif ($items->type == config('constants.PUSH_TYPE.NEW_PRODUCT')) {
                $data['product_id'] = $items->table_rec_id;
                $data['image'] = $items->image;
            }
            $newList[$date]['date'] = $date;
            $newList[$date]['items'][] = $data;
        }
        $response['notification_list'] = array_values($newList);
        return $this->success(Lang::get('messages.success'), $response);
    }

    // Make Push Notification
    public function makePushNotification($data)
    {
        if (!empty($data)) {
            // Set Push Data
            $push = new PushNotification;
            $push->title = $data['title'];
            $push->body = $data['body'];
            $push->to = !empty($data['to']) ? $data['to'] : "";
            $push->type = $data['type'];
            $push->table_rec_id = !empty($data['table_rec_id']) ? $data['table_rec_id'] : 0;
            $push->customer_id = !empty($data['customer_id']) ? $data['customer_id'] : 0;
            $push->is_single = $data['is_single'];
            $push->is_sent = 0;
            // Save Push Data
            if ($push->save()) {
                // Make Payload For Send Push
                $pushData['notification'] = [
                    'title' => $push->title,
                    'body' => $push->body,
                ];
                // Check Single Push or All
                $tokens = $push->to;
                if ($push->is_single != 1) {
                    $statusCode = \App\Helpers\AppHelper::ACTIVE['status_code'];
                    // Get All User FCM Token
                    $tokens = Customer::where('status', $statusCode)
                        ->whereNotNull('fcm_token')
                        ->pluck('fcm_token');
                    // All FCM Token Payload
                    if (!$tokens->isEmpty()) {
                        $tokens = $tokens->toArray();
                    }
                }
                $pushData['registration_ids'] = $tokens;
                // Send Push
                $response = $this->sendPush($pushData);
            }
            // Check Push Response
            if (!empty($response['success']) && $response['success'] > 0) {
                // Make Push Sent
                $push->is_sent = 1;
                if ($push->is_single != 1) {
                    Customer::where('status', $statusCode)
                        ->whereNotNull('fcm_token')
                        ->update(['is_new_push' => 1]);
                } else {
                    Customer::where('id', $push->customer_id)->update(['is_new_push' => 1]);
                }
            }
            // Push Response Log
            $push->fcm_response = stripcslashes(json_encode($response));
            // Update Push Is Sent
            $push->save();
            return $response;
        }
    }

    // Send Push Notification
    public static function sendPush($data = [])
    {
        $token = env('FCM_AUTH_TOKEN');
        $headers  = [
            'Content-Type: application/json',
            "Authorization: key=$token"
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, config('path.FCM_URL'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response     = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($statusCode == 200) {
            $response = @json_decode($response, true);
            return $response;
        }
        return [];
    }
}
