<?php

namespace Models;

use Faker\Factory as FakerFactory;

class Accounts extends EntityModelBase
{
    protected $entityName = 'Accounts';
    protected $tableName = 'accounts';
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
            switch ($fieldName) {
                case 'account_id':
                case 'account_no':
                case 'createdtime':
                case 'modifiedtime':
                case 'modifiedby':
                case 'isconvertedfromlead':
                case 'source':
                case 'tags':
                case 'notify_owner':
                case 'starred':
                case 'id':
                    #É para continuar o loop do foreach, ao invés de apenas sair do switc-hcase
                    continue 2;
                default:;
            }

            ### Para tipos Primitivos (do vTiger)

            // Picklist
            if (isset($fieldType['picklistValues'])) {
                // Para picklist, escolher aleatoriamente um valor da lista de opções
                $options = $fieldType['picklistValues'];
                $record[$fieldName] = $faker->randomElement($options)['value'];
            }
            //Currency
            elseif (isset($fieldType['name']) && $fieldType['name'] == 'currency') {
                // Gerar um valor de receita anual (número entre 12.000 e 100.000)
                $record[$fieldName] = $faker->numberBetween(12000, 100000);
            }
            // Boolean
            elseif (isset($fieldType['name']) && $fieldType['name'] == 'boolean') {
                // Gerar valor booleano aleatório
                $record[$fieldName] = $faker->boolean();
            }
            // Datetime
            elseif (isset($fieldType['name']) && $fieldType['name'] == 'datetime') {
                // Gerar uma data aleatória
                $record[$fieldName] = $faker->dateTimeThisDecade()->format('Y-m-d H:i:s');
            }
            // CNPJ
            elseif (isset($fieldType['name']) && $fieldType['name'] == 'cnpj') {
                $record[$fieldName] = $faker->cnpj();
            }
            // Email
            else if (strpos($fieldType['name'], 'email') !== false) {
                $record[$fieldName] = $faker->companyEmail();
            }
            // Telefone
            else if (strpos($fieldType['name'], 'phone') !== false) {
                $record[$fieldName] = $faker->phoneNumber();
            }


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
            if (strpos($fieldName, 'employees') !== false) {
                $record[$fieldName] = $faker->numberBetween(1, 20);
            }
            if (strpos($fieldName, 'assigned_user_id') !== false) {
                $record[$fieldName] = $faker->usuarioNetsac();
            }
            if (strpos($fieldName, 'accountname') !== false) {
                $record[$fieldName] = $faker->company();
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
        $record = json_encode($record);
        file_put_contents('teste.json', $record);
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
