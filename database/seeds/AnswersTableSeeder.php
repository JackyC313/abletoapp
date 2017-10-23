<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class AnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Dev Note: need to loop individually or else constraint checks are not updated upon new entries
        for($i = 0; $i <= 10; $i++) {
            factory(App\Answer::class)->create();
        };
    }
}
