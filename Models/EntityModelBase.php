<?php

namespace Models;

use ErrorException;

abstract class EntityModelBase implements EntityModel
{
    protected $entityName = '';
    protected $tableName = '';
    protected $executionTimes = 0;
    protected $executionMethod = '';
    protected $describe = [];

    /**
     * Puxa todos os campos do módulo
     * RecordFactory_Describe utiliza isso de base para gerar os dados aleatoriamente
     * @return array
     */
    public function describe()
    {
        global $URI, $SESSION_NAME, $UTILS;
        $params =  [
            'operation=describe',
            "sessionName={$SESSION_NAME}",
            "elementType={$this->entityName}",
        ];
        $response = $UTILS::sendHTTPGET($URI, $params);
        $response = $response['result'];

        $this->toJSONFile('describe', $response);
        $this->describe = $response;
        return $response;
    }

    /**
     * Conta total de registros não-deletados
     * @return int
     */
    public function countRecords()
    {
        global $URI, $SESSION_NAME, $UTILS;
        # Para o orderby 
        $query = urlencode("SELECT count(*) from {$this->entityName};");
        $params =  [
            'operation=query',
            "sessionName={$SESSION_NAME}",
            "query={$query}",
        ];
        $response = $UTILS::sendHTTPGET($URI, $params);
        $response = $response['result'];
        return (int)$response;
    }

    /**
     * Pega TODOS os registros não-deletados
     * @return array
     */
    public function all()
    {
        global $URI, $SESSION_NAME, $UTILS;
        $all_records = [];
        $lowerRange =  0;
        # Para pegar mais de 100 registros
        do {
            # Para o orderby 
            $query = urlencode("SELECT * from {$this->entityName} limit $lowerRange,100;");
            $params =  [
                'operation=query',
                "sessionName={$SESSION_NAME}",
                "query={$query}",
            ];
            $response = $UTILS::sendHTTPGET($URI, $params);
            $response = $response['result'];

            $all_records[] = $response;
            $lowerRange += 100;
        } while (count($response) == 100);

        $this->toJSONFile('all', $all_records);
        return $all_records;
    }

    /**
     * Cria registro usando o recordFactory_Describe ou recordFactory_Fixed
     * @param string $recordFactoryType
     * @return bool
     */
    public function create($recordFactoryType = 'describe')
    {
        global $URI, $SESSION_NAME, $UTILS;
        $record = $recordFactoryType == 'fixed' ? $this->recordFactory_fixed() : $this->recordFactory_describe();
        $params = [
            'operation=create',
            "sessionName={$SESSION_NAME}",
            "element={$record}",
            "elementType={$this->entityName}",
        ];
        $response = $UTILS::sendHTTPPOST($URI, $params);
        $response = $response['result'];
        return true;
    }

    /**
     * Cria um registro com base em um array especificado pelo programador
     * @return array|string  Resposta
     */
    abstract public function recordFactory_fixed();
    /**
     * Cria um registro com base no describe do módulo, com tratamento específico dado na subclasse
     * @return array|string Resposta 
     */
    abstract public function recordFactory_describe();

    /**
     * Número de vezes que um método deve ser executado
     * @param int $executionTimes
     * @param string $executionMethod
     * @return static
     */
    public function count($executionTimes, $executionMethod)
    {
        $this->executionTimes = $executionTimes;
        $this->executionMethod = $executionMethod;
        return $this;
    }

    /**
     * Executa o método fornecido por count()
     * @throws \ErrorException
     * @return void
     */
    public function execute()
    {
        if (!method_exists($this, $this->executionMethod)) {
            throw new ErrorException("Método {$this->executionMethod} não existe");
        }

        for ($i = 0; $i < $this->executionTimes; $i++) {
            call_user_func([$this, $this->executionMethod]);
        }
    }

    /**
     * Deleta registro informado - NÃO IMPLEMENTADO
     * @throws \ErrorException
     * @return never
     */
    public function delete()
    {
        throw new ErrorException("Método delete() não implementado");
    }

    /**
     * Converte dados para JSON e armazena localmente
     * Nome do arquivo é padronizado automaticamente: módulo + nome informado + .json, tudo minúsculas
     * @param string $baseName
     * @param array $data
     * @return string Nome do arquivo final
     */
    public function toJSONFile($baseName, $data)
    {
        $className = basename(str_replace('\\', '/', get_class($this)));
        $fileName = strtolower("{$className}_{$baseName}.json");
        file_put_contents($fileName, json_encode($data, JSON_PRETTY_PRINT));
        return $fileName;
    }
}
