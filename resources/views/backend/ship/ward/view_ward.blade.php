@extends('admin.admin_master')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <div class="container-full">
        <!-- Content Header (Page header) -->

        <!-- Main content -->
        <section class="content">
            <div class="row">

                <div class="col-8">

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Ward List</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                    <tr>
                                        <th>Province Name</th>
                                        <th>District Name</th>
                                        <th>Ward Name</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($wards as $item)
                                        <tr>
                                            <td>{{ $item->province->province_name }}</td>
                                            <td>{{ $item->district->district_name }}</td>
                                            <td>{{ $item->ward_name }}</td>
                                            <td>
                                                <a href="{{ route('ward.edit', $item->id) }}" class="btn btn-info" title="Edit Data"><i class="fa fa-pencil"></i></a>
                                                <a href="{{ route('ward.delete', $item->id) }}" class="btn btn-danger" title="Delete Data" id="delete"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                    <!-- /.box -->
                </div>
                <!-- /.col -->
                <!--------- Add brand Page ------------------->
                <div class="col-4">

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Add Ward</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive">

                                <form method="POST" action="{{ route('ward.store') }}">
                                    @csrf

                                    <div class="form-group">
                                        <h5>Province Select <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <select name="province_id" class="form-control">
                                                <option value="" selected="" disabled="">Select Province</option>
                                                @foreach($provinces as $province)
                                                    <option value="{{ $province->id }}">{{ $province->province_name }}</option>
                                                @endforeach
                                            </select>

                                            @error('province_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <h5>District Select <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <select name="district_id" class="form-control">
                                                <option value="" selected="" disabled="">Select District</option>
                                                @foreach($districts as $district)
                                                    <option value="{{ $district->id }}">{{ $district->district_name }}</option>
                                                @endforeach
                                            </select>

                                            @error('district_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <h5>Ward Name<span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="ward_name" class="form-control"> </div>
                                        @error('ward_name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="text-xs-right">
                                        <input type="submit" class="btn btn-rounded btn-primary mb-5" value="Add New">
                                    </div>
                                </form>

                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                    <!-- /.box -->
                </div>
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->

    </div>

    <script type="text/javascript">
        $(document).ready(function () {
            $('select[name="province_id"]').on('change', function () {
                var province_id = $(this).val();
                if(province_id) {
                    $.ajax({
                        url: "{{ url('/shipping/ward/district') }}/"+province_id,
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            var d = $('select[name="district_id"]').empty();
                            $.each(data, function (key, value) {
                                $('select[name="district_id"]').append('<option value=" '+value.id+' ">' + value.district_name + '</option>');
                            });
                        }
                    });
                } else {
                    alert('danger');
                }
            });
        });
    </script>
@endsection
