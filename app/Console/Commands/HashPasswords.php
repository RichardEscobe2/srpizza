<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

#[Signature('app:hash-passwords')]
#[Description('Hash all existing plain text passwords in the usuarios table')]
class HashPasswords extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to hash existing passwords...');
        
        $usuarios = DB::table('usuarios')->get();
        $count = 0;

        foreach ($usuarios as $usuario) {
            // Check if password already hashed (bcrypt hashes start with $2y$)
            if (!str_starts_with($usuario->contrasena, '$2y$')) {
                DB::table('usuarios')->where('id_usuario', $usuario->id_usuario)->update([
                    'contrasena' => Hash::make($usuario->contrasena)
                ]);
                $count++;
            }
        }

        $this->info("Successfully hashed passwords for $count users.");
    }
}
