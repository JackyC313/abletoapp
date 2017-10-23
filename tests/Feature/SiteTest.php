<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Answer;
use App\Option;
use App\Question;
use App\User;


class SiteTest extends TestCase
{
//  use DatabaseMigrations;
    use DatabaseTransactions;
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
     */

    /**
     * Set Up dummy user
     * Set Up dummy question
     * Set Up dummy options
     * Set Up answer options
     */
     
    /**
     * Set Up for use
     * 1 Users
     * 2 Questions
     * 2 Options (one for each question)
     * 1 Answer for the created user
     */
    protected function setup() {
        parent::setup();

        $this->user = factory(User::class)->create([
            'name' => 'TestUser for running test',
            'email' => 'test_unique_answer111111@example.com',
            'password' => bcrypt('secret'),
            'remember_token' => str_random(10),
        ]);

        $this->question = factory(Question::class)->create([
            'question' => "Question 1 for running test?",
        ]);

        $this->option = factory(Option::class)->create([
            'option' => "Test Option 1 for Question 1",
            'question_id' => $this->question->id
        ]);

        $this->question->options()->save($this->option);
        
        $this->answer = factory(Answer::class)->create([
            'question_id' => $this->question->id,
            'option_id' => $this->option->id,
            'user_id' => $this->user->id,
        ]);

        // Question with no answers
        $this->question_unanswered = factory(Question::class)->create([
            'question' => "Question 2 with no answers for test?",
        ]);

        $this->option_unanswered = factory(Option::class)->create([
            'option' => "Test Option for Question 2",
            'question_id' => $this->question_unanswered->id
        ]);

        $this->question_unanswered->options()->save($this->option_unanswered);
    }
   
     /**
     * /                                - index                 [not login protected]
     * 
     * While not logged in 
     * Going to Index page
     * Should show welcome msg "Welcome to the AbleTo App" in heading
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testIndexNotLoggedIn()
    {
        $this->get('/')
            ->assertStatus(200)
            ->assertSeeText('Welcome to the AbleTo App');
    }

    /**
     * /                                - index                 [not login protected]
     * 
     * While logged in 
     * Going to Index page
     * Should send you to the dashboard
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testIndexLoggedIn()
    {
        $this->actingAs($this->user)
            ->get('/')
            ->assertStatus(302)
            ->assertRedirect('/dashboard');
    }

    /**
     * Not Logged in Tests
     * 
     * /dashboard                       - dashboard             [login protected]
     * /question/{question}/index       - question display      [login protected]
     * /question/{question}/results     - question results      [login protected]
     * /question/{question}/submit      - question submit       [login protected]
     * 
     */
    
    /**
     * /dashboard                       - dashboard             [login protected]
     * 
     * While not logged in 
     * Going to Dashboard page 
     * Should send you to Login page
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testDashboardNotLoggedIn()
    {
        $this->get('/dashboard')
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * /question/{question}/index       - question display      [login protected]
     * 
     * While not logged in 
     * Going to Question page 
     * Should send you to Login page
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testQuestionPageNotLoggedIn()
    {
        $question_id = $this->question->id;
        $this->get('/question/'.$question_id.'/index')
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * /question/{question}/results     - question results      [login protected]
     * 
     * While not logged in 
     * Going to Question Results page
     * Should send you to Login page
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testQuestionResultsPageNotLoggedIn()
    {
        $question_id = $this->question->id;
        $this->get('/question/'.$question_id.'/results')
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * [GET] 
     * /question/{question}/submit      - question submit       [login protected]
     * 
     * While not logged in 
     * Going to Question Submit page
     * Should display an error page (set in app\Exceptions\Handler.php)
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testQuestionSubmitPageNotLoggedInGet()
    {
        $question_id = $this->question->id;
        $this->get('/question/'.$question_id.'/submit')
            ->assertStatus(405)
            ->assertSeeText('Error Page');

    }

    /**
     * [POST] 
     * /question/{question}/submit      - question submit       [login protected]
     * 
     * While not logged in 
     * Posting to Question Submit page
     * Should send you to Login page
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testQuestionSubmitPageNotLoggedInPost()
    {
        $question_id = $this->question->id;
        $this->post('/question/'.$question_id.'/submit')
            ->assertStatus(302)
            ->assertRedirect('/login');
    }

    /**
     * While Logged in Tests
     * 
     * /dashboard                       - dashboard             [login protected]
     * /question/{question}/index       - question display      [login protected]
     * /question/{question}/results     - question results      [login protected]
     * 
     */

    /**
     * /dashboard                       - dashboard             [login protected]
     * 
     * While logged in 
     * Going to Dashboard page
     * Should show "Dashboard" in heading
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testDashboardLoggedIn()
    {
        $this->actingAs($this->user)
            ->get('/dashboard')
            ->assertStatus(200)
            ->assertSeeText('Dashboard Home');
    }

    /**
     * /question/{question}/index       - question display      [login protected]
     * 
     * While logged in 
     * Going to Question page with the question_id of a question the user HAVE answered
     * Should redirect user back to dashboard
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testQuestionPageLoggedInHaveAnswered()
    {
        $question_id = $this->question->id;

        $this->actingAs($this->user)
            ->get('/question/'.$question_id.'/index')
            ->assertStatus(302)
            ->assertRedirect('/dashboard');
    }

    /**
     * /question/{question}/index       - question display      [login protected]
     * 
     * While logged in 
     * Going to Question page with the question_id of a question the user HAVE NOT answered
     * Should show "Question Page" in heading
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testQuestionPageLoggedInHaveNotAnswered()
    {
        $question_id = $this->question_unanswered->id;

        $this->actingAs($this->user)
            ->get('/question/'.$question_id.'/index')
            ->assertStatus(200)
            ->assertSeeText('Question Page');
    }

    /**
     * /question/{question}/results     - question results      [login protected]
     * 
     * While logged in 
     * Going to Question Results page with the question_id of a question the user HAVE answered
     * Should show "Question Results Page" in heading
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testQuestionResultsLoggedInHaveAnswered()
    {
        $question_id = $this->question->id;
        
        $this->actingAs($this->user)
            ->get('/question/'.$question_id.'/results')
            ->assertStatus(200)
            ->assertSeeText('Question Results Page');
    }

    /**
     * /question/{question}/results     - question results      [login protected]
     * 
     * While logged in 
     * Going to Question Results page with the question_id of a question the user HAVE NOT answered
     * Should redirect user back to dashboard
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testQuestionResultsLoggedInHaveNotAnswered()
    {

        $question_id = $this->question_unanswered->id;
        
        $this->actingAs($this->user)
            ->get('/question/'.$question_id.'/results')
            ->assertStatus(302)
            ->assertRedirect('/question/'.$question_id.'/index');
    }

    /**
     * [GET] 
     * /question/{question}/submit      - question submit       [login protected]
     * 
     * While logged in 
     * Going to Question Submit page with the question_id of a question the user HAVE answered
     * Should display an error page (set in app\Exceptions\Handler.php)
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testQuestionSubmitPageLoggedInHaveAnsweredGet()
    {
        $question_id = $this->question->id;

        $this->actingAs($this->user)
            ->get('/question/'.$question_id.'/submit')
            ->assertStatus(405)
            ->assertSeeText('Error Page');
}

    /**
     * [GET] 
     * /question/{question}/submit      - question submit       [login protected]
     * 
     * While logged in 
     * Going to Question Submit page with the question_id of a question the user HAVE answered
     * Should display an error page (set in app\Exceptions\Handler.php)
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testQuestionSubmitPageLoggedInHaveNotAnsweredGet()
    {
        $question_id = $this->question_unanswered->id;

        // No more unanswered questions, so create one to test
        if(empty($question_id)) {
            $question = factory(Question::class)->create();
            $question->each(function ($question) {
                    $question->options()
                        ->saveMany(factory(Option::class, 4)->make());
            });
            $question_id = $question->id;
        }

        $this->actingAs($this->user)
            ->get('/question/'.$question_id.'/submit')
            ->assertStatus(405)
            ->assertSeeText('Error Page');
}

    /**
     * [POST] 
     * /question/{question}/submit      - question submit       [login protected]
     * 
     * While logged in 
     * Going to Question Submit page with the question_id of a question the user HAVE answered
     * Should send you to dashboard
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testQuestionSubmitPageLoggedInHaveAnsweredPost()
    {
        $question_id = $this->question->id;
        $option_id = $this->option->id;
        
        $data = array(
            'question_'.$question_id => $option_id
        );

        $this->actingAs($this->user)
            ->post('/question/'.$question_id.'/submit', $data)
            ->assertStatus(302)
            ->assertRedirect('/dashboard');
    }

    /**
     * [POST] 
     * /question/{question}/submit      - question submit       [login protected]
     * 
     * While logged in 
     * Going to Question Submit page with the question_id of a question the user HAVE answered
     * Should show "Question Results Page" in heading
     * 
     * @group UnitSiteTest 
     * @test
     * @return void
     */
    public function testQuestionSubmitPageLoggedInHaveNotAnsweredPost()
    {
        $question_id = $this->question_unanswered->id;
        $option_id = $this->option_unanswered->id;
        
        $data = array(
            'question_'.$question_id => $option_id
        );

        $this->actingAs($this->user)
            ->post('/question/'.$question_id.'/submit', $data)
            ->assertStatus(302)
            ->assertRedirect('question/'.$question_id.'/results');
    }
}
