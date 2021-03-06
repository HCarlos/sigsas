{{--<div class="main-container bgc-white" >--}}
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar sidebar-fixed expandable sidebar-light" data-backdrop="true" data-dismiss="true" data-swipe="true">
        <div class="sidebar-inner">

            <div class="ace-scroll flex-grow-1 mt-1px" data-ace-scroll="{}"><!-- optional `nav` tag -->
                <nav class="pt-3" aria-label="Main">
                    <ul class="nav flex-column has-active-border">
                        <li class="nav-item {{ str_contains(url()->current(), 'dashboard') ? 'active': ''}}">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="nav-icon fa fa-home"></i>
                                <span class="nav-text fadeable">Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item {{ str_contains(url()->current(), 'listDenuncias') ? 'active': ''}}">
                            <a class="nav-link" href="{{ route('listDenuncias') }}">
                                <i class="nav-icon fa fa-archive"></i>
                                <span class="nav-text fadeable">SIAC</span>
                            </a>
                        </li>

                        <li class="nav-item  {{ str_contains(url()->current(), 'userList') ? 'active': ''}} ">
                            <a href="{{ route('userList') }}" class="nav-link  ">
                                <i class="nav-icon fa fa-users"></i>
                                <span class="nav-text fadeable">Usuarios</span>
                            </a>
                        </li>

                        @if (Auth::user()->hasRole('Administrator|SysOp'))
                            <li class="nav-item  {{ str_contains(url()->current(), 'archivosConfig') ? 'active': ''}} ">
                                <a href="{{route('archivosConfig')}}" class="nav-link  ">
                                    <i class="nav-icon fa fa-file-excel"></i>
                                    <span class="nav-text fadeable">Archivos</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </nav>

            </div><!-- /.ace-scroll -->

        </div>
    </div>

{{--</div>--}}
