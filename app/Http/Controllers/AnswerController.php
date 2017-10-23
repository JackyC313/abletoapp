<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Question;
use App\User;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Question $question)
    {
        $question_id = $question->id;

        // Check for valid option ids
        $validate = [];
        if( (count($question) > 0) && (count($question->options) > 0) ) {
            $validOptions = "";
            foreach($question->options as $option) {
                $validOptions .= $option->id . ',';
            }
            $validOptions = rtrim($validOptions,',');
            $validate['question_'.$question_id] = 'required|in:'.$validOptions;
        }
        $this->validate($request, $validate);

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

        // If it was a valid option id, store in answer
        $answer_id = $request->input('question_'.$question_id);
        $submittedAnswer = new Answer();
        $submittedAnswer->option_id = $answer_id;
        $submittedAnswer->question_id = $question_id;
        $submittedAnswer->user_id = auth()->user()->id;
            
        $submittedAnswer->save();
        return redirect()->action('QuestionController@results', [$question_id]);
    }
}
