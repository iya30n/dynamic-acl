@extends('dynamicACL::layout')
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
						<td>{{ $role->users_count }}</td>
						<td>{!! $role->action_buttons !!}</td>
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
