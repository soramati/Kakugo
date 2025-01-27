<!DOCTYPE HTML>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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

<body>
    @include('layouts.navigation')

    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif
    
    <div class="create_page ">
        <form class="max-w-md mx-auto" action="/goals" method="POST">
            @csrf
            <div class="relative z-0 w-full mb-5 group" >
                <input type="text" 
                
                id="title" 
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                name="goal[goals_name]"  value="{{ old('goal.goals_name') }}" 
                placeholder="" required />
                <label for="title" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">タイトル</label>
                <p class="title__error" style="color:red">{{ $errors->first('goal.goals_name') }}</p>
            </div>
            {{-- モーダル --}}
            <div id="hidden" class="hidden">
                <div class="modal_content">
                    <div class="modal_text">
                        <h1>いつまでに達成したいですか?</h1>
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="datetime-local" 
                            name="goal[goals_deadline]"
                            value="{{ old('goal.goals_deadline') }}" id="date" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                            <label for="date" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">達成したい日</label>
                        </div>
                        <div class="btn_wrapper">
                            <button type="submit" class="text-white bg-green-400 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">スタート</button>
                            <button type="submit" class="text-white bg-gray-400 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">あとでやる</button>
                        </div>
                    </div> 
                    <button id="modal_btn" onclick="toggleModal()">トグル</button>
                </div>  
            </div>
                       {{-- モーダルここまで --}}
            

            <div class="relative z-0 w-full mb-5 group">
                <input type="text" 
                name="goal[goals_conditions]" 
                id="goals_conditions" 
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" 
                placeholder=" "
                value="{{ old('goal.goals_conditions') }}"
                required />
                <label for="goals_conditions" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">達成する条件</label>
                <p class="body__error" style="color:red">{{ $errors->first('goal.goals_conditions') }}</p>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <input type="text"
                name="goal[goals_reward]" 
                id="goals_reward" 
                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                placeholder=" "
                value="{{ old('goal.goals_reward') }}"
                required />
                <label for="goals_reward" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">達成したら貰えるごほうび</label>
                <p class="body__error" style="color:red">{{ $errors->first('goal.goals_reward') }}</p>
            </div>
          <button type="submit" class="text-white bg-green-400 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">できた!</button>
        </div>
    </form>
</div>
<button id="modal_btn" onclick="toggleModal()">トグル</button>
</body>
<script>
  
 
 function toggleModal(){
    const modal = document.getElementById('hidden');
    // modal.classList.toggle('hidden');
    modal.classList.toggle('day_modal');
    console.log('クリックされました');
 }
</script>
    

<style scoped>
    .hidden {
    display: none;
    }
.create_page{
    width: 95%;

    min-height: 80vh;
    margin-top: 10vh;
    margin-left: auto;
    margin-right: auto; 
}
.d_col{
    display: flex;
    flex-direction: column;
    /* align-items: center; */
}

.btn_wrapper{
    display: flex;
    justify-content: space-between;
    width: 100%;
}
    
</style>
</html>