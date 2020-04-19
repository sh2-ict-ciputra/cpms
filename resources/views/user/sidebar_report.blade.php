<header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>A</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Admin</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
    </nav>
</header>
<aside class="main-sidebar sidebar-dark-primary">
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ url('/')}}/assets/dist/img/logo-ciputra_original.png" class="img-circle" alt="User Image">
                <br>
            </div>
            <div class="pull-left info">
                <p>{{ $user->user_name }}</p>
                <i class="fa fa-circle text-success"></i> Online
            </div>
        </div>
        <hr class="mb-5" style="border-top:0">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <ul class="sidebar-menu" data-widget="tree">
                <li><a href="{{ url('/')}}/report/project/document/?id={{ $budget->project->id}}"><i class="fa fa-file"></i> Home</a></li>
                <li><a href="{{ url('/')}}/report/project/aktivitas/"><i class="fa fa-file"></i> Dokumen</a></li>
                <li><a href="{{ url('/')}}/report/project/budget/"><i class="fa fa-file"></i> Aktivitas</a></li>
                <li><a href="{{ url('/')}}/logout"><i class="fa fa-sign-out"></i> Budget</a></li>
            </ul>
        </ul>
    </section>
</aside>