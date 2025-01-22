<?php

namespace Models;

use ErrorException;
use Faker\Factory as FakerFactory;

class Contacts
{
    protected $entityName = 'Contacts';
    protected $tableName = 'contacts';
    public function recordFactory()
    {
        $faker = FakerFactory::create('pt_BR');
        $record = [

        ];
        $record = json_encode($record);
        $record = urlencode($record);
        return $record;
    }
}
