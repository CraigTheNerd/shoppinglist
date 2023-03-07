<?php require APPROOT . '/views/inc/header.php'; ?>
    <a href="<?php echo URLROOT; ?>/items" class="btn btn-light">
        <i class="fas fa-chevron-circle-left"></i> Back
    </a>
    <div class="card card-body bg-light mt-5">

        <h2>Add Item</h2>
        <p>
            Create an item with this form
        </p>
        <form action="<?php echo URLROOT; ?>/items/add" method="post">

            <div class="form-group">
                <label for="item_name">Item Name: <sup>*</sup></label>
                <input type="text" name="item_name" class="form-control form-control-lg <?php echo (!empty($data['item_name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['item_name']; ?>">
                <span class="invalid-feedback"><?php echo $data['item_name_err']; ?></span>
            </div>

            <div class="form-group">
                <label for="item_desc">Item Description: <sup>*</sup></label>
                <textarea name="item_desc" class="form-control form-control-lg <?php echo (!empty($data['item_desc_err'])) ? 'is-invalid' : ''; ?>">
                    <?php echo $data['item_desc']; ?>
                </textarea>
                <span class="invalid-feedback"><?php echo $data['item_desc_err']; ?></span>
            </div>
            <input type="submit" class="btn btn-success" value="Submit">
        </form>
    </div>

<?php require APPROOT . '/views/inc/footer.php'; ?>