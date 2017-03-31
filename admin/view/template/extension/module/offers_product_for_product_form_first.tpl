<div class="form-group">
    <label class="col-sm-2 control-label"
           for="input-master-product">
        <?php echo $entry_master_product; ?>
    </label>
    <div class="col-sm-10">
        <label class="radio">
            <input type="checkbox" value="1" id="input-master-product"
                   name="master_product" <?php echo ($master_product == 1)? 'checked' : ''; ?> />
        </label>
    </div>
</div>