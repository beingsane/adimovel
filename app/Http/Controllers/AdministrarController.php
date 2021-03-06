<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\ViewObject\AdministrarVO;
use App\Http\ViewObject\MovimentacaoVO;
use App\Http\ViewObject\ContratoVO;
//use App\Http\Enum\UsuarioEnum;

use App\JSONUtils;
use App\Messages;
use App\Contrato;
use App\Movimentacao;
use App\HistoricoAluguel;
use App\Recibo;

class AdministrarController extends Controller
{

    public function index($id = null)
    {  
        try{
            if ($id == null) {
                
                $contrato = Contrato::orderBy('id', 'asc')->where('ativo','=','t')->get();
                //$contrato = Contrato::orderBy('id', 'asc')->where('ativo', '=', 'true')->get();

                $return = array();

                foreach ($contrato as $key => $value) {
                    $cVO = new AdministrarVO(Contrato::find($value->id));
                    $return[] = $cVO;
                }

                return JSONUtils::returnSuccess('Consulta realizada com sucesso.', $return);
            } else {
                return $this->show($id);
            }
        } catch(Exception $e){
            return JSONUtils::returnDanger('Problema de acesso à base de dados.', $e);
        }    
    }

    public function getImoveisVendas()
    {
        try{    
            $contrato = Contrato::where("finalidade","=","VEN")->orderBy('id', 'asc')->get();
            //$contrato = Contrato::orderBy('id', 'asc')->where('ativo', '=', 'true')->get();

            $return = array();

            foreach ($contrato as $key => $value) {
                $cVO = new AdministrarVO(Contrato::find($value->id));
                $return[] = $cVO;
            }

            return JSONUtils::returnSuccess('Consulta realizada com sucesso.', $return);
        } catch(Exception $e){
            return JSONUtils::returnDanger('Problema de acesso à base de dados.', $e);
        }           
    }
    public function getImoveisAluguel()
    {
        try{    
            $contrato = Contrato::where("finalidade","=","LOC")->orderBy('id', 'asc')->get();
            //$contrato = Contrato::orderBy('id', 'asc')->where('ativo', '=', 'true')->get();

            $return = array();

            foreach ($contrato as $key => $value) {
                $cVO = new AdministrarVO(Contrato::find($value->id));
                $return[] = $cVO;
            }

            return JSONUtils::returnSuccess('Consulta realizada com sucesso.', $return);
        } catch(Exception $e){
            return JSONUtils::returnDanger('Problema de acesso à base de dados.', $e);
        }           
    }

    public function atualizaSituacao(Request $request, $id)
    {
        try{
            $contrato = Contrato::find($id);
            
            $contrato->situacao_pagamento = ($request->input('situacao') === "pago") ? true : false;
            //d($request->input('situacao'), $contrato->situacao_pagamento);
            $contrato->save();
            return JSONUtils::returnSuccess($contrato->nr_contrato .' alterado com sucesso.', $contrato);
        }catch(Exception $e){
            return JSONUtils::returnDanger('Problema de acesso à base de dados.', $e);
        }
    }

    public function show($id)
    {
        try{
            $contrato = new ContratoVO(Contrato::find($id));
            
            return JSONUtils::returnSuccess(Messages::MSG_QUERY_SUCCESS,
            $contrato);
        }catch(Exception $e){
            return JSONUtils::returnDanger('Problema de acesso à base de dados.',$e);
        }
    }

    public function getMovimentos($id)
    {
        try{
            $movimentacaos = Movimentacao::where('id_contrato','=',$id)->orderBy('id','desc')->get();

            $return = array();

            $mVO = "";
            if(!empty($movimentacaos[0])){
                $mVO = new MovimentacaoVO(Movimentacao::find($movimentacaos[0]->id));
                return JSONUtils::returnSuccess(Messages::MSG_QUERY_SUCCESS, $mVO->movimentacoes);
            }else{
                return JSONUtils::returnSuccess(Messages::MSG_QUERY_SUCCESS, $mVO);
            }
        }catch(Exception $e){
            return JSONUtils::returnDanger('Problema de acesso à base de dados.',$e);
        }
    }

