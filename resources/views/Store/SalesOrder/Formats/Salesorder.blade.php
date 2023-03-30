<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Pinturas Acuario</title>
	<link rel="stylesheet" href="{{ asset('assets/css/sheets-pdf.css') }}">


	<style>
		table{
			width: 100%;
		}

		table thead{
			width: 100%;
		}
		td{
			font-size: 12px;
		}
	</style>
</head>
<body>
	<div class="container">
		@foreach($articles AS $key => $sheet)
			<div class="sheet letter">
				<div style="display: flex; justify-content: space-between; align-items:center;">
					<h1 class="title">
						Referencia de surtido
					</h1>
					<h1>
						Orden de venta: {{ $salesOrder->id }}
					</h1>
				</div>
				<table border="1">
					<thead>
						<td>#</td>
						<td>ARTICULO</td>
						<td>DESCRIPCION</td>
						<td>CAJAS</td>
					</thead>
					<tbody>
						@foreach($articles[$key] AS $key =>$article)
							<tr>
								<td>
									{{ $key + 1 }}
								</td>
								<td>
									{{ $article->code }}
								</td>
								<td>
									{{ $article->description }}
								</td>
								<td>
									@if(!empty($article->box_capacity))
										@if($article->box_capacity > 1)
											{{ $article->pivot->amount / $article->box_capacity }}
											caja(s) con
											{{ $article->box_capacity }}
											piezas
										@endif
									@endif
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		@endforeach
	</div>
<script>
	// window.print();
</script>
</body>
</html>