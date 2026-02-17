<nav class="iq-sidebar-menu">
    <ul id="iq-sidebar-toggle" class="iq-menu">
        <li class="{{ request()->route()->getName() == 'member.dashboard'? 'active': '' }}">
            <a href="{{ route('dashboard') }}" class="iq-waves-effect"><i
                    class="las la-home iq-arrow-left"></i><span>Dashboard</span></a>
        </li>










        <li class="{{ request()->route()->getName() == 'user.card'? 'active': '' }}">
            <a href="{{ route('user.card') }}" class="iq-waves-effect"><i
                    class="las la-id-card iq-arrow-left"></i><span>My vCard</span></a>
        </li>



            <li class="{{ request()->route()->getName() == 'member.stats'? 'active': '' }}">
                <a href="{{ route('member.stats') }}" class="iq-waves-effect"><i
                        class="las la-chart-bar iq-arrow-left"></i><span>Stats</span></a>
            </li>


            <li class="{{ request()->route()->getName() == 'user.profile'? 'active': '' }}">
                <a href="{{route('user.profile')}}" class="iq-waves-effect"><i
                        class="las la-user iq-arrow-left"></i><span>My Profile</span></a>
            </li>
            <li class="{{ request()->route()->getName() == 'user.profile.template'? 'active': '' }}">
                <a href="{{ route('user.profile.template') }}" class="iq-waves-effect"><i
                        class="las la-palette iq-arrow-left"></i><span>Template</span></a>
            </li>
            <li class="{{ request()->route()->getName() == 'user.socials'? 'active': '' }}">
                <a href="{{route('user.socials')}}" class="iq-waves-effect"><i
                        class="las la-user iq-arrow-left"></i><span>My Social</span></a>
            </li>
            <li class="{{ request()->route()->getName() == 'user.shop'? 'active': '' }}">
                <a href="{{route('user.shop')}}" class="iq-waves-effect"><i
                        class="las la-user iq-arrow-left"></i><span>My Shop</span></a>
            </li>


    </ul>
</nav>
<div class="p-3"></div>
