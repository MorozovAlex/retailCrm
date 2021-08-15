<?php

namespace App;
require_once 'Connection.php';
require_once 'GetItem.php';

$connection = new Connection();
$getItem = new GetItem();

if (empty($_POST)) {
    try {
        $data = $getItem->getItem('api/v5/store/products', 'name', 'AZ105R', $connection->getApiKey());
        $data = json_decode($data, true);
        $offerId = $data['products'][0]['offers'][0]['id'];
    } catch (\Exception $exception) {
        print_r($exception->getMessage());
        print_r($exception->getFile());
        print_r($exception->getLine());
    }

    $postData = [
        'site' => 'morozovalex',
        'order' => json_encode([
                'status' => 'trouble',
                'orderType' => 'eshop-individual',
                'orderMethod' => 'test',
                'number' => '05031985',
                'firstName' => 'Алексей',
                'patronymic' => 'Сергеевич',
                'lastName' => 'Морозов',
                'customerComment' => 'тестовое задание',
                'managerComment' => 'https://github.com/MorozovAlex/retailCrm',
                'items' => [
                    [
                        'offer' => [
                            'id' => $offerId,
                        ],
                    ],
                ],
            ]),
        'apiKey' => $connection->getApiKey(),
    ];

    try {
        $connection->post($postData, 'api/v5/orders/create');
    } catch (\Exception $exception) {
        print_r($exception->getMessage());
        print_r($exception->getFile());
        print_r($exception->getLine());
    }
}