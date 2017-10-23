<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Question;
use App\Option;

class SiteTest extends DuskTestCase
{
    // use DatabaseMigrations;
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
     * /                                - index                 [not login protected]
     * 
     * Index Test while not logged in 
     * Should show welcome msg "Welcome to the AbleTo App" in heading
     * 
     * @group BrowserSiteTest 
     * @test
     * @return void
     */
    public function testIndexNotLoggedIn()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertSee('Welcome to the AbleTo App');
        });
    }

    /**
     * /                                - index                 [not login protected]
     * 
     * Index Test while logged in 
     * Should send you to the dashboard
     * 
     * @group BrowserSiteTest 
     * @test
     * @return void
     */
    public function testIndexLoggedIn()
    {
        $user = User::first();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
            ->visit('/')
            ->assertSee('Dashboard Home');
        });
    }

    /**
     * /question/{question}/submit      - question submit       [login protected]
     * 
     * Question Submit Page Test while logged in 
     * Get a user & a question they haven't answered yet
     * Pick an answer and then submit
     * Should show "Question Results Page" in heading
     * 
     * @group BrowserSiteTest 
     * @test
     * @return void
     */
    public function testQuestionSubmitPageLoggedInHaveNotAnswered()
    {
        $user = User::first();
        $question = Question::whereDoesntHave('answers', function($answer) use($user) {
            return $answer->where('user_id', '=', $user->id);
        })->get()->first();
        if(!empty($question)) {
            $option_value = $question->options->pluck('id')->first();
        } else {
            $question = factory(Question::class)
                ->create();
            $question->options()
                ->saveMany(factory(Option::class, 4)->create());
            $option_value = $question->options->pluck('id')->first();
        }
        $data_browser = [
            'question_id' => $question->id,
            'option_name' => "question_".$question->id,
            'option_value' => $option_value,
        ];

        $this->browse(function (Browser $browser) use ($user, $data_browser) {
            $browser->loginAs($user)
                ->visit('/question/'.$data_browser['question_id'].'/index')
                ->radio($data_browser['option_name'], $data_browser['option_value'])
                ->press('Submit Answer')
                ->assertSee('Question Results Page');
        });
    }

}
