<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('/main.css') }}">
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- <link rel="stylesheet" href="{{ asset('/sanitize.css') }}"> --}}

</head>

<div class="page">
 
    <div class="center">
        <a href='/goals/create'>create</a>
        
    </div>
    
    <div class="col center">
      
        <h1>管理画面</h1>
        
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            title
                        </th>
                        <th scope="col" class="px-6 py-3">
                            deadline
                        </th>
                        <th scope="col" class="px-6 py-3">
                            reward
                        </th>
                        <th scope="col" class="px-6 py-3">
                            user
                        </th>
                        <th scope="col" class="px-6 py-3">
                            edit
                        </th>
                        <th scope="col" class="px-6 py-3">
                            delete
                        </th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($own_posts as $goal)
                    <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $goal->goals_name}}
                        </th>

                        <td class="px-6 py-4">
                            {{ $goal->goals_deadline}}
                        </td>
                        <td class="px-6 py-4">
                            {{ $goal->goals_reward}}
                        </td>
                        <td class="px-6 py-4">
                            {{ $goal->user->name }}
                        </td>
                        
                        
                        <td class="px-6 py-4">
                            <div class="edit">
                                <a  class="top_button" href="/goals/{{ $goal->id }}/edit">edit</a>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <form action="/goals/{{ $goal->id }}" id="form_{{ $goal->id }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="deleteGoal({{ $goal->id }})">delete</button>
                            </form>
                            
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <Admin />




    <style>

    </style>
    <script>
        function deleteGoal(id) {
            'use strict'

            if (confirm('削除すると復元できません。\n本当に削除しますか？')) {
                document.getElementById(`form_${id}`).submit();
            }
        }

 


    </script>

</html>