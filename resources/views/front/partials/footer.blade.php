<footer class="footer">
    <div class="lg-menu">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-md-2">
                    <img src="" alt="logo">
                </div>
                <div class="col-md-8 text-md-right">
                    {{-- <ul>
                        <li>
                            <a href="{{ url('/') }}" title="">Home</a>
                        </li>
                        <li>
                            <a href="{{ url('/blog') }}" title="">Blog</a>
                        </li>
                    </ul> --}}
                </div>
            </div>
        </div>
    </div>

    <div class="row copyright">
        <div class="container">
            <p>Copyright Test © 2023. All Rights Reserved</p>
        </div>
    </div>
</footer>

{{-- <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script> --}}
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" crossorigin="anonymous"></script> --}}
{{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"  crossorigin="anonymous"></script> --}}
<script>
    $(document).ready(function() {
        base_url = "{{ url('/') }}";
    })
</script>
<!-- <script src="{{ URL::asset('js/app_script.js') }}"></script> -->
<script>
    $('.multiple-select').multipleSelect({
        width: '100%'
    });
    $('.multiple_select').multiSelect();
    $('.choose').select2();
</script>
<script>
    $(document).ready(function() {
        $("#newsletterSubmit").on('click', function() {
            var email = $("#email_newsletter").val();
            var $this = $(this);
            $(this).prop('disabled', true);
            $('.newsletterForm > .alert').remove();
            $.ajax({
                url: '{{ route('newsletter') }}',
                type: 'post',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "email": email
                },
                dataType: 'json',
                success: function(json) {
                    if (json['status'] == true) {
                        $("#email_newsletter").val('');
                        $this.parents('.newsletterForm').prepend(
                            '<div class="alert alert-success" style="padding:10px;margin-bottom:10px;">' +
                            json['success'] + '</div>');
                    } else {
                        $this.prop('disabled', false);
                        $this.parents('.newsletterForm').prepend(
                            '<div class="alert alert-danger" style="padding:10px;margin-bottom:10px;">' +
                            json['error'] + '</div>');
                    }
                }
            });
        })
    })
</script>
