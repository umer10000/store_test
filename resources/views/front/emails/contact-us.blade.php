<!doctype html>
<html>

<body>
    <style>
        .contactPage {
            padding: 70px 180px;
        }

    </style>
    <div class="contactPage">
        <h2 class="text-center">Contact Query!</h2>
        <div style="margin-bottom: 20px;">
            <strong>Name: </strong> <span> {{ $name ?? '' }}</span>
        </div>
        <div style="margin-bottom: 20px;">
            <strong>Email: </strong> <span> {{ $email ?? '' }}</span>
        </div>
        <div style="margin-bottom: 20px;">
            <strong>Message: </strong> <span> {{ $userMessage ?? '' }}</span>
        </div>
    </div>
</body>

</html>
