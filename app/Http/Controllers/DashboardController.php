<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Question;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        $questions_answered = [];
        $questions_answered = $user->answers->map(function($answer) {
            return $answer->question;
        });

        $questions_all = Question::get();
        $questions_unanswered = [];
        $questions_unanswered = $questions_all->reject(function($question) use($user_id) { 
            $user_answers = $question->answers->where('user_id', $user_id);
            return count($user_answers) > 0;
        });
        
        $blade_data = array(
            'questions_answered' => $questions_answered,
            'questions_unanswered' => $questions_unanswered
        );

        return view('pages.dashboard')->with($blade_data);
    }
}
