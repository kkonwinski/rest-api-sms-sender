<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SmsSenderService
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }



    // use Symfony httpCLient to connect with api and send sms by method Post on url https://api.smsapi.pl/sms.do and authorization baerer token
    public function sendSms($userId)
    {
        $sms_token = getenv('SMS_TOKEN');
        $sms_url = getenv('SMS_URL');
        $sms_from = getenv('SMS_FROM');
        $sms_to = getenv('SMS_TO');
dd($sms_token);
        $response = $this->client->request('POST', $sms_url, [
            'headers' => [
                'Authorization' => 'Bearer '.$sms_token,
                'Content-Type' => 'application/json',
            ],
            'body' => [
                'to' => $sms_to,
                'from' => $sms_from,
                'message' => 'Hello, user with id ' . $userId . ' has been deleted.',
            ],
        ]);
dd($response);

    }






}