<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContratoAluguel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contrato_aluguel', function(Blueprint $table){
            $table->increments('id');
            $table->double('valor',20,2);
            $table->boolean('situacao');
            $table->string('num_contrato');
            $table->date('data_inicio');
            $table->date('data_vencimento');
            $table->date('data_revogado');
            $table->boolean('status');
            $table->integer('imovel')->unsigned();
            $table->foreign('imovel')->references('id')->on('imovel');
            $table->integer('inquilino')->unsigned();
            $table->foreign('inquilino')->references('id')->on('pessoa');
            $table->integer('proprietario')->unsigned();
            $table->foreign('proprietario')->references('id')->on('pessoa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contrato_aluguel');
    }
}