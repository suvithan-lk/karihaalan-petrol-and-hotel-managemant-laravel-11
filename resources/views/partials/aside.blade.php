<!-- sidebar @s -->
<div class="nk-sidebar nk-sidebar-fixed is-light " data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">
            <a href="/" class="logo-link nk-sidebar-logo">
                <img class="logo-light logo-img" src="{{asset('images/logo.png')}}" alt="logo">
                <img class=" logo-img" src="{{asset('images/logo.png')}}">
                <img class="logo-img" src="{{asset('images/logo.png')}}" alt="logo-small">
            </a>
        </div>

    </div><!-- .nk-sidebar-element -->
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    <!-- .nk-menu-item -->
                    <!-- .nk-menu-heading -->
                            <li class="nk-menu-item">
                                <a href="{{route('dashboard')}}" class="nk-menu-link">
                                    <span class="nk-menu-icon"><em class="icon ni ni-grid-alt-fill"></em></span>
                                    <span class="nk-menu-text">Dashboard</span>
                                </a>
                            </li>
                    {{-- Petrol Section --}}
                    @if (auth()->user()->role == 'admin' || auth()->user()->role == 'petrol')
                        <li class="nk-menu-item has-sub">
                            <a href="#" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-icon"><i class="fas fa-gas-pump"></i></span>
                                <span class="nk-menu-text">Petrol Set</span>
                            </a>
                            <ul class="nk-menu-sub">
                                @if (auth()->user()->role == 'admin')
                                <li class="nk-menu-item"><li class="nk-menu-item">
                                    <a href="{{ route('dayendincome.index') }}" class="nk-menu-link">
                                        <span class="nk-menu-text">View Income</span>
                                    </a>
                                </li>

                                <li class="nk-menu-item">
                                    <a href="{{route('day-end-expenses.index')}}" class="nk-menu-link">
                                        <span class="nk-menu-text">view Expense</span>
                                    </a>
                                </li>

                                <li class="nk-menu-item">
                                    <a href="{{route('petrolmeter.form')}}" class="nk-menu-link">
                                        <span class="nk-menu-text">Petrol Meter Reading History</span>
                                    </a>
                                </li>

                                @endif


                                @if (auth()->user()->role == 'petrol')
                                <li class="nk-menu-item">
                                    <a href="{{ route('dayendincome.create') }}" class="nk-menu-link">
                                        <span class="nk-menu-text">Add Income</span>
                                    </a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="{{route('day-end-expenses.create')}}" class="nk-menu-link">
                                        <span class="nk-menu-text">Add Expense</span>
                                    </a>
                                </li>
                                    <li class="nk-menu-item">
                                        <a href="{{ route('petrolmeter') }}" class="nk-menu-link">
                                            <span class="nk-menu-text">Add Petrol Meter Reading</span>
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                        {{-- End of Petrol Section --}}

                    @endif

                        {{-- Hotel Section --}}

                        @if (auth()->user()->role == 'admin' || auth()->user()->role == 'hotel')
                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-icon"><i class="fas fa-hotel"></i></span>
                                    <span class="nk-menu-text">Hotel</span>
                                </a>
                                <ul class="nk-menu-sub">
                                    @if (auth()->user()->role == 'hotel')
                                        <li class="nk-menu-item">
                                            <a href="{{route('hotelincome.create')}}" class="nk-menu-link"><span class="nk-menu-text">Add Income</span></a>
                                        </li>
                                        <li class="nk-menu-item">
                                            <a href="{{route('hotel-expenses.create')}}" class="nk-menu-link"><span class="nk-menu-text">Add Expense</span></a>
                                        </li>
                                        <li class="nk-menu-item">
                                            <a href="{{route('roombookings.create')}}" class="nk-menu-link"><span class="nk-menu-text">Room Booking</span></a>
                                        </li>
                                    @endif
                                    @if (auth()->user()->role == 'admin')
                                        <li class="nk-menu-item">
                                            <a href="{{route('hotelrooms.create')}}" class="nk-menu-link"><span class="nk-menu-text">Room Add</span></a>
                                        </li>
                                        <li class="nk-menu-item">
                                            <a href="{{route('hotelrooms.index')}}" class="nk-menu-link">
                                                <span class="nk-menu-text">view Rooms</span>
                                            </a>
                                        </li>
                                        <li class="nk-menu-item">
                                            <a href="{{route('roombookings.show')}}" class="nk-menu-link">
                                                <span class="nk-menu-text">view Room Booking</span>
                                            </a>
                                        </li>

                                        <li class="nk-menu-item">
                                            <a href="{{route('hotelincome.index')}}" class="nk-menu-link"><span class="nk-menu-text">View Income</span></a>
                                        </li>
                                        <li class="nk-menu-item">
                                            <a href="{{route('hotel-expenses.index')}}" class="nk-menu-link"><span class="nk-menu-text">View Expense</span></a>
                                        </li>
                                    @endif

                                </ul>
                            </li>
                        @endif

                        {{-- End of Hotel Section --}}



                        {{-- User Section --}}
                        @if (auth()->user()->role == 'admin')
                            <li class="nk-menu-item has-sub">
                                <a href="#" class="nk-menu-link nk-menu-toggle">
                                    <span class="nk-menu-icon"><i class="fas fa-users"></i></span>
                                    <span class="nk-menu-text">Users</span>
                                </a>
                                <ul class="nk-menu-sub">
                                    <li class="nk-menu-item">
                                        <a href="{{route('users.index')}}" class="nk-menu-link"><span class="nk-menu-text">Add Users</span></a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        {{-- End of User Section --}}

                        {{-- Contact Section --}}
                        <li class="nk-menu-item has-sub">
                            <a href="#" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-icon"><i class="fas fa-phone"></i></span>
                                <span class="nk-menu-text">Contacts</span>
                            </a>
                            <ul class="nk-menu-sub">
                                <li class="nk-menu-item">
                                    <a href="{{route('contact.create')}}" class="nk-menu-link"><span class="nk-menu-text"> Add Contacts</span></a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="{{route('contact.view')}}" class="nk-menu-link"><span class="nk-menu-text">Contacts</span></a>
                                </li>

                            </ul>
                        </li>
                        {{-- End of Contact Section --}}
                </ul><!-- .nk-menu -->
            </div><!-- .nk-sidebar-menu -->
        </div><!-- .nk-sidebar-content -->
    </div><!-- .nk-sidebar-element -->
</div>
