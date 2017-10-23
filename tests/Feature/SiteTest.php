<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Question;

class SiteTest extends TestCase
{
    /**
     * Test each url
     * Site has the follow different pages
     * 
     * [GET]
     * /                                - index                 [not login protected]
     * /dashboard                       - dashboard             [login protected]
     * /question/{question}/index       - question display      [login protected]
     * /question/{question}/results     - question results      [login protected]
     * 
     * [POST] 
     * /question/{question}/submit      - question submit       [login protected]
     * 
     * @return void
     */
// Route::get('/', 'SiteController@index');
// Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
// Route::get('/question/{question}/index', 'QuestionController@index');
// Route::post('/question/{question}/submit', 'AnswerController@store');
// Route::get('/question/{question}/results', 'QuestionController@results');

    //                                - index                 [not login protected]
    // Index Test while not logged in 
    // Should show welcome msg "Welcome to the AbleTo App" in heading
    public function testIndexNotLoggedIn()
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSeeText('Welcome to the AbleTo App');
    }

    // Index Test while logged in 
    // Should send you to the dashboard
    public function testIndexLoggedIn()
    {
        $user = User::first();
        $this->actingAs($user)
            ->get('/')
            ->assertStatus(302)
            ->assertRedirect('/dashboard');
    }

    // Not Logged in Tests
    // /dashboard                       - dashboard             [login protected]
    // /question/{question}/index       - question display      [login protected]
    // /question/{question}/results     - question results      [login protected]
    // /question/{question}/submit      - question submit       [login protected]
    
    // While not logged in these pages 
    // Should send you to Login page
    public function testDashboardNotLoggedIn()
    {
        $this->get('/dashboard')
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    // While not logged in these pages 
    // Should send you to Login page
    public function testQuestionPageNotLoggedIn()
    {
        $question_id = Question::pluck('id')->first();
        $this->get('/question/'.$question_id.'/index')
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    // While not logged in these pages 
    // Should send you to Login page
    public function testQuestionResultsPageNotLoggedIn()
    {
        $question_id = Question::pluck('id')->first();
        $this->get('/question/'.$question_id.'/results')
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    // While not logged in these pages 
    // Should send you to Login page
    public function testQuestionSubmitPageNotLoggedIn()
    {
        $question_id = Question::pluck('id')->first();
        $this->post('/question/'.$question_id.'/submit')
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    // While not logged in these pages 
    // Should send you to home page (set in app\Exceptions\Handler.php)
    public function testQuestionSubmitPageNotLoggedInGet()
    {
        $question_id = Question::pluck('id')->first();
        $this->get('/question/'.$question_id.'/submit')
            ->assertStatus(302)
            ->assertRedirect('/');
    }

    // While Logged in Tests
    // /dashboard                       - dashboard             [login protected]
    // /question/{question}/index       - question display      [login protected]
    // /question/{question}/results     - question results      [login protected]

    // Dashboard Test while logged in should show "Dashboard" in heading
    public function testDashboardLoggedIn()
    {
        $user = User::first();
        $this->be($user);
        $this ->get('/dashboard')
            ->assertStatus(200)
            ->assertSeeText('Dashboard Home');
    }

    // Question Page Test while logged in 
    // Pick a question the user HAS answered
    // Should redirect user back to dashboard
    public function testQuestionPageLoggedInHaveAnswered()
    {
        $user = User::first();
        $this->be($user);
        $question_id = Question::whereHas('answers', function($answer) use($user) {
            return $answer->where('user_id', '=', $user->id);
        })->pluck('id')->first();
        $this ->get('/question/'.$question_id.'/index')
            ->assertStatus(302)
            ->assertRedirect('/dashboard');
    }

    // Question Page Test while logged in 
    // Pick a question the user hasn't answered yet
    // Should show "Question Page" in heading
    public function testQuestionPageLoggedInHaveNotAnswered()
    {
        $user = User::first();
        $this->be($user);
        $question_id = Question::whereDoesntHave('answers', function($answer) use($user) {
            return $answer->where('user_id', '=', $user->id);
        })->pluck('id')->first();
        $this ->get('/question/'.$question_id.'/index')
            ->assertStatus(200)
            ->assertSeeText('Question Page');
    }

    // Question Results Page Test while logged in 
    // Pick a question the user HAS answered
    // Should show "Question Results Page" in heading
    public function testQuestionResultsLoggedInHaveAnswered()
    {
        $user = User::first();
        $this->be($user);
        $question_id = Question::whereHas('answers', function($answer) use($user) {
            return $answer->where('user_id', '=', $user->id);
        })->pluck('id')->first();
        $this ->get('/question/'.$question_id.'/results')
            ->assertStatus(200)
            ->assertSeeText('Question Results Page');
    }

    // Question Results Page Test while logged in 
    // Pick a question the user hasn't answered yet
    // Should redirect user back to dashboard
    public function testQuestionResultsLoggedInHaveNotAnswered()
    {
        $user = User::first();
        $this->be($user);
        $question_id = Question::whereDoesntHave('answers', function($answer) use($user) {
            return $answer->where('user_id', '=', $user->id);
        })->pluck('id')->first();
        $this ->get('/question/'.$question_id.'/results')
            ->assertStatus(302)
            ->assertRedirect('/question/'.$question_id.'/index');
    }

    // Question Submit Page Test while logged in 
    // Get user/question then answer it
    // /question/{question}/submit      - question submit       [login protected]
    // This test has been moved to Laravel Dusk tests for simulating form submssion
    // public function testQuestionSubmitPageLoggedInHaveNotAnswered()
    // {
    //     $user = User::first();
    //     $this->be($user);
    //     $question_id = Question::whereDoesntHave('answers', function($answer) use($user) {
    //         return $answer->where('user_id', '=', $user->id);
    //     })->pluck('id')->first();
    //     $this ->get('/question/'.$question_id.'/index')
    //         ->check('terms')
    //         ->assertSeeText('Question Page');
    // }
    
}