    public function create(Request $request)
	{
		try{
            $contrato = new Contrato();

            $contrato->id_imovel            = $request->input('imovel');
            $contrato->id_proprietario      = $request->input('proprietario');
            $contrato->id_inquilino         = $request->input('inquilino');
            $contrato->nr_contrato          = $request->input('nr_contrato');
            $contrato->dt_inicio            = $request->input('dt_inicio');
            $contrato->dt_vencimento        = $request->input('dt_vencimento');
            $contrato->dt_revogado          = $request->input('dt_revogado');
            $contrato->valor                = $request->input('valor');
            $contrato->situacao_pagamento   = $request->input('situacao');
            $contrato->finalidade           = $request->input('finalidade');
            $contrato->ativo = true;

            $contrato->dt_revogado = '2016-01-01';
            //$validator = \Validator::make($request->all(), $this->validaCadastro());
	        //if ($validator->fails()) {
			//	return JSONUtils::returnDanger('Problema de validação verifique os campos e tente novamente.', "Erro");   
	        //}

        	$contrato->save();
            $contrato_id = $contrato->id;

            /*
            //  Adicionando informações de contrato na tabela de Historico de Aluguel
            */
            $historico = new HistoricoAluguel();
            $historico->id_imovel   = $request->input('imovel');
            $historico->id_proprietario   = $request->input('proprietario');
            $historico->id_inquilino   = $request->input('inquilino');
            //$historico->nr_contrato   = $request->input('nr_contrato');
            $historico->dt_inicio   = $request->input('dt_inicio');
            $historico->dt_termino   = $request->input('dt_vencimento');
            $historico->valor   = $request->input('valor');
            $historico->id_contrato = $contrato_id;
            //$validator = \Validator::make($request->all(), $this->validaCadastro());
            //if ($validator->fails()) {
            //  return JSONUtils::returnDanger('Problema de validação verifique os campos e tente novamente.', "Erro");   
            //}

            $historico->save();

			return JSONUtils::returnSuccess('Contrato n° '. $contrato->nr_contrato .' cadastrado com sucesso.', $contrato);

    	}catch(Exception $e){
    		return JSONUtils::returnDanger('Problema de acesso à base de dados.', $e);
    	}
    }

    public function validaCadastro()
    {
		return [
			'titulo'			=> 'required',
        ];
    }

    public function update(Request $request, $id)
    {
        try{
            $contrato = Contrato::find($id);
            
            $contrato->id_imovel            = $request->input('imovel');
            $contrato->id_proprietario      = $request->input('proprietario');
            $contrato->id_inquilino         = $request->input('inquilino');
            $contrato->nr_contrato          = $request->input('nr_contrato');
            $contrato->dt_inicio            = $request->input('dt_inicio');
            $contrato->dt_vencimento        = $request->input('dt_vencimento');
            $contrato->dt_revogado          = $request->input('dt_revogado');
            $contrato->valor                = $request->input('valor');
            $contrato->situacao_pagamento   = $request->input('situacao');
            $contrato->finalidade           = $request->input('finalidade');
            $contrato->ativo = true;

            $contrato->ativo                = $request->input('ativo');

            $contrato->dt_revogado = '2016-01-01';
            //$validator = \Validator::make($request->all(), $this->validaCadastro());
            //if ($validator->fails()) {
            //    return JSONUtils::returnDanger('Problema de validação verifique os campos e tente novamente.', $validator->errors()->all());   
            //}

            $contrato->save();
            return JSONUtils::returnSuccess($contrato->nr_contrato .' alterada com sucesso.', $contrato);
        }catch(Exception $e){
            return JSONUtils::returnDanger('Problema de acesso à base de dados.', $e);
        }
    }

    public function destroy($id){
        try{
            $contrato = Contrato::find($id);
            //$contrato->delete();
            $contrato->ativo = false;

            $contrato->save();

            return JSONUtils::returnSuccess('Item deletado com sucesso.', $contrato);
        }catch(Exception $e){
            return JSONUtils::returnDanger('Problema de acesso à base de dados.',$e);
        }
    }

