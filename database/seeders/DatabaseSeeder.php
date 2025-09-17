<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\DemoSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AuthorSeeder::class,
            PublisherSeeder::class,
            LanguageSeeder::class,
            BookSeeder::class,
            RoleBasedUsersSeeder::class,
            DemoSeeder::class,
            BulkBooksAndLoansSeeder::class,
        ]);
    }
}
