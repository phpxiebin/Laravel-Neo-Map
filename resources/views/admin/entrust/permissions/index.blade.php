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
                                    <th>展示名称</th>
                                    <th>名称</th>
                                    <th colspan="2">
                                        @if(entrust('admin-entrust-permissions-create'))
                                        <a href="{{ URL::route('admin.entrust.permissions.create') }}" class="btn btn-primary btn-block">创建</a>
                                            @endif
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($permissions as $permission)
                                    <tr>
                                        <td>{{ $permission->id }}</td>
                                        <td>{{ $permission->display_name }}</td>
                                        <td>{{ $permission->name }}</td>

                                        <td width="80">
                                            @if(auth('admin')->user()->may('admin-entrust-permissions-edit'))
                                            <a class="btn btn-primary" href="{{URL::route('admin.entrust.permissions.edit')}}?id={{$permission->id}}">编辑</a>
                                            @endif
                                        </td>
                                        <td width="80">
                                            @if(auth('admin')->user()->may('admin-entrust-permissions-destroy'))
                                            {!! Form::open(['route' => ['admin.entrust.permissions.destroy'], 'method' => 'post']) !!}
                                            {!! Form::hidden('id', $permission->id) !!}
                                            {!! Form::submit('删除', ['class' => 'btn btn-danger', 'onclick' => 'return confirm("确定?");']) !!}
                                            {!!  Form::close() !!}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            {!! $permissions->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
