<?php

namespace App\Models;

use App\Models\Permission;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserPermission;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'status',
        'email_verified_at',
        'phone',
        'branch_id',
    ];

    /**
     * The permissions that belong to the user.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function canDo($permissions)
    {
        try {
            // If user is admin, bypass all checks
            if ($this->is_admin) {
                return true;
            }

            // If no permissions provided, deny access
            if (empty($permissions)) {
                return false;
            }

            // Convert single permission to array
            $permissions = is_array($permissions) ? $permissions : [$permissions];

            // Cache key for user permissions
            $cacheKey = 'user_permissions_' . $this->id;

            // Get cached permissions or load them from database
            $userPermissions = \Cache::remember($cacheKey, now()->addHours(1), function () {
                return $this->permissions()->pluck('name')->toArray();
            });

            // Check if user has any of the required permissions
            foreach ($permissions as $permission) {
                if (in_array($permission, $userPermissions)) {
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            \Log::error('Error in canDo method: ' . $e->getMessage());
            return false;
        }
    }

}
