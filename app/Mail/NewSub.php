<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Inscrito;

class NewSub extends Mailable
{
    use Queueable, SerializesModels;
    public $nome;
    public $evento;
    public $dia;
    public $sessao;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($nome, $sessao, $evento, $dia)
    {
        $this->nome = $nome;
        $this->evento = $evento;
        $this->dia = $dia;
        $this->sessao = $sessao;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Inscrição " . $this->evento)->markdown("emails.subs")->with([
            'nome' => $this->nome,
            'evento' => $this->evento,
            'dia' => $this->dia,
            'sessao' => $this->sessao
        ]);
    }
}
