<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'employee_id',
        'email',
        'password',
        'role',
        'is_active',
        'campus_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if user is super admin (system administrator)
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Backward-compatible alias for isSuperAdmin
     */
    public function isAdmin(): bool
    {
        return $this->isSuperAdmin();
    }

    /**
     * Check if user is faculty
     */
    public function isFaculty(): bool
    {
        return $this->role === 'faculty';
    }

    /**
     * Check if user is the university president
     */
    public function isUniversityPresident(): bool
    {
        return $this->role === 'university_president';
    }

    /**
     * Check if user is the university treasurer
     */
    public function isUniversityTreasurer(): bool
    {
        return $this->role === 'university_treasurer';
    }

    /**
     * Check if user is a campus president
     */
    public function isCampusPresident(): bool
    {
        return $this->role === 'campus_president';
    }

    /**
     * Backward-compatible: check if user is "president" (university level)
     */
    public function isPresident(): bool
    {
        return $this->isUniversityPresident();
    }

    /**
     * Backward-compatible: check if user is "treasurer" (university level)
     */
    public function isTreasurer(): bool
    {
        return $this->isUniversityTreasurer();
    }

    /**
     * Check if user is any type of officer (non-faculty)
     */
    public function isOfficer(): bool
    {
        return in_array($this->role, ['super_admin', 'university_president', 'university_treasurer', 'campus_president']);
    }

    /**
     * Check if user is a university-level officer (super admin, president, or treasurer)
     */
    public function isUniversityOfficer(): bool
    {
        return in_array($this->role, ['super_admin', 'university_president', 'university_treasurer']);
    }

    /**
     * Check if user can manage claims (approve/reject/remand) — Super Admin & University President
     */
    public function canManageClaims(): bool
    {
        return $this->isSuperAdmin() || $this->isUniversityPresident();
    }

    /**
     * Check if user can release claims — Super Admin & University Treasurer
     */
    public function canReleaseClaims(): bool
    {
        return $this->isSuperAdmin() || $this->isUniversityTreasurer();
    }

    /**
     * Check if user can manage dues — Super Admin & University Treasurer
     */
    public function canManageDues(): bool
    {
        return $this->isSuperAdmin() || $this->isUniversityTreasurer();
    }

    /**
     * Check if user can view reports — any officer
     */
    public function canViewReports(): bool
    {
        return $this->isOfficer();
    }

    /**
     * Check if user can manage pending registrations
     * Super Admin & University President see all; Campus Presidents see their campus only
     */
    public function canManagePendingRegistrations(): bool
    {
        return $this->isSuperAdmin() || $this->isUniversityPresident() || $this->isCampusPresident();
    }

    /**
     * Check if user can manage faculty members (view list, create, edit, archive)
     * Super Admin & University officers see all; Campus Presidents see their campus only
     */
    public function canManageMembers(): bool
    {
        return $this->isSuperAdmin() || $this->isUniversityOfficer() || $this->isCampusPresident();
    }

    /**
     * Get the campus ID this user manages (for campus presidents).
     * Super admin & university officers can manage all campuses (returns null).
     */
    public function managedCampusId(): ?int
    {
        if ($this->isCampusPresident()) {
            return $this->campus_id;
        }

        return null; // university-level officers see all
    }

    /**
     * Get a human-readable role label
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'super_admin' => 'Super Admin',
            'university_president' => 'University President',
            'university_treasurer' => 'University Treasurer',
            'campus_president' => 'Campus President',
            'faculty' => 'Faculty',
            default => ucfirst($this->role),
        };
    }

    /**
     * Get the campus that the user belongs to
     */
    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    /**
     * Get the faculty profile for the user
     */
    public function facultyProfile(): HasOne
    {
        return $this->hasOne(FacultyProfile::class);
    }

    /**
     * Get posts created by the user
     */
    public function transparencyPosts(): HasMany
    {
        return $this->hasMany(TransparencyPost::class, 'created_by');
    }

    /**
     * Get trainings created by the user
     */
    public function trainingSeminars(): HasMany
    {
        return $this->hasMany(TrainingSeminar::class, 'created_by');
    }

    /**
     * Get audit logs for the user
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }
}
