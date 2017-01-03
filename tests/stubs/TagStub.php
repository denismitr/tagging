<?php

use Denismitr\Tagging\TagUsedScopes;
use Illuminate\Database\Eloquent\Model;

class TagStub extends Model
{
    use TagUsedScopes;

    protected $connection = 'testbench';

    protected $table = 'tags';
}
