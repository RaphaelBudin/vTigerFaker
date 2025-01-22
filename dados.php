<?php

require_once 'vendor/autoload.php';

$faker = Faker\Factory::create('pt_BR');
echo $faker->name . "<br/> \n";
echo $faker->email. "<br/> \n";
echo $faker->address(). "<br/> \n";
echo $faker->city(). "<br/> \n";
echo $faker->phoneNumber(). "<br/> \n";

echo $faker->company(). "<br/> \n";
echo $faker->url(). "<br/> \n";
echo $faker->imageUrl(). "<br/> \n";