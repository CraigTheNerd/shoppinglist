<?php require APPROOT . '/views/inc/header.php'; ?>

<?php flash('item_message'); ?>

    <div class="row mb-3">
        <div class="col col-md-6">
            <h1>Shopping List</h1>
        </div>
        <div class="col col-md-6">
            <a href="<?php echo URLROOT; ?>/items/add" class="btn btn-primary pull-right float-right">
                <i class="fa fa-pencil"></i> Add Item
            </a>
        </div>
    </div>

<?php foreach($data['items'] as $item) : ?>

    <div class="card card-body mb-3">
        <h4 class="card-title"><?php echo $item->item_name; ?></h4>
        <p class="card-text">
            <?php echo $item->item_desc; ?>
        </p>

        <div>
            <p>
                <?php echo $item->item_completed; ?>
            </p>
        </div>

        <div class="form-group row">
            <div class="col-sm-10">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="item_checked" name="item_checked" value="1">
                    <label class="form-check-label" for="item_checked">
                        Mark as Completed
                    </label>
                </div>
            </div>
        </div>

        <a href="<?php echo URLROOT; ?>/items/show/<?php echo $item->itemId; ?>" class="btn btn-dark">More</a>
    </div>

<?php endforeach; ?>

<?php require APPROOT . '/views/inc/footer.php'; ?>