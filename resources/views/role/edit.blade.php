@extends('dynamicACL::layout')

@section('tab_title')
    {{__('dynamicACL::roles.edit_the_role', ['role_name' => $role->name])}}
@endsection

@section('content')
<!-- Main content -->
<form method="POST" action="{{ route('admin.roles.update', $role->id) }}">
    @csrf
    @method('PATCH')
    <div class="row">

        <div class="col-md-4">
            <div class="form-group">
                <input id="route" type="text" placeholder="{{__('dynamicACL::roles.insert_role_name')}}" name="name" class="form-control rounded"
                value="{{ old('name', $role->name) }}">
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <div class="form-check text-left col-md-4">
                    <input type="checkbox" class="form-check-input"
                            id="permission_check_fullAccess"
                            name="fullAccess"
                            {{ (isset($role->permissions['fullAccess']) && $role->permissions['fullAccess'] == 1) ? 'checked' : '' }}
                            value="1">

                    <label class='form-check-label' for="permission_check_fullAccess">{{__('Full Access')}}</label>
                </div>
            </div>
        </div>

        <div class="col col-md-1">
            @if(auth()->user()->hasPermission('admin.roles.update'))
                <div class="form-group"><input type="submit" class="btn btn-md btn-rounded btn-success"
                    value="{{__('dynamicACL::roles.update_role')}}"></div>
            @endif
        </div>
    </div>

    <div class="row">
        @foreach($permissions as $key => $value)
        @php
        $hasAdminDotPrefix = strpos($key, 'admin.') !== false;

        $dashKey = str_replace('.', '-', $key);
        $entityName = $hasAdminDotPrefix ? ucfirst(str_replace('admin.', '', $key)) : ucfirst($key);
        @endphp
        <div class="col-md-4 col-sm-12">
            <div class="card text-left" style="height: 100%;">

                <div class="mt-3 ml-1">

                    <div class="form-check d-flex justify-content-between w-100">

                        <div class="@if(config('easy_panel.rtl_mode')) mr-2 @endif">
                            <h4 class="align-self-center">{{ __($entityName) }}</h4>
                        </div>

                        <div class="@if(config('easy_panel.rtl_mode')) ml-1 @endif">
                            <input type="checkbox" class="form-check-input" onchange="selectAll(this, '{{$dashKey}}')">
                        </div>

                    </div>
                </div>
                <!-- /card header -->

                <hr style="width:98%;">

                <div class="card-body row">
                    @foreach($value as $keyAccess)
                    <?php $check = isset($role->permissions[$key][$keyAccess['name']]); ?>
                    <div class="form-check d-flex justify-content-between w-100">

                        <div>
                            <label class="form-check-label align-self-center"
                                for="permission_check_{{$dashKey}}_{{$keyAccess['name']}}">{{ __(ucfirst($keyAccess['name'])) }}</label>
                        </div>

                        <div>
                            <input type="checkbox" class="form-check-input {{$dashKey}}"
                                id="permission_check_{{$dashKey}}_{{$keyAccess['name']}}"
                                name="access[{{$key}}][{{$keyAccess['name']}}]"
                                {{ $check ? 'checked' : '' }}
                                value="1">
                        </div>

                    </div>
                    @endforeach
                </div>
                <!-- /card-body -->
            </div>
        </div>
        <!-- /col-md-4 -->
        @endforeach
    </div>
</form>
<!-- /.row -->
@endsection



<!-- <style>
    @ if(!config('easy_panel.rtl_mode'))
    .form-check {
        padding: 0;
    }
    @ endif

    .selectAll {
        margin-left: -30px;
    }
</style> -->


<!-- <script>

    function selectAll(selectAll, dashKey) {
        document.querySelectorAll('.' + dashKey).forEach(item => {
            if(item.checked !== selectAll.checked)
                item.click()
        })
    }

</script> -->

