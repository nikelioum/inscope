<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name', 'address'];

    /**
     * The users that belong to the company.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_user');
    }

    /**
     * Get the projects associated with the company.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
