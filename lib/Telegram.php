<?php
namespace Lib;

use GuzzleHttp\Client;

class Telegram
{
    private $apiURL;
    private $client;
    private $error = [];

    /**
     * [__construct description]
     *
     * @return  [type]  [return description]
     */
    public function __construct()
    {
        $this->apiURL = 'https://api.telegram.org/bot956711743:AAFFdGFskUekAmNnyUrbH2gEUu22Fs0w_xc/';
        $this->client = new Client(['base_uri' => $this->apiURL]);
    }

    /**
     * [proccess_request description]
     *
     * @param   [type]  $offset  [$offset description]
     *
     * @return  [type]           [return description]
     */
    public function proccess_request($offset = 0)
    {
        $response = $this->client->get('getUpdates', [
            "query" => ["offset" => $offset],
        ]);
        if (!$response) {
            $this->error["message"] = "couldent get the update";
            return false;
        }
        $updates = json_decode($response->getBody(), true);
        return $updates;
    }

    /**
     * [send_message_request description]
     *
     * @param   int     $_chat_id  [$_chat_id description]
     * @param   string  $_message  [$_message description]
     *
     * @return  [type]             [return description]
     */
    public function send_message_request(int $_chat_id, string $_message)
    {
        $res = $this->client->post('sendMessage', [
            'query' => [
                'chat_id' => $_chat_id,
                'text' => $_message,
            ],
        ]);
        if ($res->getStatusCode() !== 200) {
            $this->error["message"] = "couldent send_message_request to the api";
            return false;
        }

        if (json_decode($res->getBody()->getContents(), true) === true);
        return true;
    }

    /**
     * [send_file_request description]
     *
     * @param   int     $_chat_id  [$_chat_id description]
     * @param   string  $_path     [$_path description]
     *
     * @return  [type]             [return description]
     */
    public function send_file_request(int $_chat_id, string $_path, string $_caption)
    {
        $res = $this->client->post('sendDocument', [
            'multipart' => [
                ['name' => 'chat_id', 'contents' => $_chat_id],
                [
                    'name' => 'document',
                    'contents' => fopen($_path, 'r'),
                ],
                [
                    'name' => 'caption',
                    'contents' => $_caption . "\n@mangadl_tbot",
                ],
            ],
        ]);
        if ($res->getStatusCode() !== 200) {
            $this->error["message"] = "couldent send_file_request to the api";
            return false;
        }

        $response = json_decode($res->getBody()->getContents(), true);
        if (!$response["ok"]) {
            $this->error["message"] = "TG Error is :" . $response["description"];
            return false;
        }
        return $response['result']['document'];
    }

    public function send_file_id_request_pdf(int $_chat_id, string $_file_id, string $_caption)
    {
        $res = $this->client->post('sendDocument', [
            'multipart' => [
                ['name' => 'chat_id', 'contents' => $_chat_id],
                [
                    'name' => 'document',
                    'contents' => $_file_id,
                ],
                [
                    'name' => 'caption',
                    'contents' => $_caption . "\n@mangadl_tbot",
                ],
            ],
        ]);
        if ($res->getStatusCode() !== 200) {
            $this->error["message"] = "couldent send_file_id_request to the api";
            return false;
        }

        $response = json_decode($res->getBody()->getContents(), true);
        if (!$response["ok"]) {
            $this->error["message"] = "TG Error is :" . $response["description"];
            return false;
        }
        return true;
    }

    public function send_file_id_request_zip(int $_chat_id, string $_file_id, string $_caption)
    {
        $res = $this->client->post('sendDocument', [
            'multipart' => [
                ['name' => 'chat_id', 'contents' => $_chat_id],
                [
                    'name' => 'document',
                    'contents' => $_file_id,
                ],
                [
                    'name' => 'caption',
                    'contents' => $_caption . "\n@mangadl_tbot",
                ],
            ],
        ]);
        if ($res->getStatusCode() !== 200) {
            $this->error["message"] = "couldent send_file_id_request to the api";
            return false;
        }

        $response = json_decode($res->getBody()->getContents(), true);
        if (!$response["ok"]) {
            $this->error["message"] = "TG Error is :" . $response["description"];
            return false;
        }
        return true;
    }

    /**
     * [get_error description]
     *
     * @return  [type]  [return description]
     */
    public function get_error()
    {
        if (empty($this->error)) {
            return false;
        }
        return $this->error;
    }
}
