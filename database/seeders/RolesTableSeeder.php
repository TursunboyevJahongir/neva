<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # Админ
        Role::create([
            'name' => 'Админ',
            'permissions' => [
                'admin' => [
                    'view' => true
                ],
                'users' => [
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true
                ],
                'products' => [
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true
                ],
                'categories' => [
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true
                ],
                'shops' => [
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true
                ],
                'attribute' => [
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true
                ],
                'catalog' => [
                    'view' => true
                ],
                'orders' => [
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true
                ],
                'news' => [
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true
                ]
            ]
        ]);

        # Менеджер
        Role::create([
            'name' => 'Менеджер',
            'permissions' => [
                'products' => [
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true
                ],
                'categories' => [
                    'view' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true
                ],
            ]
        ]);

        # Магазин
        Role::create([
            'name' => 'Магазин',
            'permissions' => [
                'orders' => [
                  'view' => true
                ],
                'merchants' => [
                    'view' => true
                ]
            ]
        ]);
    }
}
