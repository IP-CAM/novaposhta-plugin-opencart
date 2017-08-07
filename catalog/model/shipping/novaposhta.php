<?php

class ModelShippingNovaposhta extends Model {

  function getQuote($address){
    $this->language->load('shipping/novaposhta');

    $query = NULL;
    $sql_q = "";
    $sql_q  = "SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE";
    $sql_q .= " `geo_zone_id` = " . (int)$this->config->get('novaposhta_geo_zone_id') . " AND";
    $sql_q .= " `country_id` = " . (int)$address['country_id'] . " AND";
    $sql_q .= " (`zone_id` = " . (int)$address['zone_id'] . " OR zone_id = 0)";
    $query = $this->db->query($sql_q)->rows;
    if ($this->config->get('novaposhta_geo_zone_id') == 0 or count($query) == 1){
      $status = true;
      } else {
      $status = false;
    }

    $method_data = array();

    if (isset($this->session->data['comment'])){
      $this->session->data['comment'] = str_replace($this->language->get('text_instruction') . "\n", '', $this->session->data['comment']);
    }
    if ($this->config->get('novaposhta_sort_order') == 0){
      $comm = '';
      if (isset($this->session->data['comment'])) $comm = $this->session->data['comment'];
      $this->session->data['comment'] = $this->language->get('text_instruction') . "\n" . $comm;
    }

    if ($status){
      $quote_data = array();
      $quote_data['novaposhta'] = array(
        'code' => 'novaposhta.novaposhta',
        'title' => $this->language->get('text_description'),
        'cost' => 0.00,
        'tax_class_id' => 0,
        'text' => $this->currency->format(0.00)
      );
      $method_data = array(
        'code' => 'novaposhta',
        'title' => $this->language->get('text_title'),
        'quote' => $quote_data,
        'sort_order' => $this->config->get('novaposhta_sort_order'),
        'error' => false
      );
    }

    return $method_data;
  }

}

?>
