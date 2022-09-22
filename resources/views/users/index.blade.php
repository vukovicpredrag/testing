@extends('adminlte::page')

@section('title', 'Users')

@section('content_header')
    <h1 style="display: inline-block; padding-right: 10px">Users</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUser"> Add User  </button>

@stop

@section('content')

    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    <table id="users" class="display" style="width:100%">
        <thead>
        <tr>
            <th>Full Name</th>
            <th>Emal</th>
            <th>Role</th>
            <th>Manage</th>
        </tr>
        </thead>
        <tbody>


        @foreach($users as $user)

            <tr>
                <td>{{ $user -> name }}</td>
                <td>{{ $user -> email }}</td>
                @if( $user -> role == 'admin')
                     <td style="color:red; font-weight: bold">{{ $user -> role }}</td>
                @else
                    <td>{{ $user -> role }}</td>
                @endif
                <td>

                    <button  type="button" class="btn btn-primary changePass" data-toggle="modal" data-user-id="{{ $user -> id }}" data-target="#changePassword"> Change Password  </button>
                    <button  type="button" class="btn btn-danger delete-user" data-user-id="{{ $user -> id }}" > Delete  </button>


                </td>
            </tr>

        @endforeach


        </tbody>
        <tfoot>
        <tr>

        </tr>
        </tfoot>
    </table>

    <!-- Modal change password -->
    <div class="modal fade" id="changePassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="editUserForm" method="post" action="{{ route('editUser') }}">
                        @csrf
                        <label for="name">New passsword</label>
                        <input type="text" name="password" class="form-control">
                        <input type="hidden" id="userIdInput" name="userId" class="form-control" value="">

                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="editUserBtn" type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div

    <!-- Modal - addUser -->
    <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="saveUserForm" method="post" action="{{ route('createUser') }}">
                    @csrf

                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" >

                        <label for="name">Email</label>
                        <input type="text" name="email" class="form-control" >

                        <label for="name">Passsword</label>
                        <input type="text" name="password" class="form-control" >

                        <label for="name">Role</label>
                        <select type="select" name="role" class="form-control" >
                            <option value="admin">Admin</option>
                            <option value="editor">Editor</option>

                        </select>

                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="saveUserBtn" type="button" class="btn btn-primary">Add User</button>
                </div>
            </div>
        </div>
    </div
@stop

@section('css')
@stop

@section('js')
    <script>

        $(document).ready(function() {
            $('#users').DataTable();
        } );


        $('#saveUserBtn').click( function () {

            $('#saveUserForm').submit()

        })


        $('#editUserBtn').click( function () {

            $('#editUserForm').submit()

        })


        $('.changePass').click( function (e) {

           let userId = $(this).attr('data-user-id');

           $('#userIdInput').val(userId);


        })

        $('.delete-user').click( function (e) {

            e.preventDefault();

            var userId = $(this).data('user-id');

            if( confirm('Are you sure?') ) {

                $.ajax({
                    url: "{{ route('deleteUser') }}",
                    type: 'POST',
                    data: {userId: userId},
                    success: function () {

                        location.reload();

                    }

                })
            }

        })

    </script>
@stop