    public function busca(Request $request)
    {
    	try{
	    	$input = $request->all();

	    	$busca = Contrato::where($input['coluna'],'like', $input['valor'].'%')
	    					->orderBy('id', 'asc')
	    					->get();
            
            $return = array();

            foreach ($busca as $key => $value) {
                $cVO = new AdministrarVO(Contrato::find($value->id));
                $return[] = $cVO;
            }

            //$return[] = $cVO;
	    	return JSONUtils::returnSuccess('Consulta realizada com sucesso.', $return);
	    } catch(Exception $e){
    		return JSONUtils::returnDanger('Problema de acesso à base de dados.',$e);
    	}	
    }

    public function createMovimento(Request $request)
    {
        try{
            $contrato = new Movimentacao();
            $contrato->id_imovel        = $request->input('id_imovel');            
            $contrato->id_contrato      = $request->input('id_contrato');
            $contrato->valor            = $request->input('valor');
            $contrato->dt_movimentacao  = $request->input('data');
            $contrato->mes              = $request->input('mes');
            $contrato->ano              = $request->input('ano');
            $contrato->descricao        = '';
            $contrato->movimentacoes    = json_encode($request->input('movimentacoes'));

            $validator = \Validator::make($request->all(), $this->validaMovimento());
            if ($validator->fails()) {
             return JSONUtils::returnDanger('Problema de validação verifique os campos e tente novamente.', $validator->errors()->all());   
            }

            $contrato->save();

            $recibo = new Recibo();
            $recibo->id_proprietario    = $request->input('id_proprietario');
            $recibo->id_inquilino       = $request->input('id_inquilino');
            $recibo->id_usuario         = $request->input('id_usuario');
            $recibo->id_imovel          = $request->input('id_imovel');  
            $recibo->id_movimentacao    = $contrato->id;
            $recibo->valor              = $request->input('valor');
            $recibo->dt_emissao         = $request->input('data');
            $recibo->mes                = $request->input('mes');
            $recibo->ano                = $request->input('ano');
            $recibo->descricao          = '';
            $recibo->ativo              = true;

            $recibo->save();

            return JSONUtils::returnSuccess('Movimento cadastrado com sucesso.', $contrato);

        }catch(Exception $e){
            return JSONUtils::returnDanger('Problema de acesso à base de dados.', $e);
        }
    }

    public function validaMovimento()
    {
        return [
            'valor' => 'numeric|min:0',
        ];
    }

    // Só pra não perder o método
    public function movimentacao($id)
    {
        try{

            $movimentacao  = \DB::select("
                SELECT
                    c.*,
                    p.nm_pessoa as proprietario, 
                    p.email as email_proprietario, 
                    p.nr_telefone as telefone_proprietario,
                    p2.nm_pessoa as inquilino, 
                    p2.email as email_inquilino,
                    p2.nr_telefone as telefone_inquilino,
                    i.titulo_anuncio, 
                    i.endereco, 
                    i.tp_imovel, 
                    i.situacao_imovel,
                    i.valor,
                    tp.titulo as tipo
                FROM 
                    contrato_aluguels c 
                INNER JOIN 
                    imovels as i on c.id_imovel = i.id 
                RIGHT JOIN 
                    tp_imovels as tp on i.tp_imovel = tp.id
                INNER JOIN 
                    pessoas as p on p.id = (
                        SELECT 
                            id 
                        FROM 
                            pessoas 
                        WHERE
                            tp_pessoa = 'PRO' 
                        AND id = c.id_proprietario
                    ) 
                INNER JOIN 
                    pessoas as p2 on p2.id = (
                        SELECT 
                            id 
                        FROM
                            pessoas 
                        WHERE 
                            tp_pessoa = 'INQ' 
                        AND id = c.id_inquilino
                    ) 
                WHERE 
                    c.id=".$id
                );

            return JSONUtils::returnSuccess('Consulta realizada com sucesso.', $movimentacao);
        }catch(Exception $e){
            return JSONUtils::returnDanger('Problema de acesso à base de dados.',$e);
        }
    }    
}
