<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function initiatePayment(Request $request)
    {
        try {
            $client = new Client();
            $tx_ref = 'tx_' . time();
            $secret_key = env('CHAPA_SECRET_KEY'); // Ensure this is set in .env

            $response = $client->post('https://api.chapa.co/v1/transaction/initialize', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $secret_key,
                    'Content-Type' => 'application/json'
                ],
                'json' => [
                    'amount' => $request->input('amount'),
                    'currency' => 'ETB',
                    'email' => $request->input('email'),
                    'tx_ref' => $tx_ref,
                    'callback_url' => route('payment.callback'),
                    'first_name' => $request->input('first_name'),
                    'last_name' => $request->input('last_name'),
                    'customization' => [
                        'title' => 'Product Purchase',
                        'description' => 'Buying Product XYZ',
                    ],
                ]
            ]);

            $responseBody = json_decode($response->getBody(), true);

            if ($responseBody['status'] === 'success') {
                return redirect($responseBody['data']['checkout_url']);
            } else {
                return back()->withErrors('Payment initialization failed. Please try again.');
            }
        } catch (\Exception $e) {
            Log::error('Chapa Payment Error: ' . $e->getMessage());
            return back()->withErrors('An error occurred. Please try again later.');
        }
    }

    public function paymentCallback(Request $request)
    {
        try {
            $tx_ref = $request->query('tx_ref');
            $client = new Client();
            $secret_key = env('CHAPA_SECRET_KEY');

            $response = $client->get("https://api.chapa.co/v1/transaction/verify/{$tx_ref}", [
                'headers' => [
                    'Authorization' => 'Bearer ' . $secret_key,
                ],
            ]);

            $responseBody = json_decode($response->getBody(), true);

            if ($responseBody['status'] === 'success') {
                // Payment successful
                return response()->json(['status' => 'success', 'message' => 'Payment successful']);
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Payment verification failed']);
            }
        } catch (\Exception $e) {
            Log::error('Chapa Payment Verification Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'An error occurred.']);
        }
    }
}
