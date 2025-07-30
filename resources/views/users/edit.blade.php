@extends('layouts.dashboard')
@section('title', 'Edit User')
@section('content')

<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="card h-100">
                    <div class="card-inner">
                        <div class="card-head">
                            <h5 class="card-title">Edit User</h5>
                        </div>

                        {{-- Success Message --}}
                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        {{-- User Edit Form --}}
                        <form action="{{ route('users.update', ['user' => $user->id]) }}" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="form-group">
                                <label for="name" class="form-label">Name:</label>
                                <div class="form-control-wrap">
                                    <input type="text" name="name" id="name" class="form-control @if ($errors->has('name')){{'is-invalid'}} @endif" placeholder="Enter full name" value="{{ old('name', $user->name) }}" required>
                                    @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number:</label>
                                <div class="form-control-wrap">
                                    <input type="text" name="phone" id="phone" class="form-control @if ($errors->has('phone')){{'is-invalid'}} @endif" placeholder="Enter phone number" value="{{ old('phone', $user->phone) }}" required>
                                    @if ($errors->has('phone'))
                                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">Password:</label>
                                <div class="form-control-wrap">
                                    <input type="password" name="password" id="password" class="form-control @if ($errors->has('password')){{'is-invalid'}} @endif" placeholder="Enter password">
                                    @if ($errors->has('password'))
                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>
                                <small>Leave blank if you don't want to change the password.</small>
                            </div>

                            <div class="form-group">
                                <label for="role" class="form-label">Role:</label>
                                <div class="form-control-wrap">
                                    <select name="role" id="role" class="form-control @if ($errors->has('role')){{'is-invalid'}} @endif" required>
                                        <option value="" disabled>Select a role</option>
                                        <option value="admin" @if($user->role == 'admin') selected @endif>Admin</option>
                                        <option value="petrol" @if($user->role == 'petrol') selected @endif>Petrol</option>
                                        <option value="hotel" @if($user->role == 'hotel') selected @endif>Hotel</option>
                                    </select>
                                    @if ($errors->has('role'))
                                    <div class="invalid-feedback">{{ $errors->first('role') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Update User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
