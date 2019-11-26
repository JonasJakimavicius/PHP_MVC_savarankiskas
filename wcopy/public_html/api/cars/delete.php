<?php

require '../../../bootloader.php';

$repository=new \App\Products\Repository();
$response=new \Core\Api\Response();

$car=$repository->load('id',$_POST['data-id']);

if($car){
    $response->addData($car->getData());
    $status=$repository->delete($car);

}else{
    $response->addError('Participant doesn`t exist');
}


$response->print();