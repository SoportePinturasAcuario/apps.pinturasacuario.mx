<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Test</title>
</head>
<body>
	<form action="{{ route('test.file.store') }}" method="POST" enctype="multipart/form-data">
		@csrf
		<input type="file" name="file"><br>
		<input type="submit">
	</form>
	
</body>
</html>