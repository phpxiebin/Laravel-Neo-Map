<nav class="navbar navbar-static-top" role="navigation">
    <div class="navbar-header">
        <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse"
                class="navbar-toggle collapsed" type="button">
            <i class="fa fa-reorder"></i>
        </button>
        <a href="{{ url('alumni') }}" class="navbar-brand">国际公益学院校友平台</a>
    </div>
    <div class="navbar-collapse collapse" id="navbar">
        <ul class="nav navbar-nav">
            <li class="dropdown">
                <a aria-expanded="false" role="button" href="javascript:void(0);" class="dropdown-toggle"
                   data-toggle="dropdown">信息管理<span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{{ url('alumni/info/organization') }}">机构信息</a></li>
                    <li><a href="{{ url('alumni/info/alumnus') }}">校友信息</a></li>
                </ul>
            </li>
            <li class="dropdown">
                <a aria-expanded="false" role="button" href="javascript:void(0);" class="dropdown-toggle"
                   data-toggle="dropdown">活动管理<span class="caret"></span></a>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="{{ url('alumni/activity') }}">活动列表</a></li>
                </ul>
            </li>
        </ul>

        <ul class="nav navbar-top-links navbar-right">
            <li>
                <span>{{ auth('alumni')->user()->name }}, 欢迎登录使用!</span>
            </li>
            <li>
                <a href="{{ url('alumni/logout') }}">
                    <i class="fa fa-sign-out"></i> 退出
                </a>
            </li>
        </ul>
    </div>
</nav>