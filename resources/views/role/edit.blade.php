@extends('dynamicACL::layout')

@section('tab_title')
    {{__('dynamicACL::roles.edit_the_role', ['role_name' => $role->name])}}
@endsection

@section('content')
    <!-- Main content -->
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">
            <!-- general form elements -->
            <!-- form start -->
            <form class="form-horizontal" method="POST" action="{{ route('admin.roles.update', $role->id) }}">
            @csrf
            @method('PATCH')

            <!-- role name -->
                <div class="row">
                    <div class="col col-md-4">
                        <div class="form-group">
                            <input type="text" placeholder="{{__('dynamicACL::roles.insert_role_name')}}" name="name" class="form-control"
                                   value="{{ old('name', $role->name) }}">
                        </div>
                    </div>
                    <!-- /col-md-4 -->

                    <div class="col col-md-4">
                        @if(auth()->user()->hasPermission('role.store'))
                            <div class="form-group"><input type="submit" class="btn btn-sm btn-rounded btn-success"
                                                           value="{{__('dynamicACL::roles.edit_role')}}">
                            </div>
                        @endif
                    </div>
                    <!-- /col-md-4 -->

                </div>
                <!-- /role name -->

                <div class="row">
                    <div class="col col-md-4">
                        <div class="card">
                            <div class="card-header text-center">{{__('dynamicACL::roles.default_permissions')}}</div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <ul>
                                            <li>
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       id="permission_check_full_access"
                                                       name="access[fullAccess]"
                                                       {{ (isset($role->permissions['fullAccess']) && $role->permissions['fullAccess'] == 1) ? 'checked' : '' }}
                                                       value="1">
                                                <label class="form-check-label" for="permission_check_full_access">{{__('dynamicACL::roles.full_access')}}</label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @foreach($permissions as $key => $value)
                        <div class="col col-md-4">

                            <div class="card">

                                <div class="card-header text-center">
                                    {{ str_replace('.', ' => ', $key) }}
                                </div>
                                <!-- /card header -->

                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-12">
                                            <ul style="column-count: 4; column-gap: 2rem; list-style: none; font-size: 13px;">
                                                @foreach($value as $keyAccess)
                                                    <?php $check = isset($role->permissions[$key][$keyAccess['name']]); ?>
                                                    <li>
                                                        <input class="form-check-input"
                                                               type="checkbox"
                                                               id="permission_check_{{$keyAccess['name']}}"
                                                               name="access[{{$key}}][{{$keyAccess['name']}}]"
                                                               {{ $check ? 'checked' : '' }}
                                                               value="1">
                                                        <label class="form-check-label" for="permission_check_{{$keyAccess['name']}}">{{ $keyAccess['name'] }}</label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <!-- /col-12 -->

                                    </div>
                                    <!-- /row -->


                                </div>
                                <!-- /card body -->

                            </div>
                            <!-- /card -->

                        </div>
                        <!-- /col-md-4 -->
                    @endforeach
                </div>

            </form>
        </div>
        <!--/.col (left) -->
    </div>
    <!-- /.row -->
@endsection
