<!doctype html>
<html>

<body>
    <style>
        .contactPage {
            padding: 70px 180px;
        }

    </style>
    <div class="contactPage">
        <p> Hello {{ $name ?? '' }}</p>
        <p> {{ $userMessage ?? '' }} </p>
    </div>
</body>

</html>
