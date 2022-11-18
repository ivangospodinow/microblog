<?php

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $data = [
            [
                'username' => 'admin',
                'password' => '$2y$10$PLa.kZr5CoMRqOv2on2ZX.Hq2zJxyhfw.bDtYDPoUf.QcHnFzTNCO',
            ],
        ];

        $posts = $this->table('users');
        $posts->insert($data)
            ->saveData();
    }
}
