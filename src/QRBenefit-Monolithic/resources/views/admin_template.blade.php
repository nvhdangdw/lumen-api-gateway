<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin MBA</title>

	<!-- Google Font: Source Sans Pro -->
	<link rel="stylesheet"
		href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="{{ asset('/bower_components/plugins/fontawesome-free/css/all.min.css') }}">
	<!-- Theme style -->
	<link rel="stylesheet" href="{{ asset('/bower_components/dist/css/adminlte.min.css') }}">
	<link rel="stylesheet" href="{{ asset('/bower_components/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
	@yield('link')
</head>

<body class="hold-transition sidebar-mini">
	<div class="wrapper">

		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>
			</ul>

			<!-- Right navbar links -->
			<ul class="navbar-nav ml-auto">

				<!-- Notifications Dropdown Menu -->
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="#">
						<i class="far fa-user"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

						<a href="{{ route('admin_logout') }}" class="dropdown-item text-right" >
							Logout
						</a>
					</div>
				</li>
			</ul>
		</nav>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<!-- Brand Logo -->
			<a href="{{ route('admin_dashbroard') }}" class="brand-link">
				<img src="{{ asset('/bower_components/dist/img/AdminLTELogo.png') }}" alt="Admin MBA Logo"
					class="brand-image img-circle elevation-3" style="opacity: .8">
				<span class="brand-text font-weight-light">Admin MBA</span>
			</a>

			<!-- Sidebar -->
			<div class="sidebar">
				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
						data-accordion="false">
						<li class="nav-item">
							<a href="{{ route('admin_customer_index') }}" class="nav-link @yield('customer_active') ">
								<i class="nav-icon fa fa-address-book"></i>
								<p>
									Customer
								</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ route('admin_store_index') }}" class="nav-link @yield('store_active') ">
								<i class="nav-icon fa fa-address-card"></i>
								<p>
									Store
								</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ route('admin_order_index') }}" class="nav-link @yield('order_active') ">
								<i class="nav-icon fa fa-bars"></i>
								<p>
									Order
								</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ route('admin_discount_index') }}" class="nav-link @yield('discount_active') ">
								<i class="nav-icon fas fa-th"></i>
								<p>
									Discount
								</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ route('admin_user_index') }}" class="nav-link @yield('user_active') ">
								<i class="nav-icon fa fa-user-circle"></i>
								<p>
									User
								</p>
							</a>
						</li>
					</ul>
				</nav>
				<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			@yield('content')
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->

		<!-- Control Sidebar -->
		<aside class="control-sidebar control-sidebar-dark">
			<!-- Control sidebar content goes here -->
			<div class="p-3">
				<h5>Title</h5>
				<p>Sidebar content</p>
			</div>
		</aside>
		<!-- /.control-sidebar -->

		<!-- Main Footer -->
		<footer class="main-footer">
			<!-- To the right -->
			<div class="float-right d-none d-sm-inline">
				Anything you want
			</div>
			<!-- Default to the left -->
			<strong>Copyright 2021 <a href="https://adminlte.io">MBA</a>.</strong> All rights
			reserved.
		</footer>
	</div>
	<!-- ./wrapper -->

	<!-- REQUIRED SCRIPTS -->

	<!-- jQuery -->
	<script src="{{ asset('/bower_components/plugins/jquery/jquery.min.js') }}"></script>
	<!-- Bootstrap 4 -->
	<script src="{{ asset('/bower_components/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
	<!-- AdminLTE App -->
	<script src="{{ asset('/bower_components/dist/js/adminlte.min.js') }}"></script>
	@yield('script')
</body>

</html>