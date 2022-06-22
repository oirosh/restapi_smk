<?php

namespace Database\Seeders;

use App\Models\Profil;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Profil::create([
            "nama_pendidikan" => "SMK",
            "nama_sekolah" => "Ma'arif Walisongo Kajoran",
            "slogan" => "Smart, Religious, Profesional",
            "singkatan" => "SMKW9",
            "logo" => "profil/logo.png",
            "ikon" => "profil/ikon.png",
            "npsn" => "69786398",
            "sambutan" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga architecto, delectus, iusto placeat neque commodi consequatur quas, laborum maiores ratione veniam assumenda sit! Doloribus et, aliquid exercitationem est inventore dolor!",
            "perkenalan" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorum cumque obcaecati temporibus. Soluta sequi, iure est quas iste nulla sit."
        ]);

        User::create([
            'nama' => 'Agas arapi',
            'email' => 'arp46301@gmail.com',
            'password' => Hash::make('agagistarukman'),
            'level' => 'admin'
        ]);
    }
}
