<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class QuestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Question::class, 5)
            ->create()
            ->each(function ($question) {
                $question->options()
                    ->saveMany(factory(App\Option::class, 4)->make());
            }
        );

    }
}
