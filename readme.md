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
Some options to do graphing later.  Will need to explore what would look good.
http://www.chartjs.org
http://www.pchart.net

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
        - option_id (the optioin the answer belongs to)
    

## Developer Notes
This is a quick start/section for any other developers who would like to get started on a fresh copy of laravel and run through the initial set ups for this project
### Laravel Set Up
```
composer create-project --prefer-dist laravel/laravel abletoapp
```
### CSS & JS Set Up
```
npm install
npm run dev
```

### DB Set Up
set up database:
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
php artisan make:controller QuestionController --model=Question  
php artisan make:controller OptionController --model=Option  
php artisan make:controller AnswerController --resource --model=Answer  
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