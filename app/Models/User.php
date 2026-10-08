<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use App\Notifications\AdminResetPasswordNotification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $appends = ['avatar_url'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'email',
        'password',
        'password_updated_at',
        'phone',
        'address',
        'profile',
        'role',
        'provider',
        'provider_id',
        'provider_token',
        'email_verified_at',
        'locale',
        'last_online_at',
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
        ];
    }

    protected $casts = [
        'password_updated_at' => 'datetime',
        'last_online_at'      => 'datetime',
    ];

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }

    public function getAvatarUrlAttribute(): string
    {
        // External avatar (Google, GitHub, etc.)
        if ($this->profile && str_starts_with($this->profile, 'http')) {
            return $this->profile;
        }

        // Local uploaded avatar
        if ($this->profile && Storage::disk('public')->exists($this->profile)) {
            return asset('storage/' . $this->profile);
        }

        // Admin default
        if ($this->isAdmin()) {
            return asset('admin/img/undraw_profile.svg');
        }

        // User default
        return asset('user/img/avatar.jpg');
    }

    public function sendPasswordResetNotification($token)
    {
        if ($this->role === 'admin') {
            $this->notify(new AdminResetPasswordNotification($token));
            return;
        }

        $this->notify(new ResetPassword($token));
    }

    public function getRegisterMethodAttribute(): string
    {
        return match ($this->provider) {
            'google' => 'Google',
            'github' => 'GitHub',
            default  => 'Local',
        };
    }


    public function isOnline(): bool
    {
        // Online if last_online_at is within last 5 minutes
        return $this->last_online_at && $this->last_online_at->diffInMinutes(now()) < 5;
    }

    public function lastOnline(): string
    {
        if ($this->last_online_at) {
            return $this->last_online_at->diffForHumans([
                'short' => true,
            ]);
        }

        return 'Never';
    }

    public function scopeOrderByOnlineStatus(Builder $query): Builder
    {
        return $query
            ->leftJoin('sessions', function ($join) {
                $join->on('users.id', '=', 'sessions.user_id')
                    ->where('sessions.last_activity', '>=', now()->subMinutes(2)->timestamp);
            })
            ->select('users.*')
            ->orderByRaw('CASE WHEN sessions.user_id IS NULL THEN 1 ELSE 0 END')
            ->orderByDesc('users.last_online_at')
            ->orderByDesc('users.created_at');
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }

}
