<section>
    <!-- Left Sidebar -->
    <aside id="leftsidebar" class="sidebar">
        <!-- User Info -->
        <div class="user-info">
            <div class="image">
                <img src="/assets/images/logo-nal.png" width="48" height="48" alt="User"/>
            </div>
            <div class="info-container">
                <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">NAL</div>
                <div class="email">{{\Illuminate\Support\Facades\Auth::user()->name}}</div>
                <div class="btn-group user-helper-dropdown">
                    <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                    <ul class="dropdown-menu pull-right">
                        <li><a href="javascript:void(0);"><i class="material-icons">person</i>Profile</a></li>
                        <li role="seperator" class="divider"></li>
                        <li><a href="/logout"><i class="material-icons">input</i>Sign Out</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- #User Info -->
        <!-- Menu -->
        <div class="menu">
            <ul class="list">
                <li class="header">MAIN NAVIGATION</li>
                <li {{Route::is('dashboard') ? 'class=active' : ''}}>
                    <a href="/">
                        <i class="material-icons">home</i>
                        <span>Home</span>
                    </a>
                </li>
                <li  {{Route::is('news') ? 'class=active' : ''}}>
                    <a href="javascript:void(0);" class="menu-toggle">
                        <i class="material-icons">assignment</i>
                        <span>News</span>
                    </a>
                    <ul class="ml-menu">
                        <li  {{Route::is('form.create') ? 'class=active' : ''}}>
                            <a href="/news/create">Post News</a>
                        </li>
                        <li  {{Route::is('news.list') ? 'class=active' : ''}}>
                            <a href="/news">List News</a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
        <!-- #Menu -->
        <!-- Footer -->
        <div class="legal">
            <div class="copyright">
                &copy; 2019 <a href="javascript:void(0);">NAL - Smart News</a>.
            </div>
            <div class="version">
                <b>Version: </b> 1.0.0
            </div>
        </div>
        <!-- #Footer -->
    </aside>
    <!-- #END# Left Sidebar -->
</section>
