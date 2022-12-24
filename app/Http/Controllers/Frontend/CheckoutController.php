<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\AppHelper;
use App\Http\Controllers\Controller;
use App\Mail\OrderPlacedMail;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Razorpay\Api\Api as Razorpay;
use Razorpay\Api\Errors\SignatureVerificationError;
use Auth;
use Helper;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    /**
     * Customer checkout view
     *
     * @param Request $request
     * @return view
     */
    public function checkout(Request $request)
    {
        if (Helper::cartCount() < 1) {
            request()->session()->flash('error', 'Cart is empty.');
            return redirect()->route('home');
        }
        $customer = AppHelper::getUserByGuard();
        $digits = config('constants.ORD_DIGIT');
        $orderNo = 'ORD' . date('ymdis') . str_pad(rand(0, pow(10, $digits) - 1), $digits, '0', STR_PAD_LEFT);
        // Manage Log File
        \Log::channel('order_log')->info(sprintf(
            "\n===========\nOrderNo:- %s\nCustomerId:- %s\nNote:- %s\n",
            $orderNo,
            $customer->id,
            "Checkout"
        ));

        return view('frontend.pages.checkout')->with([
            'customer' => $customer,
            'orderNo' => $orderNo,
        ]);
    }

    /**
     * Create Razorpay Order using by Razorpay Package
     *
     * @param Request $request
     * @return json
     */
    public function razorpayOrder(Request $request)
    {
        try {
            $cart = new Cart;
            $result = $cart->getPaymentDetails();
            $total_amt = floatval(preg_replace('/[^\d.]/', '', $result['total_amount']));
            $amount = ($total_amt * 100);
            $msg = "Something Wrong.";
            $razorpay = new Razorpay(env('RZP_KEY'), env('RZP_SECRET'));
            $orderData = [
                'receipt'         => $request->orderNo,
                'amount'          => intval($amount),
                'currency'        => config('constants.CURRENCY')
            ];
            $razorpayOrder = $razorpay->order->create($orderData);
            // Manage Log File
            \Log::channel('order_log')->info(sprintf(
                "\n===========\nOrderData:- %s\nResponse:- %s\nNote:- %s\n",
                json_encode($orderData),
                json_encode($razorpayOrder->toArray()),
                "Razorpay Order Creating"
            ));
            if (!empty($razorpayOrder->id)) {
                return response()->json([
                    'rzp_order_id' => $razorpayOrder->id,
                ], Response::HTTP_OK);
            }
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            // Manage Log File
            \Log::channel('order_log')->info(sprintf(
                "\n===========\nOrderData:- %s\nError:- %s\nNote:- %s\n",
                json_encode($request->all()),
                $msg,
                "Razorpay Order Creating Exception"
            ));
        }
        return response()->json($msg, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Verify payment using by Razorpay Package
     *
     * @param Request $request
     * @return redirect
     */
    public function checkoutSubmit(Request $request)
    {
        try {
            // Manage Log File
            \Log::channel('order_log')->info(sprintf(
                "\n===========\nOrderData:- %s\nNote:- %s\n",
                json_encode($request->all()),
                "Order Creating Request"
            ));
            $user = Auth::guard('customer')->user();
            $customerId = $user->id;
            if ($request->payment_method != "cod") {
                $razorpay = new Razorpay(env('RZP_KEY'), env('RZP_SECRET'));
                $razorpayResponse = json_decode($request->razorpay_response, true);
                $razorpay->utility->verifyPaymentSignature($razorpayResponse);
                $data['payment_method'] = 'razorpay';
                $data['payment_status'] = 'paid';
                $data['payment_id'] = $razorpayResponse['razorpay_payment_id'];
                $data['payment_response_log'] = $request->razorpay_response;
            }
            $data['order_number'] = $request->order_number;
            $data['customer_id'] = $customerId;
            // Amount
            $subTotalAmount = Helper::totalCartPrice();
            $points = (session()->has('points')) ? session('points') : 0;
            $data['points'] = $points;
            $data['sub_total_amount'] = $subTotalAmount;
            $data['total_amount'] = ($subTotalAmount - $points);
            // Save Order
            $order = new Order;
            $order->fill($data);
            if ($order->save()) {
                // Insert Order Id In Cart
                Cart::where('customer_id', $customerId)
                    ->whereNull('order_id')
                    ->update(['order_id' => $order->id]);
                // Deduct Points From Customer
                if (session()->has('points')) {
                    $newPoints = $user->total_points - $points;
                    Customer::where('id', $customerId)->update(['total_points' => $newPoints]);
                    session()->forget('points');
                }
                Mail::to($user->email)->send(new OrderPlacedMail($order->toArray()));
                request()->session()->flash('success', 'Order has been successfully placed.');
            } else {
                request()->session()->flash('success', 'Something wrong.');
            }
        } catch (SignatureVerificationError $e) {
            $error = 'Razorpay Error : ' . $e->getMessage();
            // Manage Log File
            \Log::channel('order_log')->info(sprintf(
                "\n===========\nOrderData:- %s\nError:- %s\n",
                json_encode($request->all()),
                $error,
                "Order Creating Exception"
            ));
            request()->session()->flash('error', $error);
        } catch (\Exception $e) {
            // Manage Log File
            \Log::channel('order_log')->info(sprintf(
                "\n===========\nOrderData:- %s\nError:- %s\n",
                json_encode($request->all()),
                $e->getMessage(),
                "Order Creating Exception"
            ));
            request()->session()->flash('error', $e->getMessage());
        }
        return redirect()->route('home');
    }
}
