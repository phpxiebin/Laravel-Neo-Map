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
                                    <th>名称</th>
                                    <th>角色名称</th>
                                    <th>描述</th>
                                    <th colspan="2">
                                        @if(entrust('admin-entrust-roles-create'))
                                        <a href="{{ URL::route('admin.entrust.roles.create') }}" class="btn btn-primary btn-block">创建</a>
                                            @endif
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td>{{ $role->id }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>{{ $role->display_name }}</td>
                                        <td>{{ $role->description }}</td>
                                        <td width="80">
                                            @if(auth('admin')->user()->may('admin-entrust-roles-edit'))
                                            <a class="btn btn-primary" href="{{ URL::route('admin.entrust.roles.edit')}}?id={{$role->id}}">编辑</a>
                                            @endif
                                        </td>
                                        <td width="80">
                                            @if(auth('admin')->user()->may('admin-entrust-roles-destroy'))
                                            {!! Form::open(['route' => ['admin.entrust.roles.destroy'], 'method' => 'post']) !!}
                                            {!! Form::hidden('id', $role->id) !!}
                                            {!! Form::submit('删除', ['class' => 'btn btn-danger', 'onclick' => 'return confirm("确定?");']) !!}
                                            {!! Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {!! $roles->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
