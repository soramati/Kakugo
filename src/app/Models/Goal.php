<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\Auth;



class Goal extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'goals_name',
        'goals_is_deadline',
        'goals_deadline',
        'goals_reward',
        'goals_conditions',
        'goals_repeat',
        'goals_point',
        'goals_is_achieved',
        'goals_percent',
        'goals_is_set',
        'user_id',
        'goals_user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPaginateByLimit(int $limit_count = 10)
    {
        return $this::with('user')->orderBy('updated_at', 'DESC')->paginate($limit_count);
    }

    public function resetGoalsSet()
    {
        $old_goals = $this::with('user')->find(Auth::id())->goals()->first();
        $old_goals->goals_is_set = 0;
        $old_goals->save();
        $this->goals_is_set = 1;
        $this->save();
    }

    public function formatDate()
    {
        $date = new DateTime($this->goals_deadline);
        $formattedDate = $date->format('Y/m/d G:i');
        return $formattedDate;
    }

    public function howLongTime()
    {
        $today = new DateTime("now");
        $deadline = new DateTime($this->goals_deadline);
        $started_at = new DateTime($this->started_at);

        $interval = $today->diff($deadline);
        $interval_full = $started_at->diff($deadline);


        //get remaining time
        $int = 0;
        $int = $interval->days * 24 * 60 * 60;
        $int += $interval->h * 60 * 60;
        $int += $interval->i * 60;
        $int += $interval->s;

        //get full time
        $int_full = $interval_full->days * 24 * 60 * 60;
        $int_full += $interval_full->h * 60 * 60;
        $int_full += $interval_full->i * 60;
        $int_full += $interval_full->s;

        $array = array(
            'today' => $today,
            'deadline' => $deadline,
            'invert' => $interval->invert,
            'int' => $int,
            'int_full' => $int_full
        );
        return $array;
    }
    public function getRemainingTime($interval)
    {
        $int = $interval->days * 24 * 60 * 60;
        $int += $interval->h * 60 * 60;
        $int += $interval->i * 60;
        $int += $interval->s;
        return $int;
    }


    public function getFullTime()
    {
        $today = new DateTime($this->started_at);
        $deadline = new DateTime($this->goals_deadline);
        $interval = $today->diff($deadline);

        if ($interval->invert == 1) {
            return 0;
        } else {
            $int = $interval->days * 24 * 60 * 60;
            $int += $interval->h * 60 * 60;
            $int += $interval->i * 60;
            $int += $interval->s;
            return $int;
        }
    }
}
