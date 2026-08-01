<?php

declare(strict_types=1);

namespace App\Core\Organization\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Organization extends Model
{
    use HasUuids;
    use SoftDeletes;

    protected $table = 'organizations';

    /**
     * We intentionally use guarded instead of fillable.
     * DTOs + Actions control what is persisted.
     */
    protected $guarded = [];

    /**
     * UUID is the public identifier.
     */
    public $incrementing = true;

    protected $keyType = 'int';

    protected function casts(): array
    {
        return [
            'created_at'  => 'datetime',
            'updated_at'  => 'datetime',
            'deleted_at'  => 'datetime',
            'activated_at'=> 'datetime',
            'archived_at' => 'datetime',
        ];
    }

    /**
     * Organization Owner
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'owner_user_id'
        );
    }
}