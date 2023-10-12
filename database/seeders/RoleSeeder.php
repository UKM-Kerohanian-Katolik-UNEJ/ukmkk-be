<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Role::create(["name" => "admin"]);
        Role::create(["name" => "bph"]);
        Role::create(["name" => "kerohanian"]);
        Role::create(["name" => "humas"]);
        Role::create(["name" => "litbang"]);
        Role::create(["name" => "kemhas"]);
        Role::create(["name" => "kwu"]);
    }
}
