<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Inscrito;

class Acompanhante extends Model
{
    protected $fillable = ['nome', 'data_nascimento', 'documento_id', 'morada', 'crianca'];

    public function inscrito()
    {
        return $this->belongsToMany(Inscrito::class, "inscrito_acompanhante");
    } 
    
}
