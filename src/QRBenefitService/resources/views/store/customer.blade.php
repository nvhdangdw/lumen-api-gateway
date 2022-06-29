@extends('layouts.headless') 
@section('content')
<div class="w-50 text-center" style="margin: auto">
    <div class="content-header">
            <div class="container-fluid">
                    <div class="row mb-2">
                            <div class="col-sm-6">
                                    <h1 class="m-0">Customer Detail</h1>
                            </div><!-- /.col -->
                    </div><!-- /.row -->
            </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
                <div class="row">
                        <div class="col-12">
                                <div class="card card-primary">
                                        <table class="table table-bordered">
                                                <tbody>
                                                        <tr>
                                                                <td>First name</td>
                                                                <td>{{ $customer->first_name }}</td>
                                                        </tr>
                                                        <tr>
                                                                <td>Last name</td>
                                                                <td>{{ $customer->last_name }}</td>
                                                        </tr>
                                                        <tr>
                                                                <td>Email</td>
                                                                <td>{{ $customer->email }}</td>
                                                        </tr>
                                                        <tr>
                                                                <td>Phone number</td>
                                                                <td>{{ $customer->phone_number }}</td>
                                                        </tr>
                                                        <tr>
                                                                <td>Qr Code</td>
                                                                <td>
                                                                        <!--?xml version="1.0" encoding="UTF-8"?-->
                                                                        {!! QrCode::size(200)->generate($qr_string); !!}

                                                                </td>
                                                        </tr>
                                                </tbody>
                                        </table>
                                </div>
                        </div>
                        <!-- /.col -->
                </div>
                <!-- /.row -->
        </div>
    </section>
</div>
<script>
    $(function(){
        successToast('New Customer added!');
         
    });
        
</script>
@endsection
