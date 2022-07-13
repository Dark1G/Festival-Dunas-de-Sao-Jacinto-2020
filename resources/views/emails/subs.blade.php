@component('mail::message')
{{-- Olá, {{$newsub->nome}} --}}
{{-- Debug --}}
<p>{{ greetings_pt(\Carbon\Carbon::now()) }}, {{ $nome }}</p>
<p><b>Acabou de se inscrever com sucesso no Festival Dunas para a actividade {{ $evento }}, sessão {{ $sessao }} no dia {{ $dia }}.</b></p>
<p>Para dúvidas ou questões por favor contacte:
    <br>E-mail: <a href="mailto:desporto@cm-aveiro.pt">desporto@cm-aveiro.pt</a>
    <br>Telemóvel: <a href="tel:924089103">924 089 103</a>
</p>
@endcomponent