<div class="modal fade" id="updatePasswordModal" tabindex="-1" role="dialog" aria-labelledby="updatePasswordModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updatePasswordModalLabel">Update password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="forms-sample" id="changePasswordForm" action="{{ route('change-password') }}">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="current_password"><span class="text-danger">* </span>Current password</label>
                        <input type="password" class="form-control" id="current_password" autocomplete="off"
                            placeholder="Current password" name="current_password">
                    </div>
                    <div class="form-group">
                        <label for="new_password"><span class="text-danger">* </span>New password</label>
                        <input type="password" class="form-control" id="new_password" autocomplete="off"
                            placeholder="New password" name="new_password">
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirmation"><span class="text-danger">* </span>Confirm new
                            password</label>
                        <input type="password" class="form-control" id="new_password_confirmation" autocomplete="off"
                            placeholder="Confirm new password" name="new_password_confirmation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary change-password">Update</button>
                </div>

            </form>
        </div>
    </div>
</div>
