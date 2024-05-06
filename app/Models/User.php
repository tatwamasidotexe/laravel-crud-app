<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
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
        'username',
        'dob',
        'phone_no',
        'email',
        'u_address',
        'gender',
        'hobbies',
        'img_url',
        'password',
        'state_id',
        'country_id',
    ];
    
    // Generate password based on username and DOB
    public function generatePassword($username, $dob)
    {
        $password = strtolower(substr($username, 0, 3)) . implode("", explode("-", $dob));

        return Hash::make($password);
    }

    public static function create(array $attributes = [])
    {
        if (isset($attributes['username']) && isset($attributes['dob'])) {
            $attributes['password'] = self::generatePassword($attributes['username'], $attributes['dob']);
        }

        return parent::create($attributes);
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        // 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $primaryKey = 'u_id';

    // If your table doesn't have timestamps, you can disable them:
    public $timestamps = false;
}
