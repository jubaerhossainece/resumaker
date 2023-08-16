@extends('admin.layouts.app')
@push('styles')
    <style>
        .card{
            border-radius: 10px;
        }

        .card-header:first-child{
            border-radius: 10px 10px 0px 0px;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Platform admin list </h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover" id="admin-table">
                            <thead></thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

    </div>
@endsection

@push('scripts')

    <script>
        function getData() {

            $('#admin-table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: true,
                destroy: true,
                stateSave: true,

                ajax: {
                    url: "{{route('admins.list')}}"
                },
                columns: [{
                        data: "DT_RowIndex",
                        name: 'DT_RowIndex',
                        title: "Serial",
                        searchable: false,
                        orderable: false
                    },
                    {
                        data: 'image',
                        name: 'image',
                        title: 'Photo',
                        orderable: false
                    },
                    {
                        data: 'name',
                        name: 'name',
                        title: 'Name',
                        orderable: false
                    },
                    {
                        data: 'email',
                        name: 'email',
                        title: 'Email',
                        orderable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        title: 'Status',
                        orderable: false
                    },
                    {
                        data: 'action',
                        title: 'Action',
                        searchable: false,
                        orderable: false
                    },
                ]
            });
        }

        $(document).ready(function(){
            getData();
        });
    </script>
@endpush