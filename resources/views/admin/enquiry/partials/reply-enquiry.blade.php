<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1" role="dialog" aria-labelledby="replyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="margin-top:40px;">
      <div class="modal-header">
        <h5 class="modal-title" id="replyModalLabel">Reply to Enquiry</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="replyForm">
          @csrf
          <input type="hidden" id="reply-id" name="id">

          <p><strong>Name:</strong> <span id="reply-name"></span></p>
          <p><strong>Email:</strong> <span id="reply-email"></span></p>
          <p><strong>Phone:</strong> <span id="reply-phone"></span></p>
          <p><strong>Message:</strong> <span id="reply-message"></span></p>

          <div class="form-group">
            <label>Reply:</label>
            <div id="reply-editor" style="height: 200px;"></div>
            <textarea name="reply_message" id="reply_message" class="d-none"></textarea>
          </div>
          <button type="submit" class="btn btn-primary" id="replySubmitBtn">Send Reply</button>
        </form>
      </div>
    </div>
  </div>
</div>
