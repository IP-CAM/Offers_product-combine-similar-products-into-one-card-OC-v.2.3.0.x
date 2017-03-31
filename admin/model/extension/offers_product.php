<?php

/**
 * Created by PhpStorm.
 * User: uSkyQ
 * Date: 30.03.2017
 * Time: 17:56
 */
class ModelExtensionOffersProduct extends Model
{
    private $enable_log = false;

    public function install()
    {
        $this->enable_log = true;
        $this->writeLog('Install module offers product start...');
        $text = "CREATE TABLE `" . DB_PREFIX . "offers_product` ( `master_id` INT(11) NULL , `slave_id` INT(11) NULL ) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'offers product'";
        $this->db->query($text);
        $this->writeLog('— ' . $text);
        $text = "ALTER TABLE `" . DB_PREFIX . "offers_product` ADD PRIMARY KEY( `master_id`, `slave_id`)";
        $this->db->query($text);
        $this->writeLog('— ' . $text);
        $text = "ALTER TABLE `" . DB_PREFIX . "product` ADD `master_product` TINYINT(1) NOT NULL DEFAULT '0' ";
        $this->db->query($text);
        $this->writeLog('— ' . $text);

        $this->load->model('extension/event');
        $this->model_extension_event->addEvent('ofp_1', 'admin/model/catalog/product/editProduct/before', 'extension/offers_product_edit_product/editProductBefore');
        $this->model_extension_event->addEvent('ofp_2', 'admin/view/catalog/product_form/after', 'extension/offers_product_edit_product/openProductAfter');
        $this->model_extension_event->addEvent('ofp_3', 'admin/view/catalog/product_form/before', 'extension/offers_product_edit_product/openProductBefore');
        $this->model_extension_event->addEvent('ofp_4', 'admin/model/catalog/product/addProduct/after', 'extension/offers_product_edit_product/addProductAfter');
        $this->model_extension_event->addEvent('ofp_5', 'admin/model/catalog/product/deleteProduct/after', 'extension/offers_product_edit_product/deleteProductAfter');

        $this->writeLog("— addEvent('offers product before edit product : ofp_1')");
        $this->writeLog("— addEvent('offers product before edit product : ofp_2')");
        $this->writeLog("— addEvent('offers product before edit product : ofp_3')");
        $this->writeLog("— addEvent('offers product before edit product : ofp_4')");
        $this->writeLog("— addEvent('offers product before edit product : ofp_5')");
        $this->writeLog('END');
    }

    public function uninstall()
    {
        $this->enable_log = true;
        $this->writeLog('Uninstall module offers product start...');
        $text = "DROP TABLE `" . DB_PREFIX . "offers_product`";
        $this->db->query($text);
        $this->writeLog('— ' . $text);
        $text = "ALTER TABLE `" . DB_PREFIX . "product` DROP `master_product`";
        $this->db->query($text);
        $this->writeLog('— ' . $text);

        $this->load->model('extension/event');
        $this->model_extension_event->deleteEvent('ofp_1');
        $this->model_extension_event->deleteEvent('ofp_2');
        $this->model_extension_event->deleteEvent('ofp_3');
        $this->model_extension_event->deleteEvent('ofp_4');
        $this->model_extension_event->deleteEvent('ofp_5');

        $this->writeLog("— deleteEvent('offers product before edit product ofp_1')");
        $this->writeLog("— deleteEvent('offers product before edit product ofp_2')");
        $this->writeLog("— deleteEvent('offers product before edit product ofp_3')");
        $this->writeLog("— deleteEvent('offers product before edit product ofp_4')");
        $this->writeLog("— deleteEvent('offers product before edit product ofp_5')");
        $this->writeLog('END');
    }

    private function writeLog($text)
    {
        if ($this->enable_log)
            $this->log->write($text);
    }

    public function editProductBefore($data, $enable_log)
    {
        $this->enable_log = $enable_log;
        $this->writeLog('model editProductBefore');
        if (isset($data)) {
            if (isset($data[1]['master_product'])) {
                $master_product = (int)$data[1]['master_product'];
            } else {
                $master_product = 0;
            }
            $text = "UPDATE `" . DB_PREFIX . "product` SET `master_product`=" . $master_product . " WHERE product_id=" . $data[0];
            $this->db->query($text);
            $this->writeLog($text);
        }
    }

    public function addProductAfter($data, $enable_log, $product_id)
    {
        $this->enable_log = $enable_log;
        $this->writeLog('model addProductAfter');
        if (isset($data)) {
            if (isset($data[0]['master_product'])) {
                $master_product = (int)$data[0]['master_product'];
            } else {
                $master_product = 0;
            }
            $text = "UPDATE `" . DB_PREFIX . "product` SET `master_product`=" . $master_product . " WHERE product_id=" . $product_id;
            $this->db->query($text);
            $this->writeLog($text);
        }
    }

    public function deleteProductAfter($product_id, $enable_log)
    {
        $this->enable_log = $enable_log;
        $this->writeLog('model deleteProductAfter');
        $text = "DELETE FROM `" . DB_PREFIX . "offers_product` WHERE `master_id` = " . $product_id . " OR `slave_id` = " . $product_id;
        $this->db->query($text);
        $this->writeLog($text);
    }
}