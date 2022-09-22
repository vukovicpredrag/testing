@extends('adminlte::page')

@section('title', 'Status')

@section('content_header')
    <h1>Status</h1>
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addStatus"> Add Status </button>

@stop

@section('content')

    @if (\Session::has('success'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('success') !!}</li>
            </ul>
        </div>
    @endif

    <table id="status" class="display" style="width:100%">
        <thead>
        <tr>
            <th>#</th>
            <th>Status</th>

            @if(Auth::user()->role == 'admin' )
                 <th>Manage</th>
            @endif


        </tr>
        </thead>
        <tbody>

        @php $i = 1; @endphp
        @foreach($statuses as $status)

        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $status -> name }}</td>

            @if(Auth::user()->role == 'admin' )

                <td>
                    <button  type="button" class="btn btn-primary editStatus" data-toggle="modal" data-status-id="{{ $status -> id }}" data-status="{{ $status -> name }}" data-target="#editStatus"> Edit  </button>
                    <button  type="button" class="btn btn-danger delete-status" data-href="{{ route('status.destroy', $status -> id ) }}" > Delete  </button>

                </td>

            @endif


        </tr>

        @endforeach


        </tbody>
        <tfoot>
        <tr>

        </tr>
        </tfoot>
    </table>

    <!-- Modal - add status -->
    <div class="modal fade" id="addStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="saveStatusForm" method="post" action="{{ route('status.store') }}">
                        @csrf

                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" >


                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="saveStatusBtn" type="button" class="btn btn-primary">Add Status</button>
                </div>
            </div>
        </div>
    </div

<!-- Modal edit status -->
    <div class="modal fade" id="editStatus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Status</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="editStatusForm" method="post" action="{{ route('editStatus') }}">
                        @csrf

                        <label for="name">Status</label>
                        <input id="statusInput" type="text" name="name" class="form-control" value="">
                        <input type="hidden" id="statusId" name="statusId" class="form-control" value="">

                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="editStatusBtn" type="button" class="btn btn-primary">Save</button>
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
            $('#status').DataTable();
        } );


        $('#saveStatusBtn').click( function () {

            $('#saveStatusForm').submit()

        })

        $('.editStatus').click( function (e) {

            let statusId = $(this).data('status-id');
            let status = $(this).data('status');

            $("#statusInput").val(status);
            $('#statusId').val(statusId);

        })

        $('#editStatusBtn').click( function () {

            $('#editStatusForm').submit()

        })

        $('.delete-status').click( function (e) {

            e.preventDefault();

            var href = $(this).data('href');

            if( confirm('Are you sure?') ) {

                $.ajax({
                    url: href,
                    type: 'DELETE',
                    success: function () {

                        location.reload();

                    }

                })
            }

        })

    </script>
@stop