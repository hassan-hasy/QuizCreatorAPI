<?php

namespace App\Http\Controllers\Questions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quize;
use App\Models\Question;
use Exception;

class QuestionsController extends Controller
{

    public function index()
    {
        $resp = [
            "success" => true,
            "errors" => null,
            "data" => "welcome to our test api"
        ];
        return $this->response_json($resp, 200);
    }


    public function store(Request $request)
    {

        $data = $request->json()->all();
        $title = $data['title'];
        $body = $data['description'];
        $extra = $data['extra'];
        $isMandatory = $extra['isMandatory'];
        $isDraft = $extra['isDraft'];
        $isPublished = $extra['isPublished'];
        $isDeleted = $extra['isDeleted'];
        $quizCreate = Quize::create([
            'title' => $title,
            'description' => $body,
            'isMandatory' => $isMandatory,
            'isDraft' => $isDraft,
            'isPublished' => $isPublished,
            'isDeleted' => $isDeleted
        ]);
        $quizId = $quizCreate->id; //get the last inserted id
        $questions = $data['questions'];
        foreach ($questions as $question) {
            try {
                Question::create([
                    'quizId' => $quizId,
                    'question' => $question['question'],
                    'answer1' => isset($question['answer1']) ? $question['answer1'] : null,
                    'answer2' => isset($question['answer2']) ? $question['answer2'] : null,
                    'answer3' => isset($question['answer3']) ? $question['answer3'] : null,
                    'answer4' => isset($question['answer4']) ? $question['answer4'] : null
                ]);
            } catch (Exception $e) {
                if ($questions->count() == 0) {
                    $resp = [
                        "success" => false,
                        "errors" => 500,
                        "data" => null
                    ];
                    $status = 500;
                    return $this->response_json($resp, $status);
                }
            }
        }
        $resp = [
            'success' => true,
            'errors' => null,
            'data' => null
        ];
        return $this->response_json($resp, 200);
    }


    public function show($id)
    {
        $quizId = $id;
        if ($quizId == null) {
            $resp = [
                "success" => false,
                "errors" => 500,
                "data" => null
            ];
            $status = 500;
            return $this->response_json($resp, $status);
        }
        $questions = Question::leftJoin('quizes', 'quizes.id', '=', 'questions.quizId')
            ->where('quizes.id', '=', $quizId)
            ->get(['title', 'isMandatory', 'questions.*']);

        if ($questions->count() == 0) {
            $resp = [
                "success" => false,
                "errors" => 500,
                "data" => null
            ];
            $status = 500;
            return $this->response_json($resp, $status);
        }
        $title = $questions[0]['title'];
        $bundled = [];
        foreach ($questions as $q) {
            $singleQuestion['question'] = $q->question;
            $aBundle = [];
            isset($q->answer1) ? array_push($aBundle, $q->answer1) : null;
            isset($q->answer2) ? array_push($aBundle, $q->answer2) : null;
            isset($q->answer3) ? array_push($aBundle, $q->answer3) : null;
            isset($q->answer4) ? array_push($aBundle, $q->answer4) : null;
            $singleQuestion['answers'] = $aBundle;
            array_push($bundled, $singleQuestion);
        }
        $singeQuestion = [
            'title' => $title,
            'questions' => $bundled
        ];

        $resp = [
            "success" => true,
            "errors" => null,
            "data" => $singeQuestion
        ];
        return $this->response_json($resp, 200);
    }


    function response_json($data, $status)
    {
        return response()->json(
            $data,
            $status,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }
}
