<?php

require '../../../bootloader.php';

$response = new \Core\Api\Response();
$products = \App\App::$prod_repository->load(['id' => $_POST['data-id']]);


if ($products) {
    foreach ($products as $product) {
        $response->addData($product->getData());
        \App\App::$prod_repository->delete($product);
    }
} else {
    $response->addError('Product does not exist');
}

$response->print();