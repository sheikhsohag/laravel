<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DockerTest extends Model
{
    protected $table = 'docker_tests';

    protected $fillable = [
        'name',
        'email',
    ];
}
