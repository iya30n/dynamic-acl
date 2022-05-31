<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('tab_title', 'Document')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        @font-face {
            font-family: Vazir;
            src: url('https://cdn.fontcdn.ir/Font/Persian/Vazir/Vazir.eot');
            src: url('https://cdn.fontcdn.ir/Font/Persian/Vazir/Vazir.eot?#iefix') format('embedded-opentype'),
            url('https://cdn.fontcdn.ir/Font/Persian/Vazir/Vazir.woff2') format('woff2'),
            url('https://cdn.fontcdn.ir/Font/Persian/Vazir/Vazir.woff') format('woff'),
            url('https://cdn.fontcdn.ir/Font/Persian/Vazir/Vazir.ttf') format('truetype');
            font-weight: normal;
        }

        body {
            background: #f4f6f9;
            font-family: Vazir;
        }

        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125),0 1px 3px rgba(0,0,0,.2);
            margin-top: 2%;
        }

        .card-header {
            background: #334858;
            color: white;
        }

        .card-tools {
            float: {{ $alignment == 'rtl' ? 'left' : 'right' }}
        }

        .selectAll {
            margin-left: -30px;
        }
    </style>
</head>
<body dir="{{$alignment}}">
    <div class="mt-5">
        <div class="content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
<script>

    function selectAll(selectAll, dashKey) {
        document.querySelectorAll('.' + dashKey).forEach(item => {
            if(item.checked !== selectAll.checked)
                item.click()
        })
    }

</script>
</html>

