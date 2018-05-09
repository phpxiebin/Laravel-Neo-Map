<nav class="navbar navbar-static-top" role="navigation">
    <div class="navbar-header">
        <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse"
                class="navbar-toggle collapsed" type="button">
            <i class="fa fa-reorder"></i>
        </button>
        <a href="{{ url('admin') }}" class="navbar-brand">国际公益学院校友管理后台</a>
    </div>
    <div class="navbar-collapse collapse" id="navbar">
        <ul class="nav navbar-nav">
            <li class="dropdown">
                <a aria-expanded="false" role="button" href="javascript:void(0);" class="dropdown-toggle"
                   data-toggle="dropdown">权限管理<span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="/admin/entrust/users">用户</a></li>
                    <li><a href="/admin/entrust/roles">角色</a></li>
                    <li><a href="/admin/entrust/permissions">权限</a></li>
                    <!-- <li><a href="#">菜单</a></li> -->
                </ul>
            </li>
            <li class="dropdown">
                <a aria-expanded="false" role="button" href="javascript:void(0);" class="dropdown-toggle"
                   data-toggle="dropdown">机构管理<span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="/admin/organization">机构列表</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a aria-expanded="false" role="button" href="javascript:void(0);" class="dropdown-toggle"
                   data-toggle="dropdown">校友管理<span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="/admin/alumnus">校友列表</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a aria-expanded="false" role="button" href="javascript:void(0);" class="dropdown-toggle"
                   data-toggle="dropdown">活动管理<span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="/admin/activity">活动列表</a></li>
                    {{--<li><a href="#">活动banner</a></li>--}}
                </ul>
            </li>
        </ul>

        <ul class="nav navbar-top-links navbar-right">
            <li>
                <span>{{ auth('admin')->user()->name }}, 欢迎登录使用!</span>
            </li>
            <li>
                <a href="{{ url('admin/logout') }}">
                    <i class="fa fa-sign-out"></i> 退出
                </a>
            </li>
        </ul>
    </div>
</nav>
