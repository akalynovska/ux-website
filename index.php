<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ux</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <base href="/">

    <meta name="google-signin-client_id" content="39575750767-4d3ieqoemj7kc43hi76qrp9ft2qnqo3e.apps.googleusercontent.com">
</head>

<body>
    <script>
        var token = localStorage.getItem('token');
        if (token === null) {
            document.body.insertAdjacentHTML('beforeend', '<div class="g-signin2" data-onsuccess="onGLogIn" data-onfailure="onGLoginFailure"></div>');
        }
        else {
            document.body.insertAdjacentHTML('beforeend', '<app-root><div id="loading">Loading...</div></app-root>');
        }

        function onGLogIn(response) {
            localStorage.setItem('token', response.getAuthResponse().id_token);
            location.reload();
        }

        function onGLoginFailure(data) {
            debugger;
        }
    </script>

    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <link href="dist/styles.bundle.css" rel="stylesheet">
    <script src="dist/inline.bundle.js"></script>
    <script src="dist/polyfills.bundle.js"></script>
    <script src="dist/vendor.bundle.js"></script>
    <script src="dist/main.bundle.js"></script>
</body>
</html>