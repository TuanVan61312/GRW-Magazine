<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

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
        // 'email_verified_at',
        'password',
        'address',
        'phone_number',
        'faculty_id',
        'role_id',
        'designation',
        'start_from',
        'image'
    ];

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
    ];

    public function faculty(){
        return $this->hasOne(Faculty::class, 'id', 'faculty_id');
    }

    public function role(){
        return $this->hasOne(Role::class, 'id', 'role_id');
    }

    public function isAdmin()
    {
        $adminRole = Role::where('name', 'admin')->first(); // Tìm role có tên là 'admin'
        return $this->role_id === $adminRole->id; // So sánh role_id của user với id của vai trò admin
    }

    public function isMarketingManager()
    {
        $marketingManagerRole = Role::where('name', 'Marketing Manager')->first(); // Tìm role có tên là 'marketing manager'
        return $this->role_id === $marketingManagerRole->id; // So sánh role_id của user với id của vai trò marketing manager
    }

    public function isMarketingCoordination()
    {
        $marketingCoordinatorRole = Role::where('name', 'Marketing Coordinator')->first(); // Tìm role có tên là 'marketing coordinator'
        return $this->role_id === $marketingCoordinatorRole->id; // So sánh role_id của user với id của vai trò marketing coordinator
    }
    

}
