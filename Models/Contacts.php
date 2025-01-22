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
              $skippableFieldsModuleSpecific = [
                // 'account_id',
                // 'account_no',
            ];
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
            if(in_array($fieldName,$skippableFields)) continue;

            ### Para os campos específicos do módulo
            
            if (strpos($fieldName, 'employees') !== false) {
                $record[$fieldName] = $faker->numberBetween(1, 20);
            }
            if (strpos($fieldName, 'assigned_user_id') !== false) {
                $record[$fieldName] = $faker->usuarioNetsac();
            }
            if (strpos($fieldName, 'accountname') !== false) {
                $record[$fieldName] = $faker->company();
            }

            ### Para tipos Primitivos (do vTiger)

            // Picklist
            if (isset($fieldType['picklistValues'])) {
                $options = $fieldType['picklistValues'];
                $record[$fieldName] = $faker->randomElement($options)['value'];
            }
            //Currency
            elseif (isset($fieldType['name']) && $fieldType['name'] == 'currency') {
                $record[$fieldName] = $faker->numberBetween(12000, 100000);
            }
            // Boolean
            elseif (isset($fieldType['name']) && $fieldType['name'] == 'boolean') {
                $record[$fieldName] = $faker->boolean();
            }
            // Datetime
            elseif (isset($fieldType['name']) && $fieldType['name'] == 'datetime') {
                $record[$fieldName] = $faker->dateTimeThisDecade()->format('Y-m-d H:i:s');
            }
            // CNPJ
            elseif (isset($fieldType['name']) && $fieldType['name'] == 'cnpj') {
                $record[$fieldName] = $faker->cnpj();
            }
            else if (strpos($fieldType['name'], 'email') !== false) {
                $record[$fieldName] = $faker->companyEmail();
            }
            else if (strpos($fieldType['name'], 'phone') !== false) {
                $record[$fieldName] = $faker->phoneNumber();
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

            if (strpos($fieldName, 'website') !== false) {
                $record[$fieldName] = $faker->url();
            }

            switch ($fieldType['name'] ?? '') {
                case 'int':
                    break;
                case 'double':
                    $record[$fieldName] = $faker->randomFloat(2, 0, 100);
                    break;
                default:
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
        $record = [
            "accountname" => $faker->company(),
            "cf_884" => $faker->cnpj(),
            "email1" => $faker->companyEmail(),
            "email2" => $faker->companyEmail(),
            "phone" => $faker->phoneNumber(),
            "otherphone" => $faker->phoneNumber(),
            "website" => $faker->url(),
            "industry" => $faker->segmento(),
            "annual_revenue" => $faker->numberBetween(12000, 100000),
            "employees" => $faker->numberBetween(1, 500),
            "accounttype" => "Cliente",
            // "account_id" => 53,
            // "emailoptout" => "Recusa Email",
            // "notify_owner" => "Notificar Proprietário",
            "assigned_user_id" => $faker->usuarioNetsac(),
            "cf_1241" => "Ativo",
            "cf_1243" => "A",
            "cf_1245" => $faker->phoneNumber(),
            "bill_code" => $faker->postcode(),
            "ship_code" => $faker->postcode(),
            "bill_street" => $faker->address(),
            "ship_street" => $faker->address(),
            // "bill_pobox" => "Bairro Faturamento",
            // "ship_pobox" => "Bairro Entrega",
            "bill_city" => $faker->city(),
            "ship_city" => $faker->city(),
            "bill_state" => $faker->uf(),
            "ship_state" => $faker->uf(),
            "bill_country" => 'Brasil',
            "ship_country" => 'Brasil',
            "description" => $faker->paragraph()
        ];
        $record = json_encode($record);
        $record = urlencode($record);
        return $record;
    }
}
