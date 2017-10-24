<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Question;
use App\Option;

class ProductionDataSeeder extends Seeder
{
    /**
     * Run the database seeds to populate the DB with some production data to start off with.
     *
     * @return void
     */
    public function run()
    {
        // Clear DB and start fresh
        DB::table('answers')->delete();
        DB::table('options')->delete();
        DB::table('questions')->delete();
        $questions_json_data = File::get('database/dataset/questions.json');
        $questions = json_decode($questions_json_data); 
        $options_json_data = File::get('database/dataset/options.json');
        $options = json_decode($options_json_data); 

        if(is_array($questions) && is_array($options)) {
            foreach($questions as $question_data) {
                $new_question = factory(Question::class)->create([
                    'question' => $question_data->question,
                ]);
                $new_options = [];
                foreach($options as $options_data) {
                    $new_option = factory(Option::class)->create([
                        'option' => $options_data->option,
                        'question_id' => $new_question->id
                    ]);
                    $new_options[] = $new_option;
                }
                $new_question->options()->saveMany($new_options);
            }
        }
    }
}
