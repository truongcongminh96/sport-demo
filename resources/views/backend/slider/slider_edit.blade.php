@extends('admin.admin_master')
@section('admin')
    <div class="container-full">
        <!-- Content Header (Page header) -->

        <!-- Main content -->
        <section class="content">
            <div class="row">

                <!-- /.col -->
                <!--------- Add Slider Page ------------------->
                <div class="col-12">

                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title">Edit Slider</h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="table-responsive">

                                <form method="POST" action="{{ route('slider.update', $sliders->id) }}" enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" name="id" value="{{ $sliders->id }}">
                                    <input type="hidden" name="old_image" value="{{ $sliders->slider_image }}">

                                    <div class="form-group">
                                        <h5>Slider Title  <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="title" class="form-control" value="{{ $sliders->title }}"> </div>

                                    </div>


                                    <div class="form-group">
                                        <h5>Slider Description  <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="text" name="description" class="form-control" value="{{ $sliders->description }}"> </div>

                                    </div>



                                    <div class="form-group">
                                        <h5>Slider Image  <span class="text-danger">*</span></h5>
                                        <div class="controls">
                                            <input type="file" name="slider_image" class="form-control"> </div>
                                        @error('slider_image')
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
@endsection
