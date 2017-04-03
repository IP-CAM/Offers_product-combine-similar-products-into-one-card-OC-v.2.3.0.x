<div class="form-group">
    <label class="control-label" for="input-master-product-filter"><?php echo $entry_master_product_filter; ?></label>
    <select name="master_product_filter" id="input-master-product-filter" class="form-control">
        <option value="*"></option>
        <?php if ($master_product_filter) { ?>
        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
        <?php } else { ?>
        <option value="1"><?php echo $text_enabled; ?></option>
        <?php } ?>
        <?php if (!$master_product_filter && !is_null($master_product_filter)) { ?>
        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
        <?php } else { ?>
        <option value="0"><?php echo $text_disabled; ?></option>
        <?php } ?>
    </select>
</div>