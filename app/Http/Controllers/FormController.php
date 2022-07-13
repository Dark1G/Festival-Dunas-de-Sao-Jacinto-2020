<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inscrito;
use App\Evento;
use App\Sessao;
use App\Acompanhante;
use App\Mail\NewSub;

class FormController extends Controller
{
    public function index($event, $day) {
    	$evento = Evento::where("slug", $event)->first();
    	if (!isset($evento)) {
    		return redirect()->away("https://www.festivaldunassaojacinto.pt/");
		}

    	$exist_sessao = Sessao::where("dia", $day)->where("evento_id", $evento->id)->first();
    	if (!isset($exist_sessao)) {
    		return redirect()->away("https://www.festivaldunassaojacinto.pt/");
		}
		$valid_sessao = $this->checkAllSessao($day, $evento);

		$select_sessao = $this->getValidSessao($day, $evento->id, $evento->slug);

    	return view("form.index", compact("event","day", "evento", "select_sessao", "valid_sessao"));

    }
    public function store($event, $day, Request $request) {

		if (!(\Carbon\Carbon::now()->lessThan(\Carbon\Carbon::now()->year(2020)->month(8)->day($day - 1)->hour(22)->minute(00)->second(00)))){
			return ['error' => true, 'mensagem' => 'Inscrições encerradas para dia '.$day.'!'];
		}

    	$evento = Evento::where("slug", $event)->first();
    	if (!isset($evento)) {
    		return back();
    	}

    	$exist_sessao = Sessao::where("dia", $day)->where("evento_id", $evento->id)->first();
    	if (!isset($exist_sessao)) {
    		return back();
		}
		
		$this->validate($request, [
			'nome' => "required",
			'email' => "required",
			'data_nascimento' => "required",
			'documento_id' => "required",
			'morada' => "required"
		],[
			'nome.required' => "Campo obrigatório!",
			'email.required' => "Campo obrigatório!",
			'data_nascimento.required' => "Campo obrigatório!",
			'documento_id.required' => "Campo obrigatório!",
			'morada.required' => "Campo obrigatório!"
		]);

		if ($evento->slug == 'canoagem') {
			$this->validate($request, [
				'nome_acompanhante' => "required",
				'data_nascimento_acompanhante' => "required",
				'documento_id_acompanhante' => "required",
				'morada_acompanhante' => "required"
			], [
				'nome_acompanhante.required' => "Campo obrigatório!",
				'data_nascimento_acompanhante.required' => "Campo obrigatório!",
				'documento_id_acompanhante.required' => "Campo obrigatório!",
				'morada_acompanhante.required' => "Campo obrigatório!"
			]);
		}

		$sessao = Sessao::find($request->sessao);
		$validNumber = ($evento->slug == 'canoagem') ? 5 : 6;
		if (isset($sessao->inscritos)) {
			if ($validNumber <= $sessao->inscritos->count()) {
				return ['error' => true, 'mensagem' => 'Alcançado limite de inscritos para esta sessão!'];
			}
		}
		
		//FAZER ESTA VALIDAÇÃO - Feito acho eu!

		$user_exist = Inscrito::where("email", $request->email)->exists();
		if ($user_exist) {

			$user=Inscrito::where("email", $request->email)->first();
			$exist_user_event = $user->sessao()->where('evento_id', $evento->id)->first();
			if (isset($exist_user_event)) {
				return ['error' => true, 'mensagem' => 'Já está inscrito para o dia '.$exist_user_event->dia. ' para '.$exist_user_event->evento->nome.'. Lembramos que independentemente do dia e da sessão só poderá ir uma vez a cada actividade.'];
			}

			$user->sessao()->attach($sessao->id);

			if (isset($request->nome_acompanhante)) {
				$acompanhante = Acompanhante::create([
					'nome' => $request->nome_acompanhante,
					'data_nascimento' => $request->data_nascimento_acompanhante,
					'documento_id' => $request->documento_id_acompanhante,
					'morada' => $request->morada_acompanhante,
					'crianca' => isset($request->crianca) ? $request->crianca : 0
				]);

				if (!$acompanhante) {
					return ['error' => true, 'mensagem' => 'Ocorreu um erro ao fazer a sua inscrição!'];
				}

				$user->acompanhante()->attach($acompanhante->id, ['sessao_id' => $sessao->id]);
			}			

		} else {
			$user = Inscrito::create([
				'nome' => $request->nome,
				'data_nascimento' => $request->data_nascimento,
				'documento_id' => $request->documento_id,
				'morada' => $request->morada,
				'email' => $request->email
			]);
			
			if (!$user) {
				return ['error' => true, 'mensagem' => 'Ocorreu um erro ao fazer a sua inscrição!'];
			}

			$user->sessao()->attach($sessao->id);

			if (isset($request->nome_acompanhante)) {
				$acompanhante = Acompanhante::create([
					'nome' => $request->nome_acompanhante,
					'data_nascimento' => $request->data_nascimento_acompanhante,
					'documento_id' => $request->documento_id_acompanhante,
					'morada' => $request->morada_acompanhante,
					'crianca' => isset($request->crianca) ? $request->crianca : 0
				]);

				if (!$acompanhante) {
					return ['error' => true, 'mensagem' => 'Ocorreu um erro ao fazer a sua inscrição!'];
				}

				$user->acompanhante()->attach($acompanhante->id, ['sessao_id' => $sessao->id]);
			}
		}

		//FAZER O ENVIO DO EMAIL (NOTIFY)
		\Mail::to($user->email)->send(new NewSub($user->nome, $sessao->horas, $evento->nome, $sessao->dia));
		return ['error' => false, 'mensagem' => 'Incrição feita com sucesso! Enviámos um email para o endereço email '.$user->email];
	}
	
	protected function getValidSessao($day, $evento_id, $evento){
		$validNumber = ($evento == 'canoagem') ? 5 : 6;
		$temp = collect();
		$select_sessao = Sessao::where("dia", $day)->where("evento_id", $evento_id)->get();
		foreach ($select_sessao as $key => $sessao) {
			$sessao->valid = $sessao->inscritos->count() < $validNumber;
			$temp->push($sessao);
		}
		return $temp;
		
	}

	protected function checkAllSessao($day, $evento) {
		$all = Sessao::where('dia', $day)->where('evento_id', $evento->id)->get();
		$collection = collect();
		$validNumber = ($evento->slug == 'canoagem') ? 5 : 6;
		foreach ($all as $sessao) {
			$collection->push($validNumber <= $sessao->inscritos->count());
		}
		$arr = $collection->toArray();
		$valid = count(array_unique($arr)) === 1 && end($arr) === true;
		return $valid;
	}

}
