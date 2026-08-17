<!-- dynamic_form.php -->
<div id="dynamic_modal_container" class="register-form delete_container" style="display: none;">
    <form id="dynamic_form" action="controllers/process.php" method="POST" enctype="multipart/form-data" class="delete_box">
        
        <input type="hidden" name="module" id="modal_module" value="">
        <input type="hidden" name="action_type" id="modal_action_type" value="">
        <input type="hidden" name="item_id" id="modal_item_id" value="">

        <h3 id="modal_title">Form Title</h3>
        
        
        <p id="modal_error_msg" class="errors_message"></p>
        
        <p id="modal_message" style="display: none;"></p>

        
        <div id="modal_inputs_container"></div>

        <div class="btn-group" style="margin-top: 1.5rem;">
            <button type="submit" id="modal_submit_btn" class="btn">Submit</button>
            <button type="button" onclick="closeDynamicModal()" class="btn">Cancel</button>
        </div>
    </form>
</div>