<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $table = 'questions';

    public function quizes()
    {
        return $this->belongsTo(Quize::class, 'id');
    }
    protected $fillable = ['quizId', 'question', 'answer1', 'answer2', 'answer3', 'answer4'];
}
