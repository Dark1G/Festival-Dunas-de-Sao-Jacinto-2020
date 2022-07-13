<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Evento;
use App\Inscrito;

class Sessao extends Model
{
    protected $fillable=['dia', 'horas', 'horas_slug', 'evento_id'];

    public function evento(){
    	return $this -> belongsTo(Evento::class);
    }
    public function inscritos(){
        return $this -> belongsToMany(Inscrito::class,"inscrito_sessao");
    } 
}
