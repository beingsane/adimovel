<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImovel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imovels', function(Blueprint $table){
            $table->increments('id');
            $table->integer('tp_imovel')->unsigned();
            $table->foreign('tp_imovel')->references('id')->on('tp_imovels');
            $table->string('titulo_anuncio');
            $table->integer('id_proprietario')->unsigned();
            $table->foreign('id_proprietario')->references('id')->on('pessoas');
            $table->integer('id_corretor')->unsigned();
            $table->foreign('id_corretor')->references('id')->on('usuarios');
            $table->string('codigo_interno');
            $table->string('endereco');
            $table->integer('nm_endereco');
            $table->string('bairro');
            $table->integer('cidade')->unsigned();
            $table->foreign('cidade')->references('id')->on('cidades');
            $table->string('nm_cep');
            $table->string('area');
            $table->integer('qt_quartos');
            $table->integer('qt_banheiros');
            $table->integer('qt_vagasgaragem');
            $table->string('referencia')->nullabel();
            $table->string('descricao')->nullabel();
            $table->double('valor',20,2);
            $table->boolean('vitrine');
            $table->boolean('financiamento');
            $table->boolean('reservado')->default(false);
            $table->date('dt_cadastrado');
            $table->string('latitude')->nullabel();
            $table->string('longitude')->nullabel();
            $table->char('finalidade', 3);
            $table->boolean('ativo')->default(true);
            
                        
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
        Schema::drop('imovels');
    }
}

/*
INSERT INTO imovels (tp_imovel,titulo_anuncio,id_proprietario,id_corretor,codigo_interno,endereco,nm_endereco,bairro,cidade,nm_cep,area,qt_quartos,qt_banheiros,qt_vagasgaragem,referencia,descricao,valor,vitrine,financiamento,dt_cadastrado,latitude,longitude,situacao_imovel) values (1,'Imovel 1',8,1,'123-21','Endereço eqe',12,'bairroo',1,'324234','234',2,1,2,'ref','descricc',122.00,true,true,'2016-01-01','12312','123','VEN')
*/