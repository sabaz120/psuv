

<!DOCTYPE html>
<!--
Template Name: Metronic - Bootstrap 4 HTML, React, Angular 10 & VueJS Admin Dashboard Theme
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: https://1.envato.market/EA4JP
Renew Support: https://1.envato.market/EA4JP
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html lang="en">
	<!--begin::Head-->
	<head><base href="../../../">
		<meta charset="utf-8" />
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>SIBE - PSUV</title>
		<link rel="shortcut icon" type="image/x-icon" href="{{ url('fian.png') }}">
		<meta name="description" content="User datatable listing" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
		<link rel="canonical" href="https://keenthemes.com/metronic" />
		<!--begin::Fonts-->
		<!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" /> -->
		<!--end::Fonts-->
		<!--begin::Global Theme Styles(used by all pages)-->
		<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
		<!--<link href="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.css') }}" rel="stylesheet" type="text/css" />-->
		<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('/css/custom.css') }}" rel="stylesheet" type="text/css" />
		<!--end::Global Theme Styles-->
		<!--begin::Layout Themes(used by all pages)-->
		<!--end::Layout Themes-->
		<!--<link rel="shortcut icon" href="assets/media/logos/favicon.ico" />-->
		<link rel="shortcut icon" type="image/x-icon" href="#">

		<script>

			function toggleUserOptions(){
				
				if($("#user-options-menu").hasClass("show")){
					$("#user-options-menu").removeClass("show")
				}else{
					$("#user-options-menu").addClass("show")
				}

			}

		</script>

		<style>

			.loader-cover-custom{
				position: fixed;
				left:0;
				right: 0;
				z-index: 99999999;
				background-color: rgba(0, 0, 0, 0.6);
				top: 0;
				bottom: 0;
			}

			.loader-custom {
				margin-top:45vh;
				margin-left: 45%;
				border: 16px solid #f3f3f3; /* Light grey */
				border-top: 16px solid #3498db; /* Blue */
				border-radius: 50%;
				width: 120px;
				height: 120px;
				animation: spin 2s linear infinite;
			}
			
			@keyframes spin {
				0% { transform: rotate(0deg); }
				100% { transform: rotate(360deg); }
			}

		</style>

		@stack("styles")

	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed page-loading">
		<!--begin::Main-->
		<!--begin::Header Mobile-->
		<div id="kt_header_mobile" class="header-mobile align-items-center header-mobile-fixed">
			<!--begin::Logo-->
			<a href="{{ url('/') }}">
				<img alt="Logo" class="w-45px" src="{{ url('psuv.png') }}" style="width: 100%;" />
			</a>
			<!--end::Logo-->
			<!--begin::Toolbar-->
			<div class="d-flex align-items-center">
				<!--begin::Aside Mobile Toggle-->
				{{--<button class="btn p-0 burger-icon burger-icon-left" >
					<span></span>
				</button>--}}
				<!--end::Aside Mobile Toggle-->
				<!--begin::Header Menu Mobile Toggle-->
				<button class="btn p-0 burger-icon ml-4" id="kt_aside_mobile_toggle">
					<span></span>
				</button>
				<!--end::Header Menu Mobile Toggle-->
				<!--begin::Topbar Mobile Toggle-->
				<button class="btn btn-hover-text-primary p-0 ml-2" id="kt_header_mobile_topbar_toggle">
					<span class="svg-icon svg-icon-xl">
						<!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg-->
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
							<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
								<polygon points="0 0 24 0 24 24 0 24" />
								<path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
								<path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
							</g>
						</svg>
						<!--end::Svg Icon-->
					</span>
				</button>
				<!--end::Topbar Mobile Toggle-->
			</div>
			<!--end::Toolbar-->
		</div>
		<!--end::Header Mobile-->
		<div class="d-flex flex-column flex-root" >
			<!--begin::Page-->
			<div class="d-flex flex-row flex-column-fluid page">
				<!--begin::Aside-->
				<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
					<!--begin::Brand-->
					<div class="brand flex-column-auto bg-white" id="kt_brand">
						<!--begin::Logo-->
						<a href="{{ url('/home') }}">
							<img alt="Logo" src="{{ url('psuv.png') }}" style="width: 100px;" />
						</a>
					</div>
					<!--end::Brand-->
					<!--begin::Aside Menu-->
					<div class="aside-menu-wrapper flex-column-fluid bg-main" id="kt_aside_menu_wrapper">
						<!--begin::Menu Container-->
						<div id="kt_aside_menu" class="aside-menu my-4 bg-main" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">
							<!--begin::Menu Nav-->
							<ul class="menu-nav">

								{{--<li class="menu-item" aria-haspopup="true">
									<a href="{{ url('/home') }}" class="menu-link" style="color: #fff !important">
										<i class="menu-icon flaticon-home-1" style="color: #fff !important"></i>
										<span class="menu-text text-white">Inicio</span>
									</a>
								</li>--}}
								
								@canany([
									'gestion comunidades',
									"gestion calles",
									"gestion usuarios",
									"gestion roles",
									"gestion candidatos",
									"gestion centros de votacion"
								])
								<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
									<a href="javascript:;" class="menu-link menu-toggle">
										<i class="menu-icon flaticon2-group text-white"></i>
										<span class="menu-text text-white">Módulos administrativos</span>
										<i class="menu-arrow"></i>
									</a>
									<div class="menu-submenu">
										<i class="menu-arrow"></i>
										<ul class="menu-subnav">
											<li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text text-white">Actions</span>
												</span>
											</li>
											@can('gestion usuarios')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('admin/usuarios') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Gestión de usuarios</span>
												</a>
											</li>
											@endcan
											@can('gestion roles')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('admin/roles') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Gestión de roles</span>
												</a>
											</li>
											@endcan
											@can('gestion comunidades')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('admin/comunidad') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Gestión de comunidades</span>
												</a>
											</li>
											@endcan
											@can('gestion calles')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('admin/calles') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Gestión de calles</span>
												</a>
											</li>
											@endcan
											@can('gestion candidatos')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('admin/candidatos') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Gestionar candidatos</span>
												</a>
											</li>
											@endcan
											@can('gestion centros de votacion')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('admin/centros_votacion') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Centros de votación</span>
												</a>
											</li>
											@endcan
	
										</ul>
									</div>
								</li>
								@endcanany

								@canany([
									'raas ubch',
									"raas jefe comunidad",
									"raas jefe calle",
								])
                                <li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
									<a href="javascript:;" class="menu-link menu-toggle">
										<i class="menu-icon flaticon2-group text-white"></i>
										<span class="menu-text text-white">RAAS</span>
										<i class="menu-arrow"></i>
									</a>
									<div class="menu-submenu">
										<i class="menu-arrow"></i>
										<ul class="menu-subnav">
											<li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text text-white">Actions</span>
												</span>
											</li>
											@can('raas ubch')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ route('raas.ubch') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Gestionar UBCH</span>
												</a>
											</li>
											@endcan
											@can('raas jefe comunidad')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ route('raas.jefe-comunidad') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Gestionar Jefe de Comunidad</span>
												</a>
											</li>
											@endcan
											@can('raas jefe calle')
                                            <li class="menu-item" aria-haspopup="true">
												<a href="{{ route('raas.jefe-calle') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Gestionar Jefe de Calles</span>
												</a>
											</li>
											@endcan
										</ul>
									</div>
								</li>
								@endcanany

								@can('rep listado electores')
								<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
									<a href="javascript:;" class="menu-link menu-toggle">
										<i class="menu-icon flaticon2-group text-white"></i>
										<span class="menu-text text-white">REP</span>
										<i class="menu-arrow"></i>
									</a>
									<div class="menu-submenu">
										<i class="menu-arrow"></i>
										<ul class="menu-subnav">
											<li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text text-white">Actions</span>
												</span>
											</li>
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('/listado/rep') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Listado de electores</span>
												</a>
											</li>
										</ul>
									</div>
								</li>
								@endcan

								@can('gestion voto duro')
								<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
									<a href="javascript:;" class="menu-link menu-toggle">
										<i class="menu-icon flaticon2-group text-white"></i>
										<span class="menu-text text-white">Voto duro</span>
										<i class="menu-arrow"></i>
									</a>
									<div class="menu-submenu">
										<i class="menu-arrow"></i>
										<ul class="menu-subnav">
											<li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text text-white">Actions</span>
												</span>
											</li>
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ route('raas.voto-duro') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Gestionar votos duros</span>
												</a>
											</li>
										</ul>
									</div>
								</li>
								@endcan

								@can('nucleos familiares')
								<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
									<a href="javascript:;" class="menu-link menu-toggle">
										<i class="menu-icon flaticon2-group text-white"></i>
										<span class="menu-text text-white">1XFamilia</span>
										<i class="menu-arrow"></i>
									</a>
									<div class="menu-submenu">
										<i class="menu-arrow"></i>
										<ul class="menu-subnav">
											<li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text text-white">Actions</span>
												</span>
											</li>
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ route('raas.jefe-familia') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Gestionar Núcleos Familiares</span>
												</a>
											</li>
										</ul>
									</div>
								</li>
								@endcan
								
								@canany([
									"reporte estructura raas",	
									"reporte movilizacion electores",	
									"reporte carga",
									"reporte listado jefes",
								])
								<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
									<a href="javascript:;" class="menu-link menu-toggle">
										<i class="menu-icon flaticon2-group text-white"></i>
										<span class="menu-text text-white">Reportes</span>
										<i class="menu-arrow"></i>
									</a>
									<div class="menu-submenu">
										<i class="menu-arrow"></i>
										<ul class="menu-subnav">
											<li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text text-white">Actions</span>
												</span>
											</li>
											@can("reporte estructura raas")
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('raas/reportes/estructura') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Estructura de base</span>
												</a>
											</li>
											@endcan
											
											@can("reporte movilizacion electores")
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('raas/reportes/movilizacion_electores') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Movilización de electores</span>
												</a>
											</li>
											@endcan

											@can("reporte carga")
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('reporte-carga') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Reporte de carga</span>
												</a>
											</li>
											@endcan

											@can("reporte listado jefes")
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('listado-jefes') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Listado</span>
												</a>
											</li>
											@endcan
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('reportes/listado-participacion') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Listado participación</span>
												</a>
											</li>
										</ul>
									</div>
								</li>
								@endcanany

								<!-- Inicio instituciones -->
								@canany(['instituciones',"instituciones listado"])
								<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
									<a href="javascript:;" class="menu-link menu-toggle">
										<i class="menu-icon flaticon2-group text-white"></i>
										<span class="menu-text text-white">Instituciones</span>
										<i class="menu-arrow"></i>
									</a>
									<div class="menu-submenu">
										<i class="menu-arrow"></i>
										<ul class="menu-subnav">
											<li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text text-white">Actions</span>
												</span>
											</li>
											@can('instituciones')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('instituciones/trabajadores') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Asociar trabajador</span>
												</a>
											</li>
											@endcan
											@can('instituciones listado')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('instituciones/listado') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Listado</span>
												</a>
											</li>
											@endcan
										</ul>
									</div>
								</li>
								@endcanany
								<!-- Fin instituciones -->

								<!-- Inicio movimientos -->
								@canany(['movimientos sociales','movimientos sociales listado'])
								<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
									<a href="javascript:;" class="menu-link menu-toggle">
										<i class="menu-icon flaticon2-group text-white"></i>
										<span class="menu-text text-white">Movimientos sociales</span>
										<i class="menu-arrow"></i>
									</a>
									<div class="menu-submenu">
										<i class="menu-arrow"></i>
										<ul class="menu-subnav">
											<li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text text-white">Actions</span>
												</span>
											</li>
											@can('movimientos sociales')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('movimientos/trabajadores') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Asociar personal</span>
												</a>
											</li>
											@endcan
											@can('movimientos sociales listado')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('movimientos/listado') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Listado</span>
												</a>
											</li>
											@endcan
										</ul>
									</div>
								</li>
								@endcanany
								<!-- Fin movimientos -->
								
								@can('votaciones cuadernillo')
								<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
									<a href="javascript:;" class="menu-link menu-toggle">
										<i class="menu-icon flaticon2-group text-white"></i>
										<span class="menu-text text-white">Votaciones</span>
										<i class="menu-arrow"></i>
									</a>
									<div class="menu-submenu">
										<i class="menu-arrow"></i>
										<ul class="menu-subnav">
											<li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text text-white">Actions</span>
												</span>
											</li>

											<li class="menu-item" aria-haspopup="true">
												<a href="{{ route('cuadernillo') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Cuadernillos</span>
												</a>
											</li>

											<li class="menu-item" aria-haspopup="true">
												<a href="{{ route('votaciones.centro-votacion') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Centro de votación</span>
												</a>
											</li>

											<li class="menu-item" aria-haspopup="true">
												<a href="{{ route('votaciones.gestionar-participacion') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Gestionar participación</span>
												</a>
											</li>
	
										</ul>
									</div>
								</li>
								@endcan
								
								@can('metas ubch')
								<li class="menu-item" aria-haspopup="true">
									<a href="{{ url('/metas-ubch') }}" class="menu-link" style="color: #fff !important">
										<i class="menu-icon flaticon2-group text-white" style="color: #fff !important"></i>
										<span class="menu-text text-white">Metas UBCH</span>
									</a>
								</li>
								@endcan

								@can('sala tecnica')
								<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
									<a href="javascript:;" class="menu-link menu-toggle">
										<i class="menu-icon flaticon2-group text-white"></i>
										<span class="menu-text text-white">Sala técnica</span>
										<i class="menu-arrow"></i>
									</a>
									<div class="menu-submenu">
										<i class="menu-arrow"></i>
										<ul class="menu-subnav">
											<li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text text-white">Actions</span>
												</span>
											</li>

											<li class="menu-item" aria-haspopup="true">
												<a href="{{ route('asociar-personal') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Asociar personal</span>
												</a>
											</li>
	
										</ul>
									</div>
								</li>
								@endcan

								@can('cierre mesa')
								<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
									<a href="javascript:;" class="menu-link menu-toggle">
										<i class="menu-icon flaticon2-group text-white"></i>
										<span class="menu-text text-white">Cierre de mesa</span>
										<i class="menu-arrow"></i>
									</a>
									<div class="menu-submenu">
										<i class="menu-arrow"></i>
										<ul class="menu-subnav">
											<li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text text-white">Actions</span>
												</span>
											</li>

											<li class="menu-item" aria-haspopup="true">
												<a href="{{ route('cierre-mesa.candidatos') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Por candidatos</span>
												</a>
											</li>

											<li class="menu-item" aria-haspopup="true">
												<a href="{{ route('cierre-mesa.partidos') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Por partidos</span>
												</a>
											</li>
	
										</ul>
									</div>
								</li>
								@endcan

								@canany([
									"comandos regional",
									"comandos municipal",
									"comandos parroquial",
									"comandos enlace",
								])
								<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
									<a href="javascript:;" class="menu-link menu-toggle">
										<i class="menu-icon flaticon2-group text-white"></i>
										<span class="menu-text text-white">Comandos</span>
										<i class="menu-arrow"></i>
									</a>
									<div class="menu-submenu">
										<i class="menu-arrow"></i>
										<ul class="menu-subnav">
											<li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text text-white">Actions</span>
												</span>
											</li>
											@can('comandos regional')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('comandos/regionales') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Regional</span>
												</a>
											</li>
											@endcan
											@can('comandos municipal')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('comandos/municipales') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Municipal</span>
												</a>
											</li>
											@endcan
											@can('comandos parroquial')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('comandos/parroquiales') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Parroquial</span>
												</a>
											</li>
											@endcan
											@can('comandos enlace')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('comandos/enlaces') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Enlace</span>
												</a>
											</li>
											@endcan
										</ul>
									</div>
								</li>
								@endcanany
								
								@canany([
									"estadistica cierre mesa candidatos",
									"estadistica cierre mesa partidos",
								])
								<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
									<a href="javascript:;" class="menu-link menu-toggle">
										<i class="menu-icon flaticon2-group text-white"></i>
										<span class="menu-text text-white">Estadística</span>
										<i class="menu-arrow"></i>
									</a>
									<div class="menu-submenu">
										<i class="menu-arrow"></i>
										<ul class="menu-subnav">
											<li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text text-white">Actions</span>
												</span>
											</li>
											@can('estadistica cierre mesa candidatos')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('estadistica/cierre-mesa') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Cierre de mesa candidatos</span>
												</a>
											</li>
											@endcan
											@can('estadistica cierre mesa partidos')
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('estadistica/cierre-mesa-partidos') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Cierre de mesa partidos</span>
												</a>
											</li>
											@endcan
											
										</ul>
									</div>
								</li>
								@endcanany
								<li class="menu-item menu-item-submenu" aria-haspopup="true" data-menu-toggle="hover">
									<a href="javascript:;" class="menu-link menu-toggle">
										<i class="menu-icon flaticon2-group text-white"></i>
										<span class="menu-text text-white">Participación</span>
										<i class="menu-arrow"></i>
									</a>
									<div class="menu-submenu">
										<i class="menu-arrow"></i>
										<ul class="menu-subnav">
											<li class="menu-item menu-item-parent" aria-haspopup="true">
												<span class="menu-link">
													<span class="menu-text text-white">Actions</span>
												</span>
											</li>
											<li class="menu-item" aria-haspopup="true">
												<a href="{{ url('participacion/gestionar') }}" class="menu-link">
													<i class="menu-bullet menu-bullet-line">
														<span></span>
													</i>
													<span class="menu-text text-white">Gestionar participación</span>
												</a>
											</li>
										</ul>
									</div>
								</li>
							</ul>
							<!--end::Menu Nav -->
						</div>
						<!--end::Menu Container-->
					</div>
					<!--end::Aside Menu-->
				</div>
				<!--end::Aside-->
				<!--begin::Wrapper-->
				<div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
					<!--begin::Header-->
					<div id="kt_header" class="header header-fixed">
						<!--begin::Container-->
						<div class="container-fluid d-flex align-items-stretch justify-content-between">
							<!--begin::Header Menu Wrapper-->
							<div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
								<!--begin::Header Menu-->
								<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
									
								</div>
								<!--end::Header Menu-->
							</div>
							<!--end::Header Menu Wrapper-->
							<!--begin::Topbar-->
							<div class="topbar">
								
								<!--begin::User-->
								<div class="topbar-item">
									<div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle" onclick="toggleUserOptions()">
										<span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hola,</span>
										<span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">{{ \Auth::user()->name }}</span>
										<!--<span class="symbol symbol-35 symbol-light-success">
											<span class="symbol-label font-size-h5 font-weight-bold"></span>
										</span>-->
									</div>
									<!--begin::Dropdown-->
									<div id="user-options-menu" class="dropdown-menu" style="right: 0; float:right !important; left: unset; padding-left: 1rem; padding-bottom: 1rem;">
										<!--begin:Header-->
										
											<a href="{{ url('/logout') }}" class="btn btn-success btn-sm font-weight-bold font-size-sm mt-2">Cerrar sesión</a>
										
										<!--end:Nav-->
									</div>
									<!--end::Dropdown-->
								</div>
								<!--end::User-->
							</div>
							<!--end::Topbar-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Header-->
					<!--begin::Content-->
					<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
						
						@yield("content")
						
					</div>
					<!--end::Content-->
					<!--begin::Footer-->
					<div class="footer bg-white py-4 d-flex flex-lg-column" id="kt_footer">
						<!--begin::Container-->
						<div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
							<!--begin::Copyright-->
							<div class="text-dark text-center" style="width: 100%;">
								<span class="text-muted font-weight-bold mr-2">2021 - 2022©</span>
								<a href="h#" target="_blank" class="text-dark-75 text-hover-primary">Copyright</a>
							</div>
							<!--end::Copyright-->
							<!--begin::Nav-->
							<!--end::Nav-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Footer-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Main-->

		
		
		
		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop">
			<span class="svg-icon">
				<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<polygon points="0 0 24 0 24 24 0 24" />
						<rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
						<path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero" />
					</g>
				</svg>
				<!--end::Svg Icon-->
			</span>
		</div>
		<!--end::Scrolltop-->
		<!--begin::Global Config(global config for global JS scripts)-->
		<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
		<!--end::Global Config-->
		<!--begin::Global Theme Bundle(used by all pages)-->
		<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
		<!--<script src="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>-->
		<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
		<!--end::Global Theme Bundle-->
		<!--begin::Page Scripts(used by this page)-->
		<!--<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>-->
		<script src="{{ asset('/js/app.js') }}"></script>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

		<script>
			  function swalAlert(type="error",title="Error",msg=""){
    Swal.fire({
      title: title,
      html: msg,
      icon: type//success,error,warning,info,question
    })
  }//swalAlert

  function errorsToHtmlList(errors) {

        const isObject = errors instanceof Object && !Array.isArray(errors);

        if (errors == null || !isObject) return "";

        const ul = document.createElement('ul');

        for (let key in errors) {
            const li = document.createElement('li');
            li.innerText = `${errors[key][0]}`;
            ul.appendChild(li);
        }

        return ul.outerHTML;
  }
		</script>

		@stack("scripts")

		<!--end::Page Scripts-->
	</body>
	<!--end::Body-->
</html>