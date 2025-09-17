<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formularios</title>
</head>

<body>
    @foreach ($forms as $form)
            <div>
                @include($form['view'], [ 'startPage' => $form['startPage'], 'endPage' => $form['endPage']])
            </div>
    @endforeach
</body>

</html>