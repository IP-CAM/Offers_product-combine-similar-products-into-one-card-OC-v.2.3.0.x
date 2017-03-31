<?php

/**
 * Created by PhpStorm.
 * User: uSkyQ
 * Date: 31.03.2017
 * Time: 17:23
 */
class ControllerExtensionOffersProductEditProduct extends Controller
{
//    Event action
    public function deleteProductAfter(&$route, &$data){
        if ($this->config->get('offers_product_status')) {
            if ($this->config->get('offers_product_en_log')) {
                $this->log->write('controller deleteProductAfter');
            }
            $this->load->model('extension/offers_product');
            $this->model_extension_offers_product->deleteProductAfter($data[0], $this->config->get('offers_product_en_log'));
        }

    }
    public function addProductAfter(&$route, &$data, &$product_id)
    {
        if ($this->config->get('offers_product_status')) {
            if ($this->config->get('offers_product_en_log')) {
                $this->log->write('controller addProductAfter');
            }
            $this->load->model('extension/offers_product');
            $this->model_extension_offers_product->addProductAfter($data, $this->config->get('offers_product_en_log'),$product_id);
        }
    }

    public function editProductBefore(&$route, &$data)
    {
        if ($this->config->get('offers_product_status')) {
            if ($this->config->get('offers_product_en_log')) {
                $this->log->write('controller editProductBefore');
            }
            $this->load->model('extension/offers_product');
            $this->model_extension_offers_product->editProductBefore($data, $this->config->get('offers_product_en_log'));
        }
    }

    public function openProductBefore(&$route, &$data)
    {
        if ($this->config->get('offers_product_status')) {
            if ($this->config->get('offers_product_en_log')) {
                $this->log->write('controller openProductBefore');
            }
            $data = $this->loadLanguageForopenProductBefore($data);
            $data = $this->loadMainDataForopenProductBefore($data);
        }

    }

    public function openProductAfter(&$route, &$data, &$output)
    {
        if ($this->config->get('offers_product_status')) {
            if ($this->config->get('offers_product_en_log')) {
                $this->log->write('controller openProductAfter');
            }
            $template = new Template($this->registry->get('config')->get('template_type'));
            foreach ($data as $key => $value) {
                $template->set($key, $value);
            }

            $searchText = "id=\"tab-data\">";
            $outputAdd = $template->render('extension/module/offers_product_for_product_form_first.tpl');
            $output = str_replace($searchText, $searchText . $outputAdd, $output);

            if ($data['master_product'] == 1) {
                $template->set('tab_slave_list', $data['tab_slave_list']);
                $searchText = "<li><a href=\"#tab-links\"";
                $outputAdd = $template->render('extension/module/offers_product_for_product_form_nav.tpl');
                $output = str_replace($searchText, $outputAdd . $searchText, $output);

                $outputAdd = $template->render('extension/module/offers_product_for_product_form_slave_list.tpl');
                $searchText = "<div class=\"tab-pane\" id=\"tab-links\">";
                $output = str_replace($searchText, $outputAdd . $searchText, $output);
            }


        }
    }

//-------------------------------------------------------------------

    private function loadMainDataForopenProductBefore($data)
    {
        $this->load->model('catalog/product');
        if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $product_info = $this->model_catalog_product->getProduct($this->request->get['product_id']);
        }
        if (isset($this->request->post['master_product'])) {
            $data['master_product'] = $this->request->post['master_product'];
        } elseif (!empty($product_info)) {
            $data['master_product'] = $product_info['master_product'];
        } else {
            $data['master_product'] = 0;
        }
        return $data;
    }

    private function loadLanguageForopenProductBefore($data)
    {
        $this->load->language('extension/module/offers_product');
        $data['entry_master_product'] = $this->language->get('entry_master_product');
        $data['tab_slave_list'] = $this->language->get('tab_slave_list');
        return $data;
    }

}