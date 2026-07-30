<?php

declare(strict_types=1);

namespace App\Core\Organization\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Organization extends Model
{
    use SoftDeletes;

    protected $table = 'organizations';

    protected $guarded = [];
}