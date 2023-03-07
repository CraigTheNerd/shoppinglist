<?php require APPROOT . '/views/inc/header.php'; ?>

    <a href="<?php echo URLROOT; ?>/items" class="btn btn-light">
        <i class="fas fa-chevron-circle-left"></i> Back
    </a>

    <h1 class="mt-5 mb-3"><?php echo $data['item']->item_name; ?></h1>
    <p>
        <?php echo $data['item']->item_desc; ?>
    </p>

    <!--    Check owner of item. Only show edit and delete to item owner    -->
<?php if($data['item']->user_id == $_SESSION['user_id']) : ?>

    <hr>
    <a href="<?php echo URLROOT; ?>/items/edit/<?php echo $data['item']->id; ?>" class="btn btn-warning">Edit</a>

    <form class="float-right" action="<?php echo URLROOT; ?>/items/delete/<?php echo $data['item']->id; ?>" method="post">
        <input type="submit" value="delete" class="btn btn-danger">
    </form>

<?php endif; ?>

<?php require APPROOT . '/views/inc/footer.php'; ?>