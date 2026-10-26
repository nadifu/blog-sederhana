<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user bawaan untuk login
        User::firstOrCreate(
            ['email' => 'admin@blog.com'],
            [
                'name' => 'Admin Blog',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );

        // Contoh postingan blog awal
        if (Post::count() === 0) {
            Post::create([
                'title' => 'Selamat Datang di Blog Sederhana via Docker',
                'content' => 'Aplikasi web Laravel ini berhasil dikontainerisasi menggunakan Docker dan Docker Compose, lalu di-deploy ke Microsoft Azure Virtual Machine.',
                'author' => 'Admin',
            ]);

            Post::create([
                'title' => 'Implementasi Multi-Stage Build & Internal Network',
                'content' => 'Dockerfile menggunakan multi-stage build (Node.js untuk frontend, Composer untuk vendor PHP, dan PHP-FPM 8.4 Alpine sebagai runtime). Database MySQL 8.4 diamankan dalam internal network.',
                'author' => 'Praktikan Komputasi Awan',
            ]);
        }
    }
}
