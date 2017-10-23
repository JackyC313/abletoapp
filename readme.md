# AbleTo Project

## Project Description
Build a webapp with a login page (using Laravel 5.X). 

Think of this exercise as if someone goes through a set of questions that he/she needs to answer and then sees the results. Once users log in, have them answer a set of "behavioral" questions (how they feel, what they did today, what they had for breakfast - 3-4 questions is fine) and then please display their answers.

The answers to the questions should multiple-choice / structured format (not free form where you can type in your answer). This way you can report on the answers with a graph. 
For example, the question could be asked "what did you eat today for breakfast ?" with answers "eggs, cereal, toast" and you could select a different answer each day. 

The exercise doesn't have to have many models or controllers -  what's important is that the code is tested / documented / we can run it (so dependencies are clearly defined and well explained). 
It's not about the code quantity, but the quality of the code.

Once you have something: 
 - upload it to your github and give us access to clone the repo and comment on it. 
 - deploy your code in the cloud or your own server if it's open to the public (AWS or Heroku or GoogleCloud or cloud9, etc..) and give us a live link, so we can access the live webapp while looking at the code. 

## Developer Notes/Question/Answers
1) Do users need the ability to create/edit the set of questions (surveys/polls)?
Recommendations: I can just populate the surveys in the database manually for now and create a nicer UI for creating them near the end if necessary
Answer: Seeding questions is fine.

2) Are users limited to answering these question/surveys once?
Recommendations: I think it would make more sense that users can only fill out a survey once. However, if we want to gather more answers, one can always create a copy of that survey and tweak it as necessary.
Answer: Think of it as someone coming to a website day after day and answering the questions to generate data ( insights ) that they can be displayed.
Dev Note: Then it would be less of a survey system and more of a set of (random) questions which users and request for and answer but would only do once for each question. 

3) Are we looking for any sort of specific graphs? (Line/Pie/Bar)
Recommendations:  I am thinking of playing around with what would look nice with the amount of test data I can get. Perhaps showing a graph for each survey on how many people answered what for each question (kind of like a poll result).
Answer: Recommendation makes sense.

Graphing:
Found a Laravel Charting/Graphing package that also seems to be kept up to date.  Used that for now
https://github.com/ConsoleTVs/Charts  
https://erik.cat/projects/Charts/docs/5  

## Summary of User Workflows
1. Users entering the app are initially not logged in and are presented with the option to register or login
    * A user registers
    * A user logs in with their credentials
2. Once registered or logged in, they can see a dashboard with access to a list of questions 
    * These questions are sorted by ones they can answer and the rest are ones that have been answered
    * Each question would have links on them
        * Unanswered questions links lets the user answer the question
        * Answered questions links lets the user see the results of the question (Note: This is unavailble for unanswered questions to give incentive for users to answer it so they can see the results)
3. If a user chooses to answer a question, they are present the question with the multiple answers and a submit button.
    * They are required to pick an answer to submit
    * Once there is a valid submission, they are brough back to the dashboard with the status of the submission
4. If the user chooses to see the question results, there would be a graph showing the number of people having the answers for each choice (similar to a poll results graph)

## Development Outline
1. Build the initial framework for project
    * Documentation
    * DB Tables
    * Controllers
    * Models
2. 3 Database Tables
    * Questions
    * Options
    * Answers
3. Controllers
    * SiteContoller - Help with page navigation
    * DashboardController - (renamed from "HomeController") Help to show logged in Dashboard
    * QuestionController - Handle displaying of question and their answers, showing question results
    * OptionController - No need yet
    * AnswerController - Handle storing of user answers
4. Models
    * User
        - id (unique id)
        - name (username)
        - email (unique email)
        - rememberToken (token to remember login)
    * Question
        - id (unique id)
        - question (text question)
    * Option
        - id (unique id)
        - question_id (the question the option belongs to)
        - option (text option)
    * Answer
        - id (unique id)
        - user_id (the user the answer belongs to)
        - question_id (the question the answer belongs to)
        - option_id (the option the answer belongs to)
