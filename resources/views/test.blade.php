<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
    </head>
    <body>
        <form action="/register" method="POST">
            {!! csrf_field() !!}

            <div>
                Name: <input type="text" name="name">
            </div>

            <div>
                <input type="checkbox" value="yes" name="terms"> Accept Terms
            </div>

            <div>
                <input type="submit" value="Register">
            </div>
        </form>
    </body>
</html>
