@extends('layouts.headless') 
@section('content')
    <?php 
        $message = $message ?? '';
    ?>
        <script>
            //execute this script after adding new order
            window.parent.closePopUp('<?php echo $message?>');
        </script>
@endsection
