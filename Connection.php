<?php

namespace App;

class Connection
{
    private const API_KEY = '2mck9zrlrT1URPZVTeU15QUyFRXVA96F';

    public const SUBDOMAIN = 'morozovalex';

    public function post($postData, $path)
    {
        $curl = curl_init();
        /** Устанавливаем необходимые опции для сеанса cURL  */
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_URL, $this->getLink($path));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getHeaders());
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);

        $out = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $code = (int)$code;
        $errors = [
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not found',
            500 => 'Internal server error',
            502 => 'Bad gateway',
            503 => 'Service unavailable',
        ];

        try {
            /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
            if ($code < 200 || $code > 204) {
                throw new \Exception($errors[$code] ?? 'Undefined error', $code);
            }

            print_r($out);

            return $out;
        } catch (\Exception $e) {
            print_r(json_decode($out));
            die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
        }

    }
        private function getLink(string $path): string
        {
            return 'https://' . self::SUBDOMAIN . '.retailcrm.ru/' . $path;
        }

        private function getHeaders(): array
        {
            return [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'User-Agent' => 'RetailCRM PHP API Client / v6.x',
                'Accept' => 'application/json',
            ];
        }

        public function getApiKey() {
            return self::API_KEY;
        }

}