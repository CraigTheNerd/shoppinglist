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
        <a href="<?php echo URLROOT; ?>/items/show/<?php echo $item->itemId; ?>" class="btn btn-dark">More</a>
    </div>

<?php endforeach; ?>

<?php require APPROOT . '/views/inc/footer.php'; ?>