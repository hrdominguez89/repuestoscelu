<?php
namespace App\Service;

use Aws\Sns\SnsClient;

class AwsSnsClient
{
    private $snsClient;

    public function __construct()
    {
        $this->snsClient = new SnsClient([
            'version' => '2010-03-31',
            'region' => $_ENV['AWS_SNS_REGION'],
            'credentials' => [
                'key' => $_ENV['AWS_SNS_ID'],
                'secret' => $_ENV['AWS_SNS_KEY'],
            ],
        ]);
    }

    public function sendSMS(string $message, string $phoneNumber)
    {
        $result = $this->snsClient->publish([
            'Message' => $message,
            'PhoneNumber' => $phoneNumber,
        ]);

        return $result;
    }
}