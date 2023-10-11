<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PengurusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            "name" => "Admin",
            "email" => "adminkatolik@unej.ac.id",
            "password" => bcrypt("password")
        ])->assignRole("admin");

        User::create([
            "name" => "BPH",
            "email" => "bphkatolik@unej.ac.id",
            "password" => bcrypt("password")
        ])->assignRole("bph");

        User::create([
            "name" => "Humas",
            "email" => "humaskatolik@unej.ac.id",
            "password" => bcrypt("password")
        ])->assignRole("humas");

        User::create([
            "name" => "Kerohanian",
            "email" => "kerohaniankatolik@unej.ac.id",
            "password" => bcrypt("password")
        ])->assignRole("kerohanian");

        User::create([
            "name" => "Litbang",
            "email" => "litbangkatolik@unej.ac.id",
            "password" => bcrypt("password")
        ])->assignRole("litbang");

        User::create([
            "name" => "Kemhas",
            "email" => "kemhaskatolik@unej.ac.id",
            "password" => bcrypt("password")
        ])->assignRole("kemhas");

        User::create([
            "name" => "KWU",
            "email" => "kwukatolik@unej.ac.id",
            "password" => bcrypt("password")
        ])->assignRole("kwu");
    }
}
