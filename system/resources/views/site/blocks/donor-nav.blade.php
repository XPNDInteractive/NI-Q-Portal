<div class="navbar navbar-expand-md donor-nav bg-dark  p-0 py-3 ">
    
        <div class="container  py-1">
            <button class="btn btn-clean navbar-toggler" data-toggle="collapse" data-target="#navbarTogglerDemo01" ><i class="fas fa-bars"></i> MENU</button>
            
          
                <ul class="navbar-nav d-none d-md-flex">
                   
                   @foreach($donor_menu->items()->get() as $item)
                        @foreach($userPermissions as $permmission)
                            @if(!is_null($item->permissions()->where('name', $permmission->name)->first()))
                                <li class="nav-item"><a class="nav-link" href="{{url($item->path)}}">{{$item->name}}</a></li>
                                @php break; @endphp
                            @endif
                        @endforeach
                    @endforeach
                    
                </ul>
           
           <ul class="nav ml-auto">
                <li class="nav-item mr-2">
                    <a href="{{Route('milkkit_send')}}" class="nav-link">Request Milk Kit</a>
                </li>

                 <li class="nav-item mr-2">
                    <a href="{{Route('milkkit_send')}}" class="nav-link">Request Milk Kit</a>
                </li>
                
                <li class="nav-item ">
                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }} 
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
                
            </ul>
        </div>
    </div>
</div>
 <div style="margin-top: -1.5rem; border-top: #111 1px solid; " class="collapse navbar-collapse bg-dark donor-nav-dropdown mb-4" id="navbarTogglerDemo01">
   
        <ul class="nav flex-column p-0 text-white">
           
            @foreach($donor_menu->items()->get() as $item)
                @foreach($userPermissions as $permmission)
                    @if(!is_null($item->permissions()->where('name', $permmission->name)->first()))
                     <li class="nav-item p-0">
                        <div class="container">
                            <a class="nav-link" href="{{Route('home')}}"> Home</a>
                        </div>
                    </li>
                    @endif
                @endforeach
            @endforeach
        </ul>
    
</div>