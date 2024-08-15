<header class="mb-2">
    
    <nav class="navbar navbar-expand-md navbar-dark bg-dark"> 
        <a class="navbar-brand" href="/">Template&nbsp;Creator...&nbsp;<i class="fas fa-pen"></i></a>
        
        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#nav-bar">
            <span class="navbar-toggler-icon"></span>
        </button>
         
         
        <div class="collapse navbar-collapse" id="nav-bar">
            <ul class="navbar-nav mr-auto"></ul>
            <ul class="navbar-nav">
                @if (Auth::check())
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->name }}</a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <li class="text-center dropdown-item">
                                <a href="#">Profile</a>
                            </li>
                            <li class="dropdown-divider"></li>
                            <li class="text-center dropdown-item">
                                <a href="#">Template list</a>
                            </li>
                            <li class="dropdown-divider"></li>
                            <li class="text-center dropdown-item">
                                <a href="#">Logout</a>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="#" class="nav-link">Signup</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Login</a>
                    </li>
                @endif
	   		</ul> 
		</div>
   </nav>
</header>
