<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Evento;
use App\Sessao;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	$canoagem = Evento::create([
            'nome' => "Canoagem", 
            'slug' => Str::slug("Canoagem")
        ]);

        $sup = Evento::create([
            'nome' => "Stand Up Paddle", 
            'slug' => Str::slug("sup")
        ]);

        $time = ['14h:00m','14h:30m','15h:00m','15h:30m',
        '16h:00m','16h:30m','17h:00m','17h:30m',
        '18h:00m','18h:30m','19h:00m'];
        
        for ($i = 0; $i < count($time);$i++) {

            if (!isset($time[$i+1])) {
                continue;
            }

            Sessao::create([
                'dia' => 21, 
                'horas' => "{$time[$i]} - {$time[$i+1]}",
                'horas_slug' => str_replace(':', '',"{$time[$i]}-{$time[$i+1]}"),
                'evento_id' => $canoagem->id
            ]);
        }

        for ($i = 0; $i < count($time);$i++) {

            if (!isset($time[$i+1])) {
                continue;
            }

            Sessao::create([
                'dia' => 22, 
                'horas' => "{$time[$i]} - {$time[$i+1]}",
                'horas_slug' => str_replace(':', '',"{$time[$i]}-{$time[$i+1]}"),
                'evento_id' => $canoagem->id
            ]);
        }

        for ($i = 0; $i < count($time);$i++) {

            if (!isset($time[$i+1])) {
                continue;
            }

            Sessao::create([
                'dia' => 23, 
                'horas' => "{$time[$i]} - {$time[$i+1]}",
                'horas_slug' => str_replace(':', '',"{$time[$i]}-{$time[$i+1]}"),
                'evento_id' => $canoagem->id
            ]);
        }

        for ($i = 0; $i < count($time);$i++) {

            if (!isset($time[$i+1])) {
                continue;
            }

            Sessao::create([
                'dia' => 22, 
                'horas' => "{$time[$i]} - {$time[$i+1]}",
                'horas_slug' => str_replace(':', '',"{$time[$i]}-{$time[$i+1]}"),
                'evento_id' => $sup->id
            ]);
        }

        for ($i = 0; $i < count($time);$i++) {

            if (!isset($time[$i+1])) {
                continue;
            }

            Sessao::create([
                'dia' => 23, 
                'horas' => "{$time[$i]} - {$time[$i+1]}",
                'horas_slug' => str_replace(':', '',"{$time[$i]}-{$time[$i+1]}"),
                'evento_id' => $sup->id
            ]);
        }
    }
}
