<?php

namespace Models;

use ErrorException;
use Faker\Factory as FakerFactory;

class Potential extends EntityModelBase
{
    protected $entityName = 'Potentials';
    protected $tableName = 'potentials';
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
                'potential_no',
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
                    $text = implode("\n\n", $text);
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

            ### Para os campos específicos do módulo que não se encaixam acima

            if (strpos($fieldName, 'potentialname') !== false) {
                $record[$fieldName] = $faker->tituloOportunidade;
            }

            ## Vinculações com módulos relacionados

            if ((strpos($fieldName, 'contact_id') !== false || strpos($fieldName, 'related_to') !== false) && !isset($record[$fieldName])) {
                # Na mesma hora para não vincular organizações que não tem nada a ver com o contato ao trocar o campo na iteração
                do {
                    $contact = $faker->contato();
                    var_dump($contact);
                } while (!isset($contact['account_id']) || $contact['account_id'] == '');
                $record['contact_id'] = $contact['id'];
                $record['related_to'] = $contact['account_id'];
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
