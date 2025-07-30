@extends('layouts.dashboard')
@section('title', 'User Management')
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card h-100">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Add New User</h5>
                        </div>

                        {{-- Success Message --}}
                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        {{-- User Form --}}
                        <form action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="name" class="form-label">Name:</label>
                                <div class="form-control-wrap">
                                    <input type="text" name="name" id="name" class="form-control @if ($errors->has('name')){{'is-invalid'}} @endif" placeholder="Enter full name" required>
                                    @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number:</label>
                                <div class="form-control-wrap">
                                    <input type="text" name="phone" id="phone" class="form-control @if ($errors->has('phone')){{'is-invalid'}} @endif" placeholder="Enter phone number" required>
                                    @if ($errors->has('phone'))
                                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">Password:</label>
                                <div class="form-control-wrap">
                                    <input type="password" name="password" id="password" class="form-control @if ($errors->has('password')){{'is-invalid'}} @endif" placeholder="Enter password" required>
                                    @if ($errors->has('password'))
                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="role" class="form-label">Role:</label>
                                <div class="form-control-wrap">
                                    <select name="role" id="role" class="form-control @if ($errors->has('role')){{'is-invalid'}} @endif" required>
                                        <option value="" disabled selected>Select a role</option>
                                        <option value="admin">Admin</option>
                                        <option value="petrol">Petrol</option>
                                        <option value="hotel">Hotel</option>
                                    </select>
                                    @if ($errors->has('role'))
                                    <div class="invalid-feedback">{{ $errors->first('role') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Add User</button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- User Table --}}
                <div class="card mt-4">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">User List</h5>
                        </div>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $key => $user)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No users found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
