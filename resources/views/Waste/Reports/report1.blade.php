<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8" />
	<title>Reporte de daños de materiales {{ $waste->id }}</title>
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
			width: 792px;
			height: 612px;
			padding: 50px!important;
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
		.doc-title{
			text-align:right;
		}
		table, table tr{
			width: 100%
		}
		table tr td{
			border-left: 1px solid #ccc;
		}
		table tr td, th{
			font-size: 10px;
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
			align-items: center;
			justify-content: center;
			position: relative;
			flex-direction: column;
			outline: 1px solid #ccc;
			overflow: hidden;
		}
		.photo-container .title{
			display: flex;
			align-items: center;
			justify-content: flex-start;
			width: 100%;
			height: 20px;
			border-bottom: 1px solid #ccc;
			position: absolute;
			left: 0;
			top: 0;
		}
		.photo-container img{
			display: block;
			margin-top: 50px;
			width: calc(100% - 20px);
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
					<span>S. Rafael & Del Parque, Parque</span>
					<span>Industrial Lerma Toluca, 52000</span>
					<span>Lerma de Villada, Méx.</span>
					<span><a target="_blanck" href="tel:5553212500">55 5321 2500</a></span>
				</div>
				<div class="col d-flex flex-direction-column justify-center align-end">
					<h2 class="doc-title">Reporte de daños de materiales</h2>
					<h3>Folio: {{ $waste->id }}</h3>
					<span><strong>Fecha: </strong>{{ $waste->created_at }}</span> 
					<span><strong>Reportado por: </strong>{{ $waste->de->id }}-{{ @strtoupper($waste->de->name) }}</span>
				</div>
			</div>
		</div>
		<div class="page-body">
			<div class="container-fluid">
				<div class="row row-cols-12 mt-4">
					<table>
						
					<tbody>
						<tr>
							<td style="border-left: none;" width="10%"><strong>OC/OP:</strong></td>
							<td style="border-left: none;" width="20%">{{ @strtoupper($waste->ordencompra) }}</td>
							<td style="border-left: none;" width="10%"><strong>Factura:</strong></td>
							<td style="border-left: none;" width="20%">{{ @strtoupper($waste->factura) }}</td>
							<td style="border-left: none;" width="20%"><strong>Típo:</strong></td>
							<td style="border-left: none;" width="20%">
								@if($waste->type == 1)
									Proveedor
								@else
									Producción/Almacén
								@endif
							</td>							
						</tr>
						<tr>
							<td style="border-left: none;" colspan="2"><strong>Reporte para:</strong></td>
							<td style="border-left: none;" colspan="4">
								@foreach($waste->para as $key => $item)
									<span> {{ $item->id }}-{{ $item->name }}</span>, 
								@endforeach
							</td>
						</tr>
					</tbody>
					</table>
				</div>

				<div class="row row-cols-12 mt-4">
					<div class="col">
						<table class="table">
							<thead class="thead-dark">
								<tr>
									<th scope="col"><center>#</center></th>
									<th scope="col"><center>Artículo</center></th>
									<th scope="col"><center>Categoría</center></th>
									<th scope="col"><center>Proveedor</center></th>
									<th scope="col"><center>Departamento</center></th>
									<th scope="col"><center>Daño</center></th>
									<th scope="col"><center>Lote</center></th>
									<th scope="col"><center>Cantidad</center></th>
									<th scope="col"><center>Destino f</center></th>
									<th scope="col"><center>Unidad</center></th>
								</tr>
							</thead>
							<tbody>
								@foreach($waste->items AS $key => $item)
									<tr>
										<td scope="row"><center>{{ $key + 1}})</center></td>
										<td scope="col">
											{{ $item->article->code }} <br>
											{{ $item->article->description }}
										</td>
										<td scope="col">{{ $item->categoria }}</td>
										<td scope="col">{{ $item->proveedor }}</td>
										<td scope="col">{{ $item->departamento }}</td>
										<td scope="col">{{ $item->daño }}</td>
										<td scope="col">{{ $item->lote }}</td>
										<td scope="col">{{ $item->cantidad }}</td>
										<td scope="col">{{ $item->destino_final }}</td>
										<td scope="col">{{ $item->unidad }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>

				<div class="row row-cols-12 mt-4">
						<strong>Comentarios:</strong>
						{{ $waste->comentarios }}
				</div>
			</div>
		</div>
	</div>

	@foreach($waste->photos->chunk(4) AS $photos)
		<div class="page">
			<div class="row" style="height: 100%">
				@foreach($photos AS $key => $photo)
					<div class="col-6" style="height: 50%;">
						<div class="row" style="height: 100%;">
							<div class="col-12" style="display: flex; align-items: center; height: 20px; border-bottom: 1px solid #ccc;">
								{{ $key + 1 }}) {{ $photo->name }}
							</div>
							<div class="col-12" style="display: flex; align-items: center; height: 100%; justify-content: center; overflow: hidden;">
								<img src="{{@asset($photo->path)}}" alt="{{@asset($photo->path)}}" style="display: block; max-width: 100%; max-height: calc(100% - 20px)">
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>
	@endforeach

</div>


<script>
	// window.print();
</script>
</body>
</html>
