@extends('admin.layouts.app')
@include('flash::message')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="wrapper" style="padding-top:20px">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ ($breadcrumb = Breadcrumbs::current()) ? $breadcrumb->title : 'Fallback Title' }}</h5>
                    </div>
                    <div class="ibox-content">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>错误!</strong> <br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="project-list">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>用户名</th>
                                    <th>角色名称</th>
                                    <th colspan="2">
                                        @if(entrust('admin-entrust-users-create'))
                                        <a href="{{ URL::route('admin.entrust.users.create') }}" class="btn btn-primary btn-block">创建</a>
                                            @endif
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($adminUsers as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->display_name }}</td>
                                        @if(auth('admin')->user()->may('admin-entrust-users-edit'))
                                        <td width="80"><a class="btn btn-primary" href="{{ URL::route('admin.entrust.users.edit')}}?id={{$user->id}}">编辑</a></td>
                                        @endif
                                        <td width="80">
                                            @if(auth('admin')->user()->may('admin-entrust-users-destroy'))
                                                {!! Form::open(['route' => ['admin.entrust.users.destroy'], 'method' => 'post']) !!}
                                                {!! Form::hidden('id', $user->id) !!}
                                                {!! Form::submit('删除', ['class' => 'btn btn-danger', 'onclick' => 'return confirm("确定?");']) !!}
                                                {!! Form::close() !!}</td>
                                            @endif
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {!! $adminUsers->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
