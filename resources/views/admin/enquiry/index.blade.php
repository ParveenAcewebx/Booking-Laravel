@extends('admin.layouts.app')

@section('content')
<div class="pcoded-main-container">
    <div class="pcoded-content">
        <div class="page-header">
            <div class="page-block">
                <div class="row align-items-center">
                    <div class="col-md-10">
                        <div class="page-header-title">
                            <h5>All Enquiries</h5>
                        </div>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="feather icon-home"></i></a></li>
                            <li class="breadcrumb-item">Enquiries</li>
                            <li class="breadcrumb-item">All Enquiries</li>
                        </ul>
                    </div>
                    <div class="col-md-2">
                        <div class="page-header-titles float-right">
                            @can('delete enquires')
                            <button id="bulkDeleteBtn" class="btn btn-danger btn-sm p-2 bulkDeleteBtn" disabled>Delete</button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enquiries Table -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card user-profile-list">
                    <div class="card-body">
                        <div class="dt-responsive">
                            <table class="table table-striped nowrap" id="enquiries-table" width="100%">
                                <thead>
                                    <tr>
                                        <th style="display: none;">ID</th>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.enquiry.partials.view-enquiry')
@include('admin.enquiry.partials.reply-enquiry')

<script type="text/javascript">
    $(document).ready(function() {
        // Initialize Quill editor
        var quill = new Quill('#reply-editor', {
            theme: 'snow'
        });

        var table = $('#enquiries-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('enquiry.list') }}",
            columns: [{
                    data: 'id',
                    name: 'id',
                    visible: false
                },
                {
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [0, 'desc']
            ],
            lengthMenu: [
                [10, 25, 50, 100],
                [10, 25, 50, 100]
            ],
            createdRow: function(row, data) {
                if (data.read == 0) $(row).css('background-color', '#d4edda');
            }
        });

        $(document).on('click', '.showenquiry', function() {
            var id = $(this).data('id');
            $.get('/admin/enquires/' + id, function(data) {
                $('#enquiry-name').text(data.name);
                $('#enquiry-email').text(data.email);
                $('#enquiry-phone').text(data.phone);
                $('#enquiry-message').text(data.message || '');
                $('#enquiryModal').modal('show');
                table.ajax.reload(null, false);
            });
        });

        $(document).on('click', '.replyview', function() {
            var id = $(this).data('id');
            $.get('/admin/enquires/' + id, function(data) {
                $('#reply-id').val(data.id);
                $('#reply-name').text(data.name);
                $('#reply-email').text(data.email);
                $('#reply-phone').text(data.phone);
                $('#reply-message').text(data.message || '');
                quill.setContents([]); 
                $('#replyModal').modal('show');
                table.ajax.reload(null, false);
            });
        });

        $('#replyForm').on('submit', function(e) {
            e.preventDefault();

            var replyContent = quill.root.innerHTML.trim();
            $('#reply_message').val(replyContent);

            if (replyContent === '' || replyContent === '<p><br></p>') {
                toastr.error('Reply message is required!');
                return;
            }

            var btn = $('#replySubmitBtn');
            var originalText = btn.html();
            btn.html('<span class="spinner-border spinner-border-sm"></span> Sending...').prop('disabled', true);

            $.ajax({
                url: '/admin/enquiries/reply',
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    if (res.success) {
                        toastr.success(res.message);
                        $('#replyModal').modal('hide');
                        table.ajax.reload(null, false);
                    } else {
                        toastr.error(res.message);
                    }
                },
                error: function(xhr) {
                    let err = xhr.responseJSON?.message || 'Something went wrong!';
                    toastr.error(err);
                },
                complete: function() {
                    btn.html(originalText).prop('disabled', false);
                }
            });
        });

        toastr.options = {
            closeButton: true,
            progressBar: true,
            timeOut: 4000,
            positionClass: "toast-top-right"
        };

        @if(session('success')) toastr.success("{{ session('success') }}");
        @endif
        @if(session('error')) toastr.error("{{ session('error') }}");
        @endif
    });
</script>
@endsection