5. Set Up Routing
    * /
    * /dashboard
    * /question/{id}/show
    * /question/{id}/submit
    * /question/{id}/result
6. Front End
    * Layout
    * Partials
        * TopNavigation
        * StatusMessages
        * QuestionUnanswered
        * QuestionAnswered
    * Pages
        * FrontPage
        * Dashboard
        * QuestionAnswer
        * QuestionResult
7. Testing
    * Page Testing
    * Submission
8. Test Data Seeding
    * Users Seed
    * Questions Seed (should create up to 4 options for each question)
    * Answers Seed

## Developer Notes
This is a quick start/section for any other developers who would like to get started on a fresh copy of laravel and run through the initial set ups for this project
### Laravel Set Up
```
composer create-project --prefer-dist laravel/laravel abletoapp
```
### CSS & JS Set Up
Add custom css file
resources\assets\sass\_main.scss 
in resources\assets\sass\app.scss  
```
// Main (custom styles)
@import "main";
```
Compile public app.js and app.css
```
npm install
npm run dev
```
### DB Set Up
update .env with database info:
db: -  
user: -  
pw: -  

https://laravel-news.com/laravel-5-4-key-too-long-error  
in app\Providers\AppServiceProvider.php
```
use Illuminate\Support\Facades\Schema;

public function boot()
{
    Schema::defaultStringLength(191);
}
```
#### Create Database Tables for Questions/Options/Answers
```
php artisan make:migration create_questions_table --create=questions  
php artisan make:migration create_options_table --create=options  
php artisan make:migration create_answers_table --create=answers  
```
### Models & Controllers Set Up
#### Create Model/Controllers
```
php artisan make:controller SiteController
php artisan make:controller QuestionController --model=Question  
php artisan make:controller OptionController --model=Option  
php artisan make:controller AnswerController --model=Answer  
```
### Laravel Package Set Up
#### Add Login Scaffolding
```
php artisan make:auth
```
#### Form Helper Package
```
composer require "laravelcollective/html":"^5.4.0"
```
#### Custom helper for blade templates  
https://code.tutsplus.com/tutorials/how-to-create-a-laravel-helper--cms-28537  
```
php artisan make:provider AngServiceProvider
```
in \config\app.php
```
        // providers
        App\Providers\AngServiceProvider::class,

        // aliasesa
        'AngBlade' => App\Helpers\Ang\Blade::class,
```
#### Charts/Graphing Package  
```
composer require consoletvs/charts:5.*
```

```
php artisan vendor:publish
```

### Testing Set Up
Initialized Tests Files
```
php artisan make:test SiteTest

composer require --dev laravel/dusk
php artisan dusk:install  
php artisan dusk:make SiteTest
```
### App Testing
Regular PHP Unit SiteTests to test visiting pages
```
vendor/bin/phpunit tests/Feature/SiteTest.php
```
Laravel Dusk Test to test question submission
```
php artisan dusk --filter SiteTest
```
### Seeding Test Data
Model Factories

```
factory(App\User::class)->create();  
factory(App\Question::class)->create();  
factory(App\Option::class)->create();  
factory(App\Answer::class)->create();  
```
DEV NOTES:
* Options created look for Questions that has less than 4 options already so as to not fill it up too much with options
* Answers create make sure that user's only have 1 answer for each question  

```
php artisan db:seed --class=UsersTableSeeder  
php artisan db:seed --class=QuestionsTableSeeder  
php artisan db:seed --class=AnswersTableSeeder  
or just
php artisan db:seed
```

DEV NOTE: 
* Questions seeded will also have 4 options made for them
* Found a bug where when using batch create notation such as
```
       factory(App\Answer::class, 10)->create();
```
The logic to check constraints on the collection of data in the factory do not reflect the newest created object collection. And so the AnswersTable seed was rewritten as a for loop which seems to do the trick.  
```
        for($i = 0; $i <= 10; $i++) {
            factory(App\Answer::class)->create();
        };
    }
```
May need to explore this more in the future on whether the batch notation is batching up the SQL Creates and running it all as a transaction. This may also be an issue for the when we want to batch create options as there is logic in that factory as well.
