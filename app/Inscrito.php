<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Sessao;
use App\Acompanhante;

class Inscrito extends Model 
{
    protected $fillable = ['nome', 'data_nascimento', 'documento_id', 'morada', 'email', 'pessoas'];

    public function sessao() {
    	return $this -> belongsToMany(Sessao::class,"inscrito_sessao");
    }

    public function acompanhante() {
        return $this->belongsToMany(Acompanhante::class, "inscrito_acompanhante")->withPivot('sessao_id');
    }
}
