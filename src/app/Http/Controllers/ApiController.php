<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Goal;
use App\Models\User;
use App\Models\Follow;
use Faker\Provider\Base;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{


    public function apiIndex()
    {
        $goals = Goal::where('user_id', Auth::id())->get();
        if ($goals->isEmpty()) {
            return response()->json([
                'message' => 'Data not found',
                'isData' => '0',
            ], 200);
        }
        return response()->json([
            'message' => 'Data read successfully',
            'isData' => '1',
            'data' => $goals,

        ], 200);
    }
    public function apiShere($hashID)
    {
        $goal = Goal::where('hashed_id', $hashID)->first();
        return response()->json([
            'message' => 'Data read successfully',
            'data' => $goal,
        ], 200);
    }


    public function apiStore(Request $request)
    {
        // バリデーションを実施
        // Goal::where('user_id', Auth::id())->update(['goals_is_set' => 0]);
        $goal = new Goal();
        $goal->goals_name = $request->goals_name;
        $goal->goals_description = $request->goals_description;

        // $goal->goals_is_set = $request->goals_is_set;
        // urlにするときスラッシュが邪魔なので削除
        // $goal->hashed_id = str_replace('/', '', Hash::make($goal->id));


        $goal->user_id = Auth::id();
        $goal->save();

        $allGoals =   Goal::where('user_id', Auth::id());
        $acivedGoals =   Goal::where('user_id', Auth::id())->where('goals_is_achieved', 1);
        $points =  $acivedGoals->count() / $allGoals->count() * 100;

        User::where('id', Auth::id())->update(['point' => $points]);

        // レスポンスとホームへリダイレクト

        return response()->json([
            'message' => 'Data created successfully',
            'data' => $goal,
        ], 201)->header('Location', url('/home'));
    }
    public function apiPublish()
    {
        $user = User::find(Auth::id());
        $user->isPunlish = 1;
        $user->hashed_id = str_replace('/', '', Hash::make($user->id)) . Auth::id();
        $user->save();
        return response()->json([
            'message' => 'Data published successfully',
        ], 200);
    }
    public function apiAchieved($id)
    {
        $task = Goal::find($id);
        $task->goals_is_achieved = 1;
        $task->save();

        $allGoals =   Goal::where('user_id', Auth::id());
        $acivedGoals =   Goal::where('user_id', Auth::id())->where('goals_is_achieved', 1);
        $points =  $acivedGoals->count() / $allGoals->count() * 100;

        User::where('id', Auth::id())->update(['point' => $points]);
        return response()->json([
            'message' => 'Data achieved successfully',
        ], 200);
    }

    public function destroy($id)
    {
        $task = Goal::find($id);
        $task->destroy($id);
        return response()->json([
            'message' => 'Data deleted successfully',
        ], 200);
    }
    public function reset()
    {
        $goals = Goal::where('user_id', Auth::id());
        $goals->delete();
        $user = User::find(Auth::id());
        $user->point = 0;
        $user->isPunlish = 0;
        $user->save();
        return response()->json([
            'message' => 'Data reset successfully',
        ], 200);
    }
    public function apiStart($id)
    {
        Goal::where('user_id', Auth::id())->update(['goals_is_set' => 0]);
        $task = Goal::find($id);
        $task->goals_is_set = 1;
        $task->save();
        return response()->json([
            'message' => 'Data start successfully',
        ], 200);
    }
    public function apiGetPublishedGoals()
    {

        $goals = User::where('isPunlish', true)->with(['goals'])->get();

        return response()->json([
            'data' => $goals,
            'message' => 'Data read successfully',

        ], 200);
    }
    public function apiDone($id)
    {
        $task = Goal::find($id);
        $task->goals_is_achieved = 1;
        $task->goals_is_set = 0;

        $task->save();
        return response()->json([
            'message' => 'Data done successfully',
        ], 200);
    }
    public function apiEdit(Request $request, $id)
    {
        $task = Goal::find($id);
        $task->goals_name = $request->goals_name;
        $task->goals_deadline = $request->goals_deadline;
        $task->goals_conditions = $request->goals_conditions;
        $task->goals_reward = $request->goals_reward;
        $task->save();
        return response()->json([
            'message' => 'Data edited successfully',
            'data' => $task,
        ], 200);
    }
    public function apiGetUserName()
    {
        $user = User::find(Auth::id());
        return response()->json([
            'message' => 'Data read successfully',
            'data' => $user,
        ], 200);
    }

    public function good($hashID)
    {
        $goal =  [];
        $goal =  Goal::where('hashed_id', $hashID)->first();
        $goal->goals_good = $goal->goals_good + 1;
        $goal->save();
    }
    public function getFollowingNum($id)
    {

        $following = Follow::where('followee_id', $id)->count();
        return response()->json([
            'message' => 'Data read successfully',
            'data' => $following,
        ], 200);
    }

    public function apiAddGood($id)
    {

        Follow::create([
            'follower_id' => Auth::id(),
            'followee_id' => $id,
        ]);
        return response()->json([
            'message' => 'Data added successfully',
            'data' =>  $id . 'をフォローしました',
        ], 200);
    }
    public function  getFollower()
    {
        $user = User::find(12);
        $followersCount = Follow::get();
        return response()->json([
            'message' => 'Data read successfully',
            'data' => $followersCount,

        ], 200);
    }
}
