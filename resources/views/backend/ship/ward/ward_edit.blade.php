@extends('admin.admin_master')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <div class="container-full">
        <!-- Content Header (Page header) -->

        <!-- Main content -->
        <section class="content">
            <div class="row">

                <!-- /.col -->
                <!--------- Add brand Page ------------------->
                <div class="col-12">

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Ward</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive">

                                <form method="POST" action="{{ route('ward.update', $ward->id) }}">
                                    @csrf

                                    <input type="hidden" name="id" value="{{ $ward->id }}">

                                    <div class="form-group">
                                        <h5>Province Select <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <select name="province_id" class="form-control">
                                                <option value="" selected="" disabled="">Select Province</option>
                                                @foreach($provinces as $province)
                                                    <option value="{{ $province->id }}" {{ $province->id == $ward->province_id ? 'selected' : '' }}>{{ $province->province_name }}</option>
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
                                                    <option value="{{ $district->id }}" {{ $district->id == $ward->province_id ? 'selected' : '' }}>{{ $district->district_name }}</option>
                                                @endforeach
                                            </select>

                                            @error('district_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <h5>Ward Name  <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="ward_name" class="form-control" value="{{ $ward->ward_name }}"> </div>
                                        @error('ward_name')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="text-xs-right">
                                        <input type="submit" class="btn btn-rounded btn-primary mb-5" value="Update">
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
