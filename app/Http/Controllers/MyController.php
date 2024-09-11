<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class MyController extends Controller
{
    public function createIntent(){
        $client = new Client();

        $response = $client->request('POST', 'https://api.paymongo.com/v1/payment_intents', [
        'body' => '{"data":{"attributes":{"amount":2000,"payment_method_allowed":["gcash"],"payment_method_options":{"card":{"request_three_d_secure":"any"}},"currency":"PHP","capture_type":"automatic"}}}',
        'headers' => [
            'accept' => 'application/json',
            'authorization' => 'Basic c2tfdGVzdF9TVFcxc1ZhVkd5QXlLUDVUUGc2UDZ1NWg6',
            'content-type' => 'application/json',
        ],
        ]);
        
        return view('intents', ['json'=>$response->getBody()]);
    }

    // PAYMENT INTENT WORKFLOW
    public function sponsor(){
        return view('sponsor');
    }

    public function createPaymentIntent(Request $request){
        $amount = $request->input('amount');

        $client = new Client();

        $response = $client->request('POST', 'https://api.paymongo.com/v1/payment_intents', [
        'body' => '{"data":{"attributes":{"amount":'. $amount .',"payment_method_allowed":["gcash"],"payment_method_options":{"card":{"request_three_d_secure":"any"}},"currency":"PHP","capture_type":"automatic"}}}',
        'headers' => [
            'accept' => 'application/json',
            'authorization' => 'Basic c2tfdGVzdF9TVFcxc1ZhVkd5QXlLUDVUUGc2UDZ1NWg6',
            'content-type' => 'application/json',
        ],
        ]);

        $data = $response->getBody();
        $oData = json_decode($data);

        return response()->json([
            'success' => true,
            'data' => $oData->data,
        ]);
    }

    public function createPaymentMethod(Request $request){
        $name = $request->input('name');
        $email = $request->input('email');
        $phone = $request->input('phone');

        $client = new Client();

        $response = $client->request('POST', 'https://api.paymongo.com/v1/payment_methods', [
        'body' => '{"data":{"attributes":{"billing":{"name":"'. $name .'","email":"'. $email .'","phone":"'. $phone .'"},"type":"gcash"}}}',
        'headers' => [
            'Content-Type' => 'application/json',
            'accept' => 'application/json',
            'authorization' => 'Basic c2tfdGVzdF9TVFcxc1ZhVkd5QXlLUDVUUGc2UDZ1NWg6',
        ],
        ]);

        $data = $response->getBody();
        $oData = json_decode($data);

        return response()->json([
            'success' => true,
            'data' => $oData->data,
        ]);
    }

    public function attach(Request $request){
        $clientKey = $request->input('clientKey');
        $paymentMethodId = $request->input('paymentMethodId');
        $paymentIntentId = $request->input('paymentIntentId');

        $client = new Client();
        
        $response = $client->request('POST', 'https://api.paymongo.com/v1/payment_intents/'.$paymentIntentId.'/attach', [
            'body' => '{"data":{"attributes":{"payment_method":"'. $paymentMethodId .'","client_key":"'. $clientKey .'","return_url":"https://dashboard.paymongo.com/home"}}}',
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Basic c2tfdGVzdF9TVFcxc1ZhVkd5QXlLUDVUUGc2UDZ1NWg6',
                'content-type' => 'application/json',
            ],
        ]);

        $data = $response->getBody();
        $oData = json_decode($data);

        return response()->json([
            'success' => true,
            'data' => $oData->data,
        ]);
    }


    //CREATE LINK PAYMONGO
    public function link(){
        return view('link');
    }

    //FORM SUBMIT FOR LINK
    public function submitLink(Request $request){

        $amount = $request->input('amount');
        $client = new Client();
        $response = $client->request('POST', 'https://api.paymongo.com/v1/links', [
        'body' => '{"data":{"attributes":{"amount":' . $amount . ',"description":"Test desc"}}}',
        'headers' => [
            'accept' => 'application/json',
            'authorization' => 'Basic c2tfdGVzdF9TVFcxc1ZhVkd5QXlLUDVUUGc2UDZ1NWg6',
            'content-type' => 'application/json',
        ],
        ]);

        $data = $response->getBody();
        $oData = json_decode($data);
        $url = $oData->data->attributes->checkout_url;
        return redirect()->away($url);
        // return view('intents', ['json'=>$data]);
    }
}
