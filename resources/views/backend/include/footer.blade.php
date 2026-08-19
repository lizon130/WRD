</div>
</div>
<script src="{{ asset('assets/js/backend/bootstrap.bundle.min.js') }}" crossorigin="anonymous"></script>
<script src="{{ asset('assets/js/backend/scripts.js') }}"></script>
<script src="{{ asset('assets/js/backend/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/backend/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/backend/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>

<!-- include summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

{{-- <script src="{{ asset('assets/js/backend/select2.min.js')}}" ></script> --}}
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="{{ asset('assets/js/backend/validator.js') }}"></script>


<script>
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            $.toast({
                heading: 'Error',
                text: "{{ $error }}",
                position: 'top-center',
                icon: 'error'
            })
        @endforeach
    @endif

    @if (session()->has('success'))
        $.toast({
            heading: 'Success',
            text: "{{ session()->get('success') }}",
            position: 'top-center',
            icon: 'success'
        })
    @endif


    function previewFile(input, preview) {
        var file = $("#" + input + "").get(0).files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function() {
                $("#" + preview + "").attr("src", reader.result);
            }
            reader.readAsDataURL(file);
        }
    }

    function initSummerNote() {
        $('.tinymceText').summernote({
            height: 200,
        });
    }

    initSummerNote();


    $('.select2').select2();


    $(document).on('click', '.flag-select', function(e) {
        e.preventDefault();
        let language = $(this).attr('data-language');
        $.ajax({
            url: "{{ url('admin/setting/change-language') }}",
            type: "Get",
            data: {
                language: language,
            },
            success: function(response) {
                window.location.reload();
            }
        })
    });

    $(document).on("change", "#select_all", function() {
        if ($("#select_all").attr("data-checked") == "true") {
            $('input[type="checkbox"]').each(function(key, val) {
                $(this).prop("checked", false);
                $("#select_all").attr("data-checked", false);
            });
        } else {
            $('input[type="checkbox"]').each(function(key, val) {
                $(this).prop("checked", true);
                $("#select_all").attr("data-checked", true);
            });
        }
    });

    function copyThisDiv(button) {
        // Get the original row of the button that was clicked
        var originalRow = button.closest('.copy_this');

        // Clone the original row
        var clonedRow = originalRow.cloneNode(true);

        // Clear values of cloned input fields
        var textInputs = clonedRow.querySelectorAll('input[type="text"]');
        textInputs.forEach(input => input.value = ''); // Clear all text inputs

        var checkboxes = clonedRow.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => checkbox.checked = false); // Uncheck all checkboxes

        // Change the button in the cloned row to a remove button
        var buttonInClonedRow = clonedRow.querySelector('button');
        if (buttonInClonedRow) {
            buttonInClonedRow.innerHTML = '<i class="fa fa-minus"></i>';
            buttonInClonedRow.classList.remove('btn-outline-secondary');
            buttonInClonedRow.classList.add('btn-outline-danger');
            buttonInClonedRow.setAttribute('onclick', 'removeThisDiv(this);');
        }

        // Insert the cloned row after the original row
        originalRow.after(clonedRow);
    }


    function removeThisDiv(button) {
        // Get the parent row of the button that was clicked
        var rowToRemove = button.closest('.copy_this');

        // Remove the parent row
        rowToRemove.remove();
    }
</script>

@stack('footer')
</body>

</html>
