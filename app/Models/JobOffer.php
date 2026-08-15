<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobOffer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id',
        'user_id',
        'title',
        'description',
        'location',
        'contract_type',
        'salary_min',
        'salary_max',
        'show_salary',
        'stage_duration_months',
        'stage_stipend',
        'guaranteed_response',
        'status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'guaranteed_response' => 'boolean',
            'show_salary' => 'boolean',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'job_offer_skill');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Percentage of this offer's required skills that the given user also has.
     */
    public function compatibilityScoreFor(User $user): int
    {
        $requiredSkillIds = $this->skills->pluck('id');

        if ($requiredSkillIds->isEmpty()) {
            return 0;
        }

        $userSkillIds = $user->skills->pluck('id');

        $matchingCount = $requiredSkillIds->intersect($userSkillIds)->count();

        return (int) round($matchingCount / $requiredSkillIds->count() * 100);
    }
}
