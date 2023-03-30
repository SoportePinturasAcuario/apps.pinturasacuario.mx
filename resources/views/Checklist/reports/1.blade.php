</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Checklist #{{ $checklist->id }}</title>
	<meta charset="UTF-8" />
	<link
	rel="stylesheet"
	href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
	integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk"
	crossorigin="anonymous"
	/>
	<style>
		*{
			margin: 0!important;
			padding: 0!important;
		}
		body {
			background-color: #333;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
		}
		.page {
			background-color: #ffff;
			height: 792px;
			padding: 50px!important;
			width: 612px;
			position: relative;
			display: block;
			flex-direction: column;
			margin-bottom: 10px!important;
		}
		.page-header, .page-body, .page-footer{
			padding: 0;
			margin: 0;
			width: 100%;
		}
		.page-header{
			height: 20%;
			overflow: hidden;
			/*outline: 1px solid red;*/
		}
		.page-body{
			min-height: 70%;
			max-height: 100%;
			overflow: hidden;
			/*outline: 1px solid blue;*/
		}	
		.page-footer{
			height: 10%;
			overflow: hidden;
			/*outline: 1px solid orange;*/
		}
		.page-header .logo{
			width: 50%;
		}
		.d-flex{
			display: flex;
		}
		.flex-direction-column{
			flex-direction: column;
		}
		.justify-start{
			justify-content: flex-start;
		}
		.justify-center{
			justify-content: center;
		}
		.justify-end{
			justify-content: flex-end;
		}
		.align-center{
			align-items: center;
		}
		.align-start{
			align-items: flex-start;
		}
		.align-end{
			align-items: flex-end;
		}
		table, table tr{
			width: 100%
		}
		table tr td{
			padding: 5px;
		}
		*{
			font-size: x-small;
		}		
		@media print {
			body {
				padding: 0;
				height: 100%;
				margin: 0;
			}
			.page {
				height: 100vh;
				width: 100%;
				padding: none;
				border: none;
				margin: none;
				page-break-after: always;
			}
			*{
				font-size: medium;
			}
		}
		@page{
			content: normal;
		}

		.photo-container{
			height: 100%;
			width: 100%;
			display: flex;
			justify-content: center;
			position: relative;
			flex-direction: column;
			outline: 1px solid #999;
			overflow: hidden;
		}
		.photo-container .title{
			display: flex;
			padding: 10px!important;
			width: 100%;
			border-bottom: 1px solid #999;
			position: absolute;
			left: 0;
			top: 0;
		}
		.photo-container img{
			width: 100%;
			height: auto;
		}		
	</style>
