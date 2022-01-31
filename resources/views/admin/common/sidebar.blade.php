<aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User Profile-->
                <div class="user-profile">
                    <div class="user-pro-body">
                        <div><img src="{{URL::to('/')}}/public/assets/images/users/placeholder.png" alt="user-img" class="img-circle"></div>
                        <div class="dropdown">
                            <a href="javascript:void(0)" class="dropdown-toggle u-dropdown link hide-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Auth::user()->name}} <span class="caret"></span></a>
                            <div class="dropdown-menu animated flipInY">
                                <!-- text-->
<!--                                <a href="javascript:void(0)" class="dropdown-item"><i class="ti-user"></i> My Profile</a>
                                 text
                                <a href="javascript:void(0)" class="dropdown-item"><i class="ti-wallet"></i> My Balance</a>
                                 text
                                <a href="javascript:void(0)" class="dropdown-item"><i class="ti-email"></i> Inbox</a>
                                 text
                                <div class="dropdown-divider"></div>-->
                                <!-- text-->
<!--                                <a href="javascript:void(0)" class="dropdown-item"><i class="ti-settings"></i> Account Setting</a>
                                 text
                                <div class="dropdown-divider"></div>-->
                                <!-- text-->
                                <a href="{{URL::to('/')}}/admin/logout" class="dropdown-item"><i class="fas fa-power-off"></i> Logout</a>
                                <!-- text-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        
                        <li class="{{$active == 'dashboard'?'active':''}}"> <a class="waves-effect waves-dark" href="{{URL::to('/')}}/admin/dashboard" aria-expanded="false"><i class="icon-speedometer"></i><span class="hide-menu">Dashboard </span></a></li>
                        <li class="{{$active == 'categories'?'active':''}}"> <a class="waves-effect waves-dark" href="{{URL::to('/')}}/admin/categories" aria-expanded="false"><i class="ti-layout-grid2"></i><span class="hide-menu">Songs Categories</span></a></li>
                        <li class="{{$active == 'songs'?'active':''}}"> <a class="waves-effect waves-dark" href="{{URL::to('/')}}/admin/songs" aria-expanded="false"><i class="ti-music-alt"></i><span class="hide-menu">Songs</span></a></li>
                        <li class="{{$active == 'artists'?'active':''}}"> <a class="waves-effect waves-dark" href="{{URL::to('/')}}/admin/artists" aria-expanded="false"><i class="fas fa-guitar"></i><span class="hide-menu">Artists</span></a></li>
                        <li class="{{$active == 'albums'?'active':''}}"> <a class="waves-effect waves-dark" href="{{URL::to('/')}}/admin/albums" aria-expanded="false"><i class="fas fa-compact-disc"></i><span class="hide-menu">Albums</span></a></li>
                        <li class="{{$active == 'weekly_selection'?'active':''}}"> <a class="waves-effect waves-dark" href="{{URL::to('/')}}/admin/weekly_selection" aria-expanded="false"><i class="fas fa-check"></i><span class="hide-menu">Weekly Selection</span></a></li>
                        <li class="{{$active == 'advertisement'?'active':''}}"> <a class="waves-effect waves-dark" href="{{URL::to('/')}}/admin/advertisement" aria-expanded="false"><i class="fas fa-check"></i><span class="hide-menu">Advertisement</span></a></li>
<!--                        <li class="{{$active == 'users'?'active':''}}"> <a class="waves-effect waves-dark" href="{{URL::to('/')}}/admin/users" aria-expanded="false"><i class="fas fa-users"></i><span class="hide-menu">Users</span></a></li>-->
                        
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>