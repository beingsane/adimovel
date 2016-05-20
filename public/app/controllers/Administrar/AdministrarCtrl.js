(function(){
	'use strict';

	angular.module('adimovelApp').controller('AdministrarCtrl', Administrar);

	Administrar.injector = ["Request", "URL", "$routeParams"];

	function Administrar(Request, URL, $routeParams){

		var vm = this;
		var user = localStorage.getItem('user');

		if(user){
			vm.user = JSON.parse(user);
		}

		vm.colunas = [
<<<<<<< HEAD
	/*		{
				value 	: 'imovel',
				name : 'Imovel'	
			},
			{
				value 	: 'proprietario',
				name : 'Proprietario'
			},
			{
				value 	: 'inquilino',
				name : 'Inquilino'
			},
	*/		{
				value 	: 'nr_contrato',
				name : 'Numero Contrato'
			}//,
	/*		{
				value 	: 'finalidade',
				name : 'Finalidade'
			} */
=======
			// {
			// 	value 	: 'id_imovel',
			// 	name : 'Imovel'	
			// },
			// {
			// 	value 	: 'id_proprietario',
			// 	name : 'Proprietario'
			// },
			// {
			// 	value 	: 'id_inquilino',
			// 	name : 'Inquilino'
			// },
			{
				value 	: 'nr_contrato',
				name : 'Numero Contrato'
			},
			// {
			// 	value 	: 'finalidade',
			// 	name : 'Finalidade'
			// }
>>>>>>> fa5d8e1a7c7be467d7966294e727450d9a58c18b
		];


		active();

		setTimeout(function(){
			$("#coluna").find('option:first').remove();
		}, 500);

		function active(){
			var functions = [getMovimentos(), getMovimento()];
		}

		/*
		//  Cadastro do imóvel em ADMINISTRAR
		*/
		vm.setContrato = function(){
			var data = {
				imovel 	 		: $("#imovel").val(),
				proprietario 	: $("#proprietario").val(),
				inquilino 		: $("#inquilino").val(),
				nr_contrato 	: $("#nr_contrato").val(),
				dt_inicio 		: $("#dt_inicio").val(),
				dt_vencimento 	: $("#dt_vencimento").val(),
				valor 	 		: $("#valor").val(),
				situacao 	 	: $("#situacao").val(),
				finalidade 		: $("#finalidade").val()
			}
			Request.set("administrar", data).then(function(res){
				var alerta = new alert();
				if(res[0].codigo == "SUCCESS"){
					alerta.success(res[0].mensagem);
				}else if(res[0].codigo == "DANGER"){
					alerta = new alert();
					alerta.danger(res[0].mensagem);
				}
				return res;
			});
		}


		vm.update = function(){
			var data = {
				imovel 	 		: $("#imovel").val(),
				proprietario 	: $("#proprietario").val(),
				inquilino 		: $("#inquilino").val(),
				nr_contrato 	: $("#nr_contrato").val(),
				dt_inicio 		: $("#dt_inicio").val(),
				dt_vencimento 	: $("#dt_vencimento").val(),
				valor 	 		: $("#valor").val(),
				situacao 	 	: $("#situacao").val(),
				finalidade 	 	: $("#finalidade").val(),
				ativo 			: $("#ativo").val()
			};

			Request.put("administrar/" + $routeParams.slug, data)
				.then(function(res){
					console.log(res, data);
					var alerta = new alert();
					if(res[0].codigo == "SUCCESS"){
						alerta.success(res[0].mensagem);
					}else if(res[0].codigo == "DANGER"){
						alerta = new alert();
						alerta.danger(res[0].mensagem);
					}
					return res;
			});

		}

		vm.deleta = function(){

			var id = event.srcElement.attributes[0].value;
			var tr = $(event.srcElement).closest('tr');
			Request.destroy('administrar/' + id)
				.then(function(res){
					var alerta = new alert();
					if(res[0].codigo == "SUCCESS"){
						alerta.successDeleta(tr, res[0].mensagem);
					}else if(res[0].codigo == "DANGER"){
						alerta = new alert();
						alerta.danger(res[0].mensagem);
					}					
					return res;
			})
		}

		vm.busca = function(){
			var data = {
				valor : $("#busca").val(),
				coluna: $("#coluna").val()
			}

			Request.set('busca/administrar', data).then(function(res) {
				angular.forEach(res[0].objeto, function(value, key) {
					(value.finalidade == "VEN") ? value.finalidade = "Venda" : value.finalidade = "Aluguel";
					(value.ativo == true) ? value.ativo = "Ativo" : value.ativo = "Inativo";
				});
				vm.movimentos = res[0].objeto;
			});

		}

		function getMovimentos(){
			Request.get("administrar").then(function(res){
				angular.forEach(res[0].objeto, function(value, key) {
					(value.finalidade == "VEN") ? value.finalidade = "Venda" : value.finalidade = "Aluguel";
					(value.situacao_pagamento == true) ? value.situacao_pagamento = "Pago" : value.situacao_pagamento = "Pendente";
				});
				
				vm.movimentos = res[0].objeto;
			});
		}

		function getMovimento(){
			if($routeParams.slug !== undefined){
				Request.get("administrar/" + $routeParams.slug)
					.then(function(res){
						var value = res[0].objeto;
<<<<<<< HEAD
+						(value.finalidade == "VEN") ? value.finalidade = "Venda" : value.finalidade = "Aluguel";
+						(value.situacao_pagamento == true) ? value.situacao_pagamento = "Pago" : value.situacao_pagamento = "Pendente";
=======
						(value.finalidade == "VEN") ? value.finalidade = "Venda" : value.finalidade = "Aluguel";
						(value.situacao_pagamento == true) ? value.situacao_pagamento = "Pago" : value.situacao_pagamento = "Pendente";
>>>>>>> fa5d8e1a7c7be467d7966294e727450d9a58c18b
						vm.movimento = res[0].objeto;
						
				});
			}
		}

		/*
		//  Cadastro do movimento de descontos e impostos do imovel
		*/
		vm.setMovimentoVenda = function(){

			var movimentacao = { 
				movimento : 'DESCONTO', 
				valor : $("#desconto").val(), 
				credito : true 
			};
			
			var data = {
				id_proprietario	: $("#id_proprietario").val(),
				id_inquilino	: $("#id_inquilino").val(),
				id_usuario		: vm.user.id,
				id_contrato 	: $("#id_contrato").val(),
				valor 	 		: $("#total").text(),
				movimentacoes	: movimentacao
			}

			
			Request.set("administrar/movimento", data).then(function(res){
				var alerta = new alert();
				if(res[0].codigo == "SUCCESS"){
					alerta.success(res[0].mensagem);
				}else if(res[0].codigo == "DANGER"){
					alerta = new alert();
					alerta.danger(res[0].mensagem);
				}
				return res;
			});
			
		}
		
		vm.setMovimentoAluguel = function(){
			
			var movimentacao = {}
			, 	total = $("#total").text();

			if($("#movimento").val() == "Custo"){
				movimentacao = {
					movimento : "CUSTO",
					custos 	  : {},
					credito   : true,
				};
				var custos = [];
				var juros  = 0;
				$(".custo-input").each(function(index, el) {
					var custo = {
						custo : $(el).find(".custo-campo").val(),
						valor : $(el).find(".vlr").val(),
						descricao : $(el).find(".desc").val()
					}
					juros = juros + custo.valor;
					custos.push(custo);
				});

				movimentacao.custos = custos;

				total = total - juros;

				if(total <= 0){
					total = 0;
				}

			} else {
				movimentacao = { 
					movimento : "DESCONTO", 
					valor : $("#desconto").val(), 
					credito : true 
				};
			}
			

			var movimentacao = {}
			, 	total = $("#total").text();

			if($("#movimento").val() == "Custo"){
				movimentacao = {
					movimento : "CUSTO",
					custos 	  : {},
					credito   : true,
				};
				var custos = [];
				var juros  = 0;
				$(".custo-input").each(function(index, el) {
					var custo = {
						custo : $(el).find(".custo-campo").val(),
						valor : $(el).find(".vlr").val(),
						descricao : $(el).find(".desc").val()
					}
					juros = juros + custo.valor;
					custos.push(custo);
				});

				movimentacao.custos = custos;

				total = total - juros;

				if(total <= 0){
					total = 0;
				}

			} else {
				movimentacao = { 
					movimento : "DESCONTO", 
					valor : $("#desconto").val(), 
					credito : true 
				};
			}
			
			var data = {
				id_contrato 	: $("#id_contrato").val(),
<<<<<<< HEAD
				proprietario : $("#id_proprietario").val(),
				valor 	 		: total,
 				movimentacoes	: movimentacao
			}
=======
				valor 	 		: total,
				movimentacoes	: movimentacao
			}

>>>>>>> fa5d8e1a7c7be467d7966294e727450d9a58c18b
			Request.set("administrar/movimento", data).then(function(res){
				var alerta = new alert();
				if(res[0].codigo == "SUCCESS"){
					alerta.success(res[0].mensagem);
				}else if(res[0].codigo == "DANGER"){
					alerta = new alert();
					alerta.danger(res[0].mensagem);
				}
				return res;
			});
			
		}

		vm.desconto = function(){
			var desconto = $("#desconto").val();
			var valor = $("#valor").val();

			var total = valor - desconto;
			$("#total").html(total);
			$("#valor-total").val(total);
		
		}

		vm.updatePagamento = function(){
			var situacao = $(".btn-aluguel").data('situacao');

			var data = {
				situacao : situacao,
			}

			Request.put("administrar/situacao/"  +vm.movimento.id, data)
				.then(function(res){
					console.log(res, data);
					var alerta = new alert();
					if(res[0].codigo == "SUCCESS"){
						alerta.success(res[0].mensagem);
					}else if(res[0].codigo == "DANGER"){
						alerta = new alert();
						alerta.danger(res[0].mensagem);
					}
					return res;
			});
		}

		vm.change = function(tipo) {
			Request.get("administrar/"+tipo).then(function(res){
				angular.forEach(res[0].objeto, function(value, key) {
					(value.finalidade == "VEN") ? value.finalidade = "Venda" : value.finalidade = "Aluguel";
					(value.situacao_pagamento == true) ? value.situacao_pagamento = "Pago" : value.situacao_pagamento = "Pendente";
				});
				
				vm.movimentos = res[0].objeto;
			});
		}

		vm.updatePagamento = function(){
			var situacao = $(".btn-aluguel").data('situacao');

			var data = {
				situacao : situacao,
			}

			Request.put("administrar/situacao/" + vm.movimento.id, data)
				.then(function(res){
					console.log(res, data);
					var alerta = new alert();
					if(res[0].codigo == "SUCCESS"){
						alerta.success(res[0].mensagem);
					}else if(res[0].codigo == "DANGER"){
						alerta = new alert();
						alerta.danger(res[0].mensagem);
					}
					return res;
			});
		}

		vm.change = function(tipo) {
			Request.get("administrar/"+tipo).then(function(res){
				angular.forEach(res[0].objeto, function(value, key) {
					(value.finalidade == "VEN") ? value.finalidade = "Venda" : value.finalidade = "Aluguel";
					(value.situacao_pagamento == true) ? value.situacao_pagamento = "Pago" : value.situacao_pagamento = "Pendente";
				});
				
				vm.movimentos = res[0].objeto;
			});
		}
	}
	
})();