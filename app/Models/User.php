<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'password',
        'facebook_id',
        'avatar',
        'nickname',
        'profile_image',
        'plants_count',
        'registration_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'registration_date' => 'datetime',
        'password' => 'hashed',
    ];

    public function plants()
    {
        return $this->hasMany(Plant::class);
    }

    public function proposals()
    {
        return $this->hasMany(PlantProposal::class);
    }

    public function updatePlantsCount()
    {
        $this->plants_count = $this->plants()->count();
        $this->save();
    }
}