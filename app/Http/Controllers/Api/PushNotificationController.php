<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PushNotification;
use Illuminate\Http\Request;
use App\Traits\HttpResponseTraits;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Lang;

class PushNotificationController extends Controller
{
    use HttpResponseTraits;

    private $model = null;

    public function __construct()
    {
        $this->model = new PushNotification;
    }

    /**
     * Get Push Notification List
     *
     * @param Request $request
     * @return json
     */
    public function getPushNotificationList(Request $request)
    {
        return $this->model->getPushNotificationList($request);
    }

    public function sendTestPush(Request $request)
    {
        return $this->model->sendPush($request->all());
    }
}
