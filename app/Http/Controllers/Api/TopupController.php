<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Topup;

class TopupController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:10000'
        ]);

        $order_id = 'TRX-' . Str::uuid()->toString();
        $payload = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $request->amount,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
            ],
        ];

        $snap_token = \Midtrans\Snap::getSnapToken($payload);

        $topup = Topup::create([
            'user_id' => auth()->id(),
            'order_id' => $order_id,
            'amount' => $request->amount,
            'status' => 'pending',
            'snap_token' => $snap_token,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Menunggu pembayaran',
            'status' => $topup->status,
            'order_id' => $order_id,
            'snap_token' => $snap_token,
        ]);
    }

    public function webhook(Request $request)
    {
        $hash = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . env('MIDTRANS_SERVER_KEY'));

        if ($hash !== $request->signature_key) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $topup = Topup::where('order_id', $request->order_id)->first();

        if (!$topup) {
        return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
    }

        if ($request->transaction_status == 'settlement' || $request->transaction_status == 'capture'){
            if ($topup->status !== 'success') {
                $user = $topup->user;

                $user->update(['balance' => $user->balance + $topup->amount]);

                $topup->update(['status' => 'success']);
            }
        } else if ($request->transaction_status == 'deny' || $request->transaction_status == 'expire' || $request->transaction_status == 'cancel'){
            $topup->update(['status' => 'failed']);
        }

        return response()->json(['success' => true]);
    }
}
