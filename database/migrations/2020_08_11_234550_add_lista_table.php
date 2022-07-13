<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddListaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement('
            CREATE VIEW lista_inscritos as 
            SELECT UUID() as id, 
                se.dia, 
                se.horas as Sessao, 
                ev.nome as Evento,
                ins.nome as NomeReponsavel,
                ins.data_nascimento as DataNascimentoResponsavel,
                ins.documento_id as DocumentoIdentificacaoResponsavel,
                ins.morada as MoradaResponsavel,
                ins.email as EmailResponsavel,
                ac.nome as NomeAcompanhamente,
                ac.morada as MoradaAcompanhamente,
                ac.data_nascimento as DataNascimentoAcompanhamente, 
                ac.crianca as LevaCrianca 
                FROM inscrito_sessao 
                insse LEFT JOIN inscritos ins 
                ON ins.id = insse.inscrito_id 
                LEFT JOIN sessaos se 
                ON se.id = insse.sessao_id 
                INNER JOIN eventos ev 
                ON ev.id = se.evento_id 
                LEFT JOIN inscrito_acompanhante inac 
                ON inac.inscrito_id = ins.id 
                LEFT JOIN acompanhantes ac 
                ON ac.id = inac.acompanhante_id
            ORDER BY ins.created_at
        ');
    }

    /**
     * Reverse the migrati
     * ons.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("DROP VIEW lista_inscritos");
    }
}
