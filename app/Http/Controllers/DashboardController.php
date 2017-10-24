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
     * Shows a list of questions not yet answered first
     * Then shows a list of questions already answered
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // TODO: Move this outside to a config area
        $itemsPerPage = 5;

        // Init to Page 1
        if(!empty($request->query('unansweredPage'))) {
            $questions_unanswered_page = $request->query('unansweredPage');
        } else {
            $questions_unanswered_page = 1;
        }
        if(!empty($request->query('answeredPage'))) {
            $questions_answered_page = $request->query('answeredPage');
        } else {
            $questions_answered_page = 1;
        }

        $user_id = auth()->user()->id;
        $user = User::find($user_id);

        // Get an array of all questions not yet answered by the user
        $questions_all = Question::get();
        $questions_unanswered = [];
        $questions_unanswered = $questions_all->reject(function($question) use($user_id) { 
            $user_answers = $question->answers->where('user_id', $user_id);
            return count($user_answers) > 0;
        });

        $unanswered_pagination = new \Illuminate\Pagination\LengthAwarePaginator(
            $questions_unanswered->forPage($questions_unanswered_page, $itemsPerPage), 
            $questions_unanswered->count(), 
            $itemsPerPage, 
            $questions_unanswered_page,
            ['path'=>url('/dashboard'), 'pageName' => 'unansweredPage', 'query' => ['answeredPage'=>$questions_answered_page] ]
        );
        
        // Get an array of all questions already answered by the user
        $questions_answered = [];
        $questions_answered = $user->answers->map(function($answer) {
            return $answer->question;
        });

        $answered_pagination = new \Illuminate\Pagination\LengthAwarePaginator(
            $questions_answered->forPage($questions_answered_page, $itemsPerPage), 
            $questions_answered->count(), 
            $itemsPerPage, 
            $questions_answered_page,
            ['path'=>url('/dashboard'), 'pageName' => 'answeredPage', 'query' => ['unansweredPage'=>$questions_unanswered_page]]
        );

        $blade_data = array(
            'answered_pagination' => $answered_pagination,
            'unanswered_pagination' => $unanswered_pagination
        );

        return view('pages.dashboard')->with($blade_data);
    }
}
