@extends('dynamicACL::layout')

@section('tab_title')
	لیست نقش‌ها
@endsection

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">لیست نقش ها</h3>

				<div class="card-tools">
					@if(auth()->user()->hasPermission('roles.create'))
					<a href="{{ route('admin.roles.create') }}" role="button"
						class="btn btn-success btn-sm btn-rounded">ایجاد نقش</a>
					@endif
				</div>
			</div>
			<!-- /.card-header -->
			<div class="card-body table-responsive p-0">
				<table class="table table-hover">
					<tr>
						<th>نام نقش</th>
						<th>تعداد کاربر</th>
						<th></th>
					</tr>
					@foreach($roles as $role)
					<tr>
						<td>{{ $role->name }}</td>
						<td>{{ $role->getUsersCount() }}</td>
						<td>
							@if (! $role->is_super_admin())
							<div class="btn-group" role="group" aria-label="Basic mixed styles example" dir="ltr">
								<a href="{{ route('admin.roles.delete',$role->id)}}" class="btn btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="حذف">❌</a>
								<a href="{{ route('admin.roles.edit', $role->id)}}" class="btn btn-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="ویرایش">📝</a>
							</div>
							@endif
						</td>
					</tr>
					@endforeach
				</table>
				{{ $roles->render() }}
			</div>
			<!-- /.card-body -->
		</div>
		<!-- /.card -->
	</div>
</div><!-- /.row -->
@endsection
