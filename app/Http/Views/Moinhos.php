<?php

namespace App\Http\Views;


class Moinhos
{
    public function dados(){
        $conexao = "(DESCRIPTION =
        (ADDRESS = (PROTOCOL = TCP)(HOST = 200.238.32.17)(PORT = 1521))
        (CONNECT_DATA = (SERVICE_NAME = ORARAC.WORLD))
            )";

        $banco =  oci_connect('Wings', 'Wings', $conexao, 'AL32UTF8');

        if(!$banco){
            $e = oci_error();
            trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
        }


        $variavelName = 'DD-MM-YYYY HH24:MI:SS';
        $variavelNameX = 'YYYY-MM-DD HH24:MI:SS';
    
        $consulta = 'SELECT 
        "Atendimento" AS "atendimento",
        "Tipo_atendimento" AS "tipo_atendimento",
        "Prontuário" AS "prontuario",
        "Paciente" AS "paciente",
        "Data_Nasc" AS "data_nasc",
        "Nome_mae" AS "nome_mae",
        "Prestador" AS "prestador",
        "Código_UI" AS "codigo_ui",
        "Unidade_Internacao" AS "unidade_internacao",
        "Código_leito" AS "codigo_leito",
        "Leito" AS "leito",
        "Código_setor" AS "codigo_setor",
        "Setor" AS "setor",
        "Codigo_item_prescrito" AS "codigo_item_prescrito",
        "Descricao_item_prescrito" AS "descricao_item_prescrito",
        "Observacao_item" AS "observacao_item",
        "Justificativa_item" AS "justificativa_item",
        "SN_cancelado" AS "sn_cancelado",
        "Pedido_exame" AS "pedido_exame",
        TO_CHAR("Data_pedido" , :variavelName ) AS "data_pedido",
        TO_CHAR("Hora_pedido" , :variavelName ) AS "hora_pedido",
        TO_CHAR("Hora_pedido" , :variavelNameX ) AS "hora_pedidoX",
        "Código_setor_exame" AS "codigo_setor_exame",
        "Setor_exame" AS "setor_exame",
        "codigo_Exame" AS "codigo_exame",
        "Descricao_exame" AS "descricao_exame",
        "Motivo_exame" AS "motivo_exame",
        "Acess_number" AS "acess_number",
        "SN_realizado" AS "sn_realizado",
        TO_CHAR("Data_realizado" , :variavelName ) AS "data_realizado",
        "Veiculo" AS "veiculo",
        "Uso_o2" AS "uso_o2",
        "Vent_mec" AS "vent_mec",
        "Isolamento" AS "isolamento",
        "Tipo_isolamento" AS "tipo_isolamento",
        "Acomp_Enf" AS "acom_enf",
        "Local_exame" AS "local_exame",
        "Cor_classificacao" AS "cor_classificacao",
        "tipo_risco" AS "tip_risco"
        FROM dbahmv.view_exames_3wings
        ';

        $stid = oci_parse($banco, $consulta);

        oci_bind_by_name($stid, ':variavelName', $variavelName);
        oci_bind_by_name($stid, ':variavelNameX', $variavelNameX);
        oci_execute($stid);

        oci_close($banco);
        return $stid;
    }
}