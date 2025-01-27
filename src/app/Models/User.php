<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Goal;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

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
        'password' => 'hashed',
    ];

    public function followers()
    {
        return $this->hasMany(Follow::class, 'followee_id');
    }

    public function followings()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function index()
    {
        return view('users/index');
    }
    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

    public function getPublishedPaginateByLimit(int $limit_count = 5)
    {
        //usersテーブルのisPublishがtrueになっているuserのgoalsテーブルのデータを取得     

        return $this::with('goals')->where('isPublish', true)->goals()->orderBy('updated_at', 'DESC')->paginate($limit_count);
    }
    public function getOwnPaginateByLimit(int $limit_count = 5)
    {
        return $this::with('goals')->find(Auth::id())->goals()->orderBy('updated_at', 'DESC')->paginate($limit_count);
    }

    public function getSetPaginateByLimit()
    {
        $set_goal = $this::with('goals')->find(Auth::id())->goals()->orderBy('updated_at', 'DESC')->where('goals_is_set', true)->where('goals_is_achieved', 0)->first();


        return $set_goal;
    }
    public function resetGoalsSet()
    {
        $old_goal = $this::with('goals')->find(Auth::id())->goals()->orderBy('updated_at', 'DESC')->where('goals_is_set', true);
        foreach ($old_goal as $goal) {
            $goal->goals_is_set = false;
            $goal->save();
        }
    }
    public function getShering($userid)
    {
        $data = $this::with('goals')->find($userid)->goals()->orderBy('updated_at', 'DESC')->where('goals_is_set', true)->where('goals_is_achieved', 0)->first();
        return [
            'data' => $data,
            'time' => $data->howLongTime(),
        ];
    }
    public function getTask($hashed_id)
    {
        $data = $this::with('goals')->where('hashed_id', $hashed_id)->first();
        return [
            'data' => $data

        ];
    }
}
