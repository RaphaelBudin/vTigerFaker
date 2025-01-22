<?php

namespace Models;

use ErrorException;
use Faker\Factory as FakerFactory;

class Contacts extends EntityModelBase
{
    protected $entityName = 'Contacts';
    protected $tableName = 'contacts';
    protected $executionTimes = 0;
    protected $executionMethod = '';
    protected $describe = [];

    public function recordFactory_describe()
    {
        $fields = self::describe();
        $fields = $fields['fields'];
        $faker = FakerFactory::create('pt_BR');
        $record = [];

        foreach ($fields as $field) {
            $fieldName = $field['name'];
            $fieldType = $field['type'];

            # Campos que não devem ser enviados na API
            $skippableFieldsModuleSpecific = [];
            $skippableFieldsModuleAgnostic = [
                'createdtime',
                'modifiedtime',
                'modifiedby',
                'isconvertedfromlead',
                'source',
                'tags',
                'notify_owner',
                'starred',
                'id',
            ];
            $skippableFields = $skippableFieldsModuleSpecific + $skippableFieldsModuleAgnostic;
            if (in_array($fieldName, $skippableFields)) continue;

            ### Para tipos Primitivos (do vTiger)

            switch ($fieldType['name'] ?? '') {
                case 'int':
                    break;
                case 'double':
                    $record[$fieldName] = $faker->randomFloat(2, 0, 100);
                    break;
                case 'currency':
                    $record[$fieldName] = $faker->numberBetween(12000, 100000);
                    break;
                case 'boolean':
                    $record[$fieldName] = $faker->boolean();
                    break;
                case 'datetime':
                    $record[$fieldName] = $faker->dateTimeThisDecade()->format('Y-m-d H:i:s');
                    break;
                case 'date':
                    $record[$fieldName] = $faker->dateTimeThisDecade()->format('Y-m-d');
                    break;
                case 'time':
                    $record[$fieldName] = $faker->dateTimeThisDecade()->format('H:i:s');
                case 'cnpj':
                    $record[$fieldName] = $faker->cnpj();
                    break;
                case 'email':
                    $record[$fieldName] = $faker->companyEmail();
                    break;
                case 'phone':
                    $record[$fieldName] = $faker->phoneNumber();
                    break;
                case 'text':
                    $text = $faker->paragraphs();
                    $text = implode("\n\n",$text);
                    $record[$fieldName] = $text;
                    break;
            }

            ### Para picklists 

            if (isset($fieldType['picklistValues'])) {
                $options = $fieldType['picklistValues'];
                $record[$fieldName] = $faker->randomElement($options)['value'];
            }

            ### Para os campos de tipo genéricos multi-módulos

            if (strpos($fieldName, 'street') !== false) {
                $record[$fieldName] = $faker->address();
            }
            if (strpos($fieldName, 'city') !== false) {
                $record[$fieldName] = $faker->city();
            }
            if (strpos($fieldName, 'state') !== false) {
                $record[$fieldName] = $faker->uf();
            }
            if (strpos($fieldName, 'country') !== false) {
                $record[$fieldName] = 'Brasil';
            }
            if (strpos($fieldName, 'pobox') !== false) {
                $record[$fieldName] = $faker->cityPrefix() . ' ' . $faker->citySuffix();
            }
            if (strpos($fieldName, '_code') !== false) {
                $record[$fieldName] = $faker->postcode();
            }
            if (strpos($fieldName, 'zip') !== false) {
                $record[$fieldName] = $faker->postcode();
            }
            if (strpos($fieldName, 'website') !== false) {
                $record[$fieldName] = $faker->url();
            }

            ### Responsável 

            if (strpos($fieldName, 'assigned_user_id') !== false) {
                $record[$fieldName] = $faker->usuarioNetsac();
            }

            ### Para os campos específicos do módulo

            if (strpos($fieldName, 'firstname') !== false) {
                $record[$fieldName] = $faker->firstName();
            }
            if (strpos($fieldName, 'lastname') !== false) {
                $record[$fieldName] = $faker->lastName();
            }
            if (strpos($fieldName, 'title') !== false) {
                $record[$fieldName] = $faker->cargo();
            }
            if (strpos($fieldName, needle: 'department') !== false) {
                $record[$fieldName] = $faker->departamento();
            }

            ### Para vincular à uma organização

            if (strpos($fieldName, 'account_id') !== false) {
                $accounts_all = Accounts::readFromFile('accounts_all.json');
                $accounts_all = array_merge(...$accounts_all);
                $account = Accounts::chooseRandom($accounts_all);
                $record[$fieldName] = $account['id'];
            }

        }
        // Retornar o record gerado, opcionalmente, você pode codificar em JSON e URL-encodar como no seu exemplo.
        $this->toJSONFile('record', $record);
        $record = json_encode($record);
        $record = urlencode($record);
        return $record;
    }

    public function recordFactory_fixed()
    {
        $faker = FakerFactory::create('pt_BR');
        $record = [];
        $record = json_encode($record);
        $record = urlencode($record);
        return $record;
    }
}
