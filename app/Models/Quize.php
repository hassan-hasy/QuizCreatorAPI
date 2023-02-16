<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quize extends Model
{
    use HasFactory;
    protected $table = 'quizes';

    public function questions()
    {
        return $this->hasMany(Question::class, 'quizId');
    }
    protected $fillable = ['title', 'description', 'isMandatory', 'isDraft', 'isPublished', 'isDeleted'];
}
