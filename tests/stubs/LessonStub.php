<?php

use Denismitr\Tagging\Taggable;
use Illuminate\Database\Eloquent\Model;

class LessonStub extends Model
{
    use Taggable;

    protected $connection = 'testbench';

    protected $table = 'lessons';
}