</head>
<body>
	<div class="page">
		<div class="page-header">
			<div class="row">
				<div class="col d-flex align-start flex-direction-column">
					<img class="logo" src="http://apps.pinturasacuario.mx/assets/logo-colores_que_cambian_mi_vida.png" alt="">
					<span><a target="_blanck" href="https://pinturasacuario.com">pinturasacuario.com</a></span>
					<span>CEDIS Pinturas Acuario</span>
					<span>Rafael Navas García</span>
					<span>Estado de México</span>
					<span><a target="_blanck" href="tel:5553212500">55 5321 2500</a></span>
				</div>
				<div class="col d-flex flex-direction-column justify-center align-end">
					<h2 class="mb-4">Checklist Transporte: #{{ $checklist->id }}</h2>
					<span><strong>Fecha: </strong>{{ $checklist->created_at }}</span>
				</div>
			</div>
		</div>
		<div class="page-body">
			<div class="container-fluid">
				<div class="row row-cols-3 mt-1">
					<div class="col-12 mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Cliente(s):</strong>
							<table>
								<tbody>
									@foreach($customers AS $key => $customer)

									<tr>
										<td>{{ $key + 1}})</td>
										<td><strong>Número:</strong> {{ $customer->folio }}</td>
										<td><strong>Nombre:</strong> {{ $customer->name }}</td>
									</tr>
									@endforeach
								</tbody>
								<tbody></tbody>
							</table>
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Trasportista:</strong>
							{{ $checklist->transportista->name }}
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Fletera:</strong>
							{{ $checklist->fletera->name }}
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Típo de unidad:</strong>
							{{ $checklist->unidad->name }}
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Número de tarjeta de circulación:</strong>
							{{ $checklist->tcirculacion }}
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Número de placa:</strong>
							{{ $checklist->placa }}
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Número de sello:</strong>
							{{ $checklist->nsello }}
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Nombre del conductor:</strong>
							{{ $checklist->nconductor }}
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Número de licencia:</strong>
							{{ $checklist->nlicencia }}
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Número de seguro trasporte:</strong>
							{{ $checklist->nseguro }}
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Ruta:</strong>
							{{ $checklist->ruta->name }}
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Hora de cita:</strong>
							{{ $checklist->hcita }} hrs.
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>GPS:</strong>
							{{ $checklist->gps }}
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Contrato de GPS:</strong>
							{{ $checklist->ngps }}
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Número de packing list:</strong>
							{{ $checklist->npackinglist }}
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Camión cargado por:</strong>
							{{ $checklist->cargador->name }}
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Camión inspeccionado por:</strong>
							{{ $checklist->inspector->name }}
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Costo del flete:</strong>
							{{ $checklist->fcosto }}
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Hora llegada del camón:</strong>
							{{ $checklist->hllegada }} hrs.
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Hora salida del camión:</strong>
							{{ $checklist->hsalida }} hrs.
						</div>
					</div>
					<div class="col mb-1">
						<div class="d-flex flex-direction-column">
							<strong>Estancia del camión:</strong>
							{{ $checklist->estancia }}
						</div>
					</div>
				</div>
				<div class="row row-cols-12 mt-3">
					<div class="col">
						<table class="table">
							<thead class="thead-dark">
								<tr>
									<th colspan="3">
										<center><strong>FACTURAS</strong></center>
									</th>
								</tr>
								<tr>
									<th scope="col" width="10%"><center>#</center></th>
									<th scope="col" width="45%">Folio de factura</th>
									<th scope="col" width="45%" style="text-align: right;">Monto de factura</th>
								</tr>
							</thead>
							<tbody>
								@if(!empty($checklist->facturas))
									@foreach($checklist->facturas as $key => $factura)
										<tr>
											<td><center>{{ $key + 1}})</center></td>
											<td>{{ $factura->folio }}</td>
											<td  style="text-align: right;">${{ $factura->monto }}</td>
										</tr>
									@endforeach
								@else
									<tr>
										<td colspan="1000">
											<center>
												sin facturas
											</center>
										</td>
									</tr>
								@endif
							</tbody>
							<tfooter>
								<tr>
									<td></td>
									<td></td>
									<td  style="text-align: right;">
										<strong>Total:</strong>
										${{ $checklist->total() }}
									</td>
								</tr>
							</tfooter>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	@if(!empty($checklist->photos))
		@foreach($checklist->photos->chunk(4) AS $photos)
			<div class="page">
				<div class="row" style="height: 100%">
					@foreach($photos AS $key => $photo)
						<div class="col-6" style="height: 50%;">
							<div class="row" style="height: 100%;">
								<div class="col-12" style="display: flex; align-items: center; height: 20px; border-bottom: 1px solid #ccc;">
									{{ $key + 1 }}) {{ $photo->name }}
								</div>
								<div class="col-12" style="display: flex; align-items: center; height: 100%; justify-content: center; overflow: hidden;">
									<img src="{{@asset($photo->url)}}" alt="{{@asset($photo->url)}}" style="display: block; max-width: 100%; max-height: calc(100% - 20px)">
								</div>
							</div>
						</div>
					@endforeach
				</div>
			</div>
		@endforeach		
	@endif
</div>


<script>
	// window.print();
</script>
</body>
</html>
