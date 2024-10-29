<?php

namespace App\Http\Controllers; // Ensure this matches your file structure

use GuzzleHttp\Client;
use Illuminate\Http\Request; // Import the Request class

class PaymentController extends Controller
{
    public function initiatePayment(Request $request) // Use the imported Request class
    {
        $client = new Client();

        $tx_ref = 'tx_' . time(); // Generate unique transaction reference
        $secret_key = env('CHASECK_TEST-nQpoUmIUPqVd5FlxXZCRvM7ZQnzMAxL7'); // Use your Chapa secret key

        $response = $client->post('https://api.chapa.co/v1/transaction/initialize', [
            'headers' => [
                'Authorization' => 'Bearer ' . $secret_key,
            ],
            'json' => [
                'amount' => $request->input('amount'),
                'currency' => 'ETB',
                'email' => $request->input('email'),
                'tx_ref' => $tx_ref,
                'callback_url' => route('payment.callback'), // Redirect URL after payment
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
            return redirect($responseBody['data']['checkout_url']); // Redirect to Chapa payment page
        } else {
            return back()->withErrors('Payment initialization failed, please try again.');
        }
    }

    public function paymentCallback(Request $request) // Use the imported Request class
    {
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
            // Payment failed
            return response()->json(['status' => 'failed', 'message' => 'Payment verification failed']);
        }
    }
}
