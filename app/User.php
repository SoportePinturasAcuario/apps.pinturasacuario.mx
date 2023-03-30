<?php

namespace Apps;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Apps\Notifications\ResetPasswordNotification;
use Apps\Notifications\Store\CustomerResetPasswordNotification;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Apps\Models\Store\Customer;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable implements JWTSubject
{
    protected $connection = 'mysql';

    use Notifiable; 

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "id", 'email', 'password', 'config', 'collaborator_id', 'avatar', 'acount_type_id', 'userable_id', 'userable_type', 'active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }  

    
    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        if ($this->acount_type_id == 1) {
            $this->notify(new ResetPasswordNotification($token));
        } else{
            $this->notify(new CustomerResetPasswordNotification($token));
        }
    }

    public function collaborator(){
        return $this->belongsTo(Collaborator::class);
    }  

    public function apps(){
        return $this->belongsToMany(App::class);
    }   

    public function roles(){
        return $this->belongsToMany(Rol::class);
    }  

    public function support_staff(){
        return $this->hasOne(SupportStaff::class);
    } 

    // public function customer(){
        // return $this->morphTo(Customer::class, 'userable_type', 'userable_id');
    // } 

    public function acount_type(){
        return $this->belongsTo(AcountType::class);
    } 

    public function userable() {
         return $this->morphTo();
    }
}
