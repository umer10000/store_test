<!doctype html>
<html>

<body>
    <style>
        .contactPage {
            padding: 70px 180px;
        }

    </style>
    <div class="contactPage">
        <h2 class="text-center">Account Email</h2>
        <p> Hello {{ $name ?? '' }}</p>
        <p> {{ $userMessage ?? '' }} </p>
    </div>
</body>

</html>
