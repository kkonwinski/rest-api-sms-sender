<?php

namespace App\Service;


use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SmsSenderService
{

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * use Symfony httpCLient to connect with api and send sms by method Post on url https://api.smsapi.pl/sms.do and authorization baerer token
     * @param $userId
     * @throws TransportExceptionInterface
     */
    #[NoReturn] public function sendSms($userId)
    {

        $sms_token = $this->getEnv('SMS_TOKEN');
        $sms_url = $this->getEnv('SMS_URL');
        $sms_to = $this->getEnv('SMS_TO');



        //create connection with sms api with params
        $response = $this->client->request('POST', $sms_url, [
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $sms_token),
                'Content-Type' => 'application/json',
            ],
            'query' => [
                'to' => $sms_to,
                'from' => '',
                'message' => 'Hello, user with id ' . $userId . ' has been deleted.',
                'format' => 'json',
                'test' => '1',
            ],
        ]);
        $result = new JsonResponse("User with id " . $userId . " has been deleted.", $response->getStatusCode(), [], true);
        die($result);


    }

    /**
     * @param string $name
     * @return array|string
     */
    public function getEnv(string $name): array|string
    {

        $value = $_ENV[$name];

        if ($value === false) {
            throw new \RuntimeException("Environment variable $name is not defined.");
        }
        return $value;
    }


}