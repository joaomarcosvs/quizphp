<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class MainController extends Controller
{
    private $app_data;

    public function __construct()
    {
        // load app_data.php file from app folder
        $this->app_data = require(app_path('app_data.php'));
    }

    public function startGame(): View
    {
        return view('home');
    }

    public function prepareGame(Request $request)
    {

        $request->validate(
            [
                'total_questions' => 'required|integer|min:3|max:30'
            ],
            [
                'total_questions.requeire' => 'O numero de questões é obrigatório.',
                'total_questions.integer' => 'O número de questões deve ser um número inteiro.',
                'total_questions.min' => 'O número de questões deve ser no mínimo :min',
                'total_questions.max' => 'O número de questões deve ser no máximo :max',
            ]
        );

        // get total questions
        $total_questions = intval($request->input('total_questions'));

        //prepare all the quiz structure
        $quiz = $this->prepareQuiz($total_questions);

        // store quiz in session
        session()->put([
            'quiz' => $quiz,
            'total_questions' => $total_questions,
            'current_questions' => 1,
            'correct_answers' => 0,
            'wrong_answers' => 0
        ]);

        return redirect()->route('game');

    }

    private function prepareQuiz($total_questions)
    {
        $questions = [];
        $total_countries = count($this->app_data);

        // create countries index for unique questions

        $indexes = range(0, $total_countries - 1);
        shuffle($indexes); // randomize the indexes
        $indexes = array_slice($indexes, 0, $total_questions);

        // create array of questions
        $question_number = 1;
        foreach ($indexes as $index) {

            $question['question_number'] = $question_number++;
            $question['country'] = $this->app_data[$index]['country'];
            $question['correct_answer'] = $this->app_data[$index]['capital'];

            //wrong answers
            $other_capitals = array_column($this->app_data, 'capital');

            //remove correct answer
            $other_capitals = array_diff($other_capitals, [$question['correct_answer']]);

            //shuffle the wrong answers
            shuffle($other_capitals);
            $question['wrong_answers'] = array_slice($other_capitals, 0, 3);

            //store answer result
            $question['correct'] = null;

            $questions[] = $question;
        }
        return $questions;
    }

    public function game(): View
    {
       $quiz = session('quiz');
       $total_questions = session('total_questions');
       $current_question = session('current_questions') -1;

       //prepare answers to show in view
       $answers = $quiz[$current_question]['wrong_answers'];
       $answers[] = $quiz[$current_question]['correct_answer'];

       shuffle($answers);

       return view('game')->with([
        'country'=> $quiz[$current_question]['country'],
        'totalQuestions' => $total_questions,
        'currentQuestion' => $current_question,
        'answers' => $answers
       ]);
    }

    public function answer($enc_answer)
    {
        try{
            $answer = Crypt::decryptString($enc_answer);
        } catch (\Exception $e) {
            return redirect()->route('game');
        }

        // game logic
        $quiz = session('quiz');
        $current_question = session('current_questions') - 1;
        $correct_answer = $quiz[$current_question]['correct_answer'];
        $correct_answers = session('wrong_answers');
        $wrong_answers = session('wrong_answers');

        if($answer == $correct_answer) {
            $correct_answers++;
            $quiz[$current_question]['correct'] = true;
        } else {
            $wrong_answers++;
            $quiz[$current_question]['correct'] = false;
        }

        //update session
        session()->put([
            'quiz' => $quiz,
            'correct_answers' => $correct_answers,
            'wrong_answers' => $wrong_answers
        ]);

        //prapare data to show correct answer
        $data = [
            'country' => $quiz[$current_question]['country'],
            'correct_answer' => $quiz[$current_question]['correct_answer'],
            'choice_answer' => $answer,
            'currentQuestion' => $current_question,
            'totalQuestions' => session('total_questions')
        ];

        return view('answer_result')->with($data);
    }
}
