<header>


    <div onclick="goHome()" class="logo_container">
      <h1 class="logo_top" >Kakugo.</h1>
      {{-- <h1 class="logo_bottom">motivation</h1> --}}

    </div>

        <!-- Responsive Settings Options -->
      
        <div class="right_container">
            <x-responsive-nav-link :href="route('profile.edit')">
                {{ __('Profile') }}
            </x-responsive-nav-link>
            <span class="nav_span"></span>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-responsive-nav-link :href="route('logout')"
                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                {{ __('Log Out') }}
            </x-responsive-nav-link>
          </form>

        </div>
  </header>
  


  <style scoped>

   
    
    </style>

    <script>
      function goHome() {
        location.href = '/';
      }
    </script>