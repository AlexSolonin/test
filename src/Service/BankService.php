<?php


namespace App\Service;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class BankService
{
    private $params;


    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function sendTransfer(array $transferArray)
    {
        $result = $this->sendPost($transferArray);

        if (isset($result)) {

            return true;
        } else {

            return false;
        }
    }

    public function sendPost(array $params)
    {
        $url = $this->params->get('url');
        $params = json_encode($params);
        $client = new Client([
            'headers' => [ 'Content-Type' => 'application/json' ]
        ]);
        try {
            $res = $client->request(
                'POST',
                $url,
                [
                    'auth' =>
                        [
                            $this->params->get('bank.login'),
                            $this->params->get('bank.pass'),
                            'basic'
                        ],
                    'body' => $params
                ]
            );
        } catch (GuzzleException $e) {
            echo $e->getMessage();

            return false;
        }
        $res->getBody();
        $resArray = json_decode($res->getBody(), true);

        return $resArray;
    }

}