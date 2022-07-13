@extends('layouts.app')

@section('content')
<section class="text-center">
    <img src="{{asset('img\AF1.jpg')}}" class="img-fluid" alt="Responsive image">
</section>
<section class="jumbotron text-center">
    <div class="container">
        <h2>{{$evento->nome}} - Dia {{$day}}</h2>
        <p class="lead text-muted">Formulário de inscrição.</p>
    </div>
</section>
<section class="album py-2 mb-4 bg-light">
    <div class="container">
        @if ($evento->slug=="sup")
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-primary" role="alert">
                        <h4 class="alert-heading">Informações adicionais</h4>
                        <hr>
                        - As inscrições encerram às 22h do dia {{$day-1}} <br>
                        - Período de cada sessão: 30 minutos <br>
                        - Lotação máxima por sessão: 6 pessoas <br>
                        - A inscrição é individual <br>
                        - Crianças com menos de 7 anos podem ir juntamente com um adulto (partilham a mesma prancha)
                    </div>
                </div>
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="alert alert-primary" role="alert">
                        <h4 class="alert-heading">Informações adicionais</h4>
                        <hr>
                        - As inscrições encerram às 22h do dia {{$day-1}} <br>
                        - Período de cada sessão: 30 minutos <br>
                        - Lotação máxima por sessão: 10 pessoas (5 grupos de 2 pessoas) <br>
                        - A inscrição terá, obrigatoriamente, de ser realizada aos pares
                    </div>
                </div>
            </div>
        @endif
        <div class="row py-2 justify-content-center">
            <div class="col-md-8">
                <div class="row">
					<div class="col mt-4 mb-3">
						<h4 class="alert-heading mb-0">Preencha os seguintes dados</h4>
						<small>* Preenchimento Obrigatório</small>
					</div>
                </div>
                @if (\Carbon\Carbon::now()
                        ->lessThan(\Carbon\Carbon::now()
                        ->year(2020)
                        ->month(8)
                        ->day($day-1)
                        ->hour(22)
                        ->minute(00)
                        ->second(00))
                        && !$valid_sessao
                    )
                    <form-subscriber :counter="50" :list-hours="{{ $select_sessao }}" evento="{{ $evento->slug }}" route="{{ route('form.store', ['event'=>$event, 'day'=>$day]) }}"></form-subscriber>
                @else
                    <div class="alert alert-warning" role="alert">
                        <h4 class="alert-heading text-center">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-exclamation-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                            </svg>
                            Inscrições encerradas para {{$evento->nome}} - Dia {{$day}}!
                        </h4>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
