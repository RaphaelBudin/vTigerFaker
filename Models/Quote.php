<?php

namespace Models;

use ErrorException;
use Faker\Factory as FakerFactory;

class Quote extends EntityModelBase
{
    protected $entityName = 'Quotes';
    protected $tableName = 'quotes';
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
                'quote_no',
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

            if (strpos($fieldName, 'subject') !== false) {
                $record[$fieldName] = $faker->tituloOportunidade;
            }

            ### Vinculações com módulos relacionados
            if (
                strpos($fieldName, 'contact_id') !== false ||
                strpos($fieldName, 'account_id') !== false ||
                strpos($fieldName, 'related_to') !== false &&
                !isset($record[$fieldName])
            ) {
                # Evitar reler o arquivo novamente
                $potentials = Potential::readFromFile('potential_all.json');
                $potentials = array_merge(...$potentials);
                $potentials = Potential::attributeAsKeys($potentials, 'contact_id');

                do {
                    $contact = $faker->contato();
                    $record['contact_id'] = $contact['id'];
                    $record['account_id'] = $contact['account_id'];
                } while (!isset($potentials[$contact['id']]));

                #...Contato com Oportunidade
                $potential = $potentials[$contact['id']];
                $record['potential_id'] = $potential['id'];
                $record['subject'] = $potential['potentialname'];
            }
        }

        $record['LineItems'] = [];

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

    public createLineItem(){
        "parent_id": "4xArray",
                "productid": "14x105",
                "sequence_no": "1",
                "quantity": "1.000",
                "listprice": "110.00000000",
                "discount_percent": "",
                "discount_amount": "",
                "comment": "Cotação de Seguro",
                "description": "",
                "incrementondel": "0",
                "tax1": "",
                "tax2": "",
                "tax3": "",
                "image": "",
                "purchase_cost": "0.00000000",
                "margin": "110.00000000",
                "tax4": "4.000",
                "tax5": "0.000",
                "tax6": "0.000",
                "id": "33x74",
                "product_name": "TESTE - SIMPLES 3",
                "entity_type": "Products",
                "deleted": "0"
            }
    }
}
