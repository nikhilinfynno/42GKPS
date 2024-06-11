<div id="forgotPasswordModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="forgotPasswordModalLabel">Enter your registered email address, we will send
                    password reset link to your email address
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form class="forms-sample" id="forgotPasswordEmailForm" action="{{ route('password.reset.request') }}">
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="registered_email"><span class="text-danger">* </span>Email address</label>
                            <input type="email" class="form-control" id="registered_email" autocomplete="off"
                                placeholder="Registered email address" name="registered_email">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary ">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
