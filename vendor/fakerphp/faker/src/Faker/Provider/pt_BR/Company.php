<?php

namespace Faker\Provider\pt_BR;

use Models\Accounts;
use Models\Contacts;
use Models\Potential;

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
        $accounts = Accounts::readFromFile($filename);
        $accounts = array_merge(...$accounts);
        return Accounts::chooseRandom($accounts);
    }

    public function contato($filename = 'contacts_all.json')
    {
        $contacts = Contacts::readFromFile($filename);
        $contacts = array_merge(...$contacts);
        return Contacts::chooseRandom($contacts);
    }

    public function tituloOportunidade()
    {
        $titulosOportunidades = [
            "Proposta de Expansão de Mercado - Ago/24",
            "Negociação com Cliente Corporativo - Set/24",
            "Oportunidade de Upgrade de Serviço - Out/24",
            "Renovação de Contrato com Cliente - Nov/24",
            "Abertura de Nova Conta Comercial - Dez/24",
            "Venda de Solução Personalizada para Empresa - Jan/25",
            "Oportunidade de Parceria Estratégica - Ago/24",
            "Proposta de Redução de Custos para Cliente - Set/24",
            "Oportunidade de Venda de Produto Premium - Out/24",
            "Negociação de Projeto Personalizado - Nov/24",
            "Oportunidade de Fidelização de Cliente - Dez/24",
            "Proposta de Expansão para Novos Mercados - Jan/25",
            "Negociação de Novo Termo de Contrato - Ago/24",
            "Venda de Software Empresarial - Set/24",
            "Proposta de Parceria de Longo Prazo - Out/24",
            "Renovação de Plano de Manutenção - Nov/24",
            "Oferta de Desconto Especial para Cliente - Dez/24",
            "Venda de Licenciamento para Novos Clientes - Jan/25",
            "Negociação de Serviços de Consultoria - Ago/24",
            "Acordo de Parceria Comercial com Cliente - Set/24",
            "Proposta de Venda de Equipamento - Out/24",
            "Proposta de Negociação de Preço - Nov/24",
            "Venda de Solução SaaS para Empresas - Dez/24",
            "Oportunidade de Crescimento de Mercado - Jan/25",
            "Proposta de Novo Serviço Personalizado - Ago/24",
            "Venda de Soluções Tecnológicas para Empresas - Set/24",
            "Oportunidade de Venda de Licenciamento Anual - Out/24",
            "Proposta de Expansão Internacional - Nov/24",
            "Acordo de Revenda de Produtos - Dez/24",
            "Venda de Tecnologia de Ponta - Jan/25",
            "Oportunidade de Venda de Produtos de Nicho - Ago/24",
            "Venda de Treinamento Corporativo - Set/24",
            "Proposta de Ajuste de Contrato - Out/24",
            "Negociação de Pacote Completo de Serviços - Nov/24",
            "Oportunidade de Venda de Serviços Integrados - Dez/24",
            "Proposta de Solução para Automação - Jan/25",
            "Oportunidade de Crescimento em Nova Região - Ago/24",
            "Venda de Sistemas de Gerenciamento Empresarial - Set/24",
            "Abertura de Nova Conta de Cliente Corporativo - Out/24",
            "Proposta de Parceria de Crescimento - Nov/24",
            "Venda de Soluções de Infraestrutura - Dez/24",
            "Oportunidade de Venda de Sistema de CRM - Jan/25",
            "Negociação de Condições Especiais para Cliente - Ago/24",
            "Proposta de Upgrade de Sistema - Set/24",
            "Oportunidade de Negociação de Contrato de Longo Prazo - Out/24",
            "Venda de Software de Gestão de Projetos - Nov/24",
            "Proposta de Acordo de Distribuição - Dez/24",
            "Oportunidade de Venda de Plataforma de E-commerce - Jan/25",
            "Venda de Solução para Análise de Dados - Ago/24",
            "Oportunidade de Acordo de Revenda - Set/24",
            "Proposta de Implementação de Tecnologia - Out/24",
            "Venda de Soluções para Energia Sustentável - Nov/24",
            "Proposta de Parceria para Marketing Digital - Dez/24",
            "Venda de Solução para Gestão de Processos - Jan/25",
            "Oportunidade de Expansão de Serviços para o Exterior - Ago/24",
            "Venda de Soluções de Segurança Digital - Set/24",
            "Oportunidade de Parceria com Fornecedores - Out/24",
            "Proposta de Renovação de Termos Contratuais - Nov/24",
            "Venda de Sistema de Gestão de Pessoas - Dez/24",
            "Proposta de Consultoria em Transformação Digital - Jan/25",
            "Negociação de Contrato de Parceria Estratégica - Ago/24",
            "Venda de Soluções para Indústria 4.0 - Set/24",
            "Oportunidade de Acordo para Desconto em Compras - Out/24",
            "Proposta de Atendimento Especial para Clientes - Nov/24",
            "Venda de Soluções de Mobilidade Empresarial - Dez/24",
            "Oportunidade de Parceria Comercial Internacional - Jan/25",
            "Proposta de Reestruturação de Processos - Ago/24",
            "Venda de Soluções de Marketing e Vendas - Set/24",
            "Oportunidade de Negociação de Novo Produto - Out/24",
            "Venda de Solução para Gestão de Inventário - Nov/24",
            "Proposta de Novo Pacote de Serviços - Dez/24",
            "Venda de Tecnologia de Gestão de Equipes - Jan/25",
            "Proposta de Implementação de CRM Corporativo - Ago/24",
            "Venda de Software de Atendimento ao Cliente - Set/24",
            "Oportunidade de Crescimento em Novos Segmentos - Out/24",
            "Venda de Soluções de Automação para Empresas - Nov/24",
            "Proposta de Reestruturação de Tecnologia Empresarial - Dez/24",
            "Oportunidade de Parceria com Empresas de Software - Jan/25",
            "Venda de Soluções de Inteligência Artificial - Ago/24",
            "Proposta de Expansão para Mercado Nacional - Set/24",
            "Oportunidade de Iniciar Novo Projeto de TI - Out/24",
            "Venda de Tecnologia de Big Data para Empresas - Nov/24",
            "Proposta de Consultoria Estratégica para Startups - Dez/24",
            "Oportunidade de Venda de Sistemas de Automação de Marketing - Jan/25",
            "Venda de Soluções para Transformação Digital - Ago/24",
            "Proposta de Parceria em Projetos Especiais - Set/24",
            "Oportunidade de Venda de Soluções de Software de Nuvem - Out/24",
            "Proposta de Expansão do Portfólio de Produtos - Nov/24",
            "Venda de Soluções para Otimização de Processos - Dez/24",
            "Oportunidade de Alavancar Vendas com Parcerias - Jan/25",
            "Proposta de Parceria de Suporte Técnico - Ago/24",
            "Venda de Sistemas de Gestão de Compras - Set/24",
            "Oportunidade de Desenvolver Software Sob Demanda - Out/24",
            "Proposta de Implementação de Soluções de TI - Nov/24",
            "Venda de Sistema para Gestão de Equipes Remotas - Dez/24",
            "Oportunidade de Expandir Operações para Nova Região - Jan/25",
            "Venda de Soluções para Empresas de E-commerce - Ago/24",
            "Proposta de Sistema de Gestão Financeira - Set/24",
            "Oportunidade de Consultoria para Melhoria de Processos - Out/24",
            "Venda de Soluções para Automação de Marketing Digital - Nov/24",
            "Proposta de Implementação de Soluções Corporativas - Dez/24",
            "Venda de Produtos Tecnológicos para Indústrias - Jan/25",
            "Oportunidade de Expansão para Mercado Internacional - Ago/24",
            "Proposta de Venda de Soluções para Pequenas Empresas - Set/24",
            "Venda de Sistema de Gerenciamento de Tarefas - Out/24",
            "Oportunidade de Consultoria para Crescimento de Vendas - Nov/24",
            "Proposta de Parceria Estratégica para Inovação - Dez/24",
            "Venda de Soluções para Crescimento Corporativo - Jan/25",
            "Oportunidade de Negociação de Novos Produtos para Revenda - Ago/24",
            "Venda de Soluções de Cloud Computing para Empresas - Set/24",
            "Proposta de Expansão de Portfólio de Produtos - Out/24",
            "Oportunidade de Venda de Soluções de Gestão de Projetos - Nov/24",
            "Venda de Tecnologia para Indústria de Varejo - Dez/24",
            "Proposta de Implementação de Ferramentas de Gestão - Jan/25"
        ];
        return $titulosOportunidades[array_rand($titulosOportunidades)];
    }

    public function oportunidade()
    {
        $oportunidades = Potential::readFromFile('potentials_all.json');
        $oportunidades = array_merge(...$oportunidades);
        return Potential::chooseRandom($oportunidades);
    }
}
