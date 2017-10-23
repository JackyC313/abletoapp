<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Question::class, function (Faker $faker) {
    return [
        'question' => $faker->sentence(5) . "?",
    ];
});

// DEV NOTE: if this is called in batch, constraint checks may not be up to date on the next create
$factory->define(App\Option::class, function (Faker $faker) {
    // Pick a question and associate it
    // Filter to questions that have less than (MaxOptions) options so we don't over do it
    $MaxOptions = 4;
    $questions = App\Question::get()->filter(function ($question) use ($MaxOptions) {
        $options_count = count($question->options);
        return $options_count < $MaxOptions;
    })->pluck('id')->toArray();

    // Create new question if no valid questions are found
    if(!empty($questions)) {
        $fake_question_id = $faker->randomElement($questions);
    } else {
        $fake_question_id = factory(App\Question::class)->create()->id;
    }

    return [
        'option' => $faker->word,
        'question_id' => $fake_question_id,
    ];
});

// DEV NOTE: if this is called in batch, constraint checks may not be up to date on the next create
$factory->define(App\Answer::class, function (Faker $faker) {
    // Pick an option and get it's option id AND question id
    $options = App\Option::pluck('id')->toArray();
    $fake_option_id = $faker->randomElement($options);
    $fake_question_id = App\Option::find($fake_option_id)->question_id;

    // Make sure user hasn't already answered the question
    $users = App\User::whereDoesntHave('answers', function($answer) use ($fake_question_id) {
        $answer->where('question_id', '=', $fake_question_id);
    })->pluck('id')->toArray();

    // Create new user if no valid users are found
    if(!empty($users)) {
        $fake_user_id = $faker->randomElement($users);
    } else {
        $fake_user_id = factory(App\User::class)->create()->id;
    }
    return [
        'question_id' => $fake_question_id,
        'option_id' => $fake_option_id,
        'user_id' => $fake_user_id,
    ];
});
