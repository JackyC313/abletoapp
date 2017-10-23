<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Charts;
use App\Answer;
use App\Question;
use App\User;

class QuestionController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($question_id)
    {
        // Check if user has an answer already for this question
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        $questions_id_answered = [];
        $questions_id_answered = $user->answers->map(function($answer) {
            return $answer->question->id;
        })->toArray();

        if(in_array($question_id, $questions_id_answered)) {
            return redirect('/dashboard')->with('error', 'You have already answered that question');
        }

        $question = Question::find($question_id);
        return view('pages.question_index')->with('question', $question);
    }

    /**
     * Display a listing of the question results.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function results($question_id)
    {
        // Check if user has an answer already for this question
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        $questions_id_answered = [];
        $questions_id_answered = $user->answers->map(function($answer) {
            return $answer->question->id;
        })->toArray();

        if(!in_array($question_id, $questions_id_answered)) {
            return redirect('/question/'.$question_id.'/index')->with('error', 'You have not yet answered this question, why not answer it first?');
        }

        $question = Question::find($question_id)->load('options.answers');
        $optionsArray = array();
        $optionsArray = $question->options->reduce(function($optionsArray, $option) {
            $optionsArray["count"][$option->id] = count($option->answers); 
            $optionsArray["name"][$option->id] = $option->option; 
            return $optionsArray;
        });

        // Chart Code
        $chart = Charts::create('bar', 'google')
            // Setup the chart settings
            ->title('Question Results for \"' . $question->question . '\"')
            // A dimension of 0 means it will take 100% of the space
            ->dimensions(0, 400) // Width x Height
            // This defines a preset of colors already done:)
            // You could always set them manually
            ->colors(['#F44336', '#FFC107', '#CCFFCC', '#2196F3'])
            // Setup the datasets labels & values
            ->labels(array_values($optionsArray["name"]))
            ->values(array_values($optionsArray["count"]))
            // Setup what the values mean
            ->elementLabel("People")
            ->xAxisTitle('Answer options')
            ->yAxisTitle('Number of people answered');
            
        $blade_data = array(
            'question' => $question,
            'answer_counts' => $optionsArray["count"],
            'chart' => $chart,
        );

        return view('pages.question_result')->with($blade_data);
    }
}
