<?php
namespace App;

use App\Container\Container;
use App\Services\User;

require_once (__DIR__."/../vendor/autoload.php");




$container = new Container();

$concrete = $container->get(User::class);
$concrete->accessUser()->setName("univers00");
echo $concrete->accessUser()->getName();

//var_dump($container);
