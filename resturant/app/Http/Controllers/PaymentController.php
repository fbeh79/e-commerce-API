<?php

namespace App\Http\Controllers;

use App\Models\Copuon;
use App\Models\Order;
use App\Models\Product;
use App\Models\TransAction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends ApiController
{
    public function checkCoupon(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'code' => 'required|string',
        ]);
        $copuon = Copuon::where('code', $request->code)->where('expires_at', '>', Carbon::now())->first();
        if ($validated->fails()) {
            return $this->ErrorResponse($validated->messages(), 422);
        }
        $order = Order::where('coupon_id', $copuon->id)->where('payment_status', 1)->first();
        if ($order) {
            return $this->ErrorResponse('Coupon already used', 422);
        }
        if ($copuon == null) {
            return $this->ErrorResponse('your copuon not valid', 404);
        }
        return $this->SuccessResponse([
            'percentage' => $copuon->percentage,
        ]);
    }

    public function PaymentSend(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'cart' => 'required|array',
            'cart.*.id' => 'required|exists:products,id',
            'cart.*.qty' => 'required|integer|min:1',
            'address_id' => 'required|integer',
            'coupon_code' => 'nullable|string',
        ]);

        if ($validated->fails()) {
            return $this->ErrorResponse($validated->messages(), 422);
        }

        $totalAmount = 0;
        foreach ($request->cart as $item) {
            $product = Product::find($item['id']);
            if ($product->quantity < $item['qty']) {
                return $this->ErrorResponse('موجودی کافی نیست', 422);
            }
            $totalAmount += $product->is_sale
                ? $product->sale_price * $item['qty']
                : $product->price * $item['qty'];
        }


        $couponAmount = 0;
        $coupon = null;
        if ($request->coupon_code) {
            $coupon = Copuon::where('code', $request->coupon_code)
                ->where('expires_at', '>', now())
                ->first();

         
        }

        $payingAmount = $totalAmount - $couponAmount;


        $order = Order::create([
            'user_id' => auth()->id() ?? 3,
            'address_id' => $request->address_id,
            'coupon_id' => $coupon?->id,
            'status' => 0,
            'total_amount' => $totalAmount,
            'coupon_amount' => $couponAmount,
            'payment_status' => 0,
            'paying_status' => 0,

        ]);

        // ارسال درخواست به زیبال
        $response = Http::withoutVerifying()->post('https://gateway.zibal.ir/v1/request', [
            'merchant' => "zibal", // مقدار واقعی sandbox
            'amount' => $payingAmount * 10,
            'callbackUrl' => route('callback'),
            'orderId' => $order->id,
        ]);

        $result = $response->json();
        Log::info('Zibal Request Response', $result);

        if (isset($result['result']) && $result['result'] == 100) {

            Transaction::create([
                'order_id' => $order->id,
                'user_id' => auth()->id() ?? 3,
                'amount' => $payingAmount,
                'token' => $result['trackId'],
                'track_id' => $result['trackId'],
                'status' => 0,
            ]);

            $order->update(['paying_status' => 1]);

            return redirect("https://sandbox.zibal.ir/start/{$result['trackId']}");
        }

        return $this->ErrorResponse($result['message'] ?? 'خطا در ایجاد تراکنش', 400);
    }

    public function callback(Request $request)
    {
        Log::info('Zibal Callback Received:', $request->all());

        $trackId = $request->input('trackId');
        if (!$trackId) {
            return response()->json(['message' => 'trackId ارسال نشده است.'], 400);
        }

        $transaction = Transaction::where('token', $trackId)->first();
        if (!$transaction) {
            return response()->json(['message' => 'تراکنش یافت نشد.'], 404);
        }

        $order = Order::find($transaction->order_id);
        if (!$order) {
            return response()->json(['message' => 'سفارش یافت نشد.'], 404);
        }


        $response = Http::withoutVerifying()->post('https://gateway.zibal.ir/v1/verify', [
            'merchant' => "zibal_sandbox_merchant",
            'trackId' => $trackId,
        ]);

        $result = $response->json();
        Log::info('Zibal Verify Response', $result);

        if ($result['result'] == 100) {
            $transaction->update([
                'status' => 1,
                'ref_number' => $result['refNumber'] ?? null,
            ]);

            $order->update([
                'payment_status' => 1,
                'status' => 1,
            ]);


            foreach ($order->cart as $item) {
                $product = Product::find($item['id']);
                if ($product) {
                    $product->decrement('quantity', $item['qty']);
                }
            }

            return response()->json([
                'message' => 'پرداخت با موفقیت انجام شد 🎉',
                'ref_number' => $result['refNumber'] ?? null,
            ], 200);
        }


        $transaction->update(['status' => 2]);
        $order->update(['payment_status' => 2]);

        return response()->json([
            'message' => 'پرداخت ناموفق بود.',
            'error' => $result['message'] ?? 'خطای ناشناخته',
        ], 400);
    }
 }
