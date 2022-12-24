<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Barcode</title>
</head>

<body>
    <img src="{{ $barcode->getImage() }}" style="display: block;
  margin-left: auto;
  margin-right: auto;margin-top: 10%;" />
</body>

</html>
