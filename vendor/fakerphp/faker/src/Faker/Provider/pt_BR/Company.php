<?php

namespace Faker\Provider\pt_BR;

use Models\Accounts;
use Models\Contacts;

require_once 'check_digit.php';

class Company extends \Faker\Provider\Company
{
    protected static $formats = [
        '{{lastName}} {{companySuffix}}',
        '{{lastName}}-{{lastName}}',
        '{{lastName}} e {{lastName}}',
        '{{lastName}} e {{lastName}} {{companySuffix}}',
        '{{lastName}} Comercial Ltda.',
    ];

    protected static $companySuffix = ['e Filhos', 'e Associados', 'Ltda.', 'S.A.'];

    /**
     * A random CNPJ number.
     *
     * @see http://en.wikipedia.org/wiki/CNPJ
     *
     * @param bool $formatted If the number should have dots/slashes/dashes or not.
     *
     * @return string
     */
    public function cnpj($formatted = true)
    {
        $n = $this->generator->numerify('########0001');
        $n .= check_digit($n);
        $n .= check_digit($n);

        return $formatted ? vsprintf('%d%d.%d%d%d.%d%d%d/%d%d%d%d-%d%d', str_split($n)) : $n;
    }

    /**
     * Nome aleatório de Setor
     * 
     * @return string
     */
    public function segmento()
    {
        $industry = ['Bancário', 'Farmacêutico', 'Varejo', 'Tecnologia', 'Agronomia', 'Indústria', 'Têxtil', 'Influencers'];
        return $industry[array_rand($industry)];
    }

    /**
     * Código de usuários cadastrados na instância modelo da netSAC
     * 
     * @return string
     */
    public function usuarioNetsac()
    {
        $users = ['1', '13', '15', '18', '22', '8', '21', '16', '20'];
        return '19x' . $users[array_rand($users)];
    }

    public function uf()
    {
        $ufs = [
            "Acre",
            "Alagoas",
            "Amapá",
            "Amazonas",
            "Bahia",
            "Ceará",
            "Distrito Federal",
            "Espírito Santo",
            "Goiás",
            "Maranhão",
            "Mato Grosso",
            "Mato Grosso do Sul",
            "Minas Gerais",
            "Pará",
            "Paraíba",
            "Paraná",
            "Pernambuco",
            "Piauí",
            "Rio de Janeiro",
            "Rio Grande do Norte",
            "Rio Grande do Sul",
            "Rondônia",
            "Roraima",
            "Santa Catarina",
            "São Paulo",
            "Sergipe",
            "Tocantins"
        ];
        return $ufs[array_rand($ufs)];
    }

    public function cargo()
    {
        $cargos = [
            "Analista de Sistemas",
            "Desenvolvedor Backend",
            "Desenvolvedor Frontend",
            "Gerente de Projetos",
            "Coordenador de TI",
            "Analista de Marketing",
            "Designer Gráfico",
            "Assistente Administrativo",
            "Auxiliar de Vendas",
            "Consultor de Vendas",
            "Gerente de Recursos Humanos",
            "Diretor Comercial"
        ];
        return $cargos[array_rand($cargos)];
    }

    public function departamento()
    {
        $departamentos = [
            "TI (Tecnologia da Informação)",
            "Marketing",
            "Vendas",
            "Recursos Humanos",
            "Financeiro",
            "Operações",
            "Atendimento ao Cliente",
            "Suporte Técnico",
            "Jurídico",
            "Pesquisa e Desenvolvimento",
            "Logística",
            "Administração"
        ];
        return $departamentos[array_rand($departamentos)];
    }


    public function organizacao($filename = 'accounts_all.json')
    {
        $accounts_all = Accounts::readFromFile($filename);
        $accounts_all = array_merge(...$accounts_all);
        return $accounts_all[array_rand($accounts_all)];
    }

    public function contato($filename='contacts_all.json'){
        $contacts = Contacts::readFromFile($filename);
        $contacts = array_merge(...$contacts);
        return $contacts[array_rand($contacts)];
    }
}