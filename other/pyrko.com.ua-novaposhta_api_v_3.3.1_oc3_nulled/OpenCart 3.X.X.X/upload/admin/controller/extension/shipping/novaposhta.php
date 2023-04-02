<?php 

class ControllerExtensionShippingNovaPoshta extends Controller
{
    protected $extension = "novaposhta";
    protected $extensionId = "8081738";
    protected $version = "3.3.1";
    protected static $license = NULL;
    private $error = array(  );
    private $settings = NULL;

    public function __construct($registry)
    {
        $this->registry = $registry;
        require_once(DIR_SYSTEM . "helper/" . $this->extension . ".php");
        $registry->set("pr", new Pr($registry));
        $registry->set($this->extension, new NovaPoshta($registry));
        $this->settings = $this->config->get("shipping_" . $this->extension);
    }

    public function install()
    {
        $this->load->model("extension/shipping/" . $this->extension);
        $this->{"model_extension_shipping_" . $this->extension}->creatTables();
    }

    public function uninstall()
    {
        $this->load->model("extension/shipping/" . $this->extension);
        $this->{"model_extension_shipping_" . $this->extension}->deleteTables();
    }

    public function index()
    {
        $this->load->language("extension/shipping/" . $this->extension);
        if( !($this->request->server["REQUEST_METHOD"] == "POST" && $this->validate()) ) 
        {
        }
        else
        {
            $this->load->model("setting/setting");
            $this->model_setting_setting->editSetting("shipping_" . $this->extension, $this->request->post);
            $this->session->data["success"] = $this->language->get("text_success_settings");
            if( !isset($this->request->get["exit"]) ) 
            {
            }
            else
            {
                $this->response->redirect($this->url->link("marketplace/extension", "user_token=" . $this->session->data["user_token"] . "&type=shipping", "SSL"));
            }

        }

        $this->document->setTitle($this->language->get("heading_title"));
        $this->document->addStyle("view/stylesheet/ocmax/" . $this->extension . ".css");
        $data["save_and_exit"] = $this->url->link("extension/shipping/" . $this->extension, "user_token=" . $this->session->data["user_token"] . "&exit", "SSL");
        $data["action_settings"] = $this->url->link("extension/shipping/" . $this->extension . "/extensionSettings", "user_token=" . $this->session->data["user_token"], "SSL");
        $data["cancel"] = $this->url->link("marketplace/extension", "user_token=" . $this->session->data["user_token"] . "&type=shipping", "SSL");
        $data["breadcrumbs"] = array(  );
        $data["breadcrumbs"][] = array( "text" => $this->language->get("text_home"), "href" => $this->url->link("common/dashboard", "user_token=" . $this->session->data["user_token"], "SSL") );
        $data["breadcrumbs"][] = array( "text" => $this->language->get("text_shipping"), "href" => $this->url->link("marketplace/extension", "user_token=" . $this->session->data["user_token"] . "&type=shipping", "SSL") );
        $data["breadcrumbs"][] = array( "text" => $this->language->get("heading_title"), "href" => $this->url->link("extension/shipping/" . $this->extension, "user_token=" . $this->session->data["user_token"], "SSL") );
        if( isset($this->session->data["success"]) ) 
        {
            $data["success"] = $this->session->data["success"];
            unset($this->session->data["success"]);
        }
        else
        {
            $data["success"] = "";
        }

        if( isset($this->error["warning"]) ) 
        {
            $data["error_warning"] = $this->error["warning"];
        }
        else
        {
            if( isset($this->session->data["warning"]) ) 
            {
                $data["error_warning"] = $this->session->data["warning"];
                unset($this->session->data["warning"]);
            }
            else
            {
                $data["error_warning"] = "";
            }

        }

        if( isset($this->error["error_key_api"]) ) 
        {
            $data["error_key_api"] = $this->error["error_key_api"];
        }
        else
        {
            $data["error_key_api"] = "";
        }

        $data["user_token"] = $this->session->data["user_token"];
        $data["text_help_integration_with_orders"] = sprintf($this->language->get("text_help_integration_with_orders"), DB_PREFIX, $this->extension);
        $this->load->model("tool/image");
        $data["placeholder"] = $this->model_tool_image->resize("no_image.png", 100, 100);
        $this->load->model("localisation/language");
        $data["languages"] = $this->model_localisation_language->getLanguages();
        $data["language_id"] = "";
        foreach( $data["languages"] as $language ) 
        {
            if( $language["code"] != $this->config->get("config_admin_language") ) 
            {
            }
            else
            {
                $data["language_id"] = $language["language_id"];
            }

            $data["language_flag"][$language["language_id"]] = (version_compare(VERSION, "2.2", ">=") ? "language/" . $language["code"] . "/" . $language["code"] . ".png" : "view/image/flags/" . $language["image"]);
        }
        $this->load->model("localisation/geo_zone");
        $data["geo_zones"] = $this->model_localisation_geo_zone->getGeoZones();
        $this->load->model("localisation/tax_class");
        $data["tax_classes"] = $this->model_localisation_tax_class->getTaxClasses();
        $this->load->model("setting/extension");
        $totals = $this->model_setting_extension->getInstalled("total");
        $data["totals"] = array(  );
        foreach( $totals as $total ) 
        {
            if( !$this->config->get("total_" . $total . "_status") ) 
            {
            }
            else
            {
                $temp_language_data = $this->load->language("extension/total/" . $total, "temp");
                $data["totals"][$total] = $temp_language_data["temp"]->get("heading_title");
            }

        }
        $payments = $this->model_setting_extension->getInstalled("payment");
        $data["payment_methods"] = array(  );
        foreach( $payments as $payment ) 
        {
            if( !$this->config->get("payment_" . $payment . "_status") ) 
            {
            }
            else
            {
                $temp_language_data = $this->load->language("extension/payment/" . $payment, "temp");
                $data["payment_methods"][$payment] = $temp_language_data["temp"]->get("heading_title");
            }

        }
        $shippings = $this->model_setting_extension->getInstalled("shipping");
        $data["shipping_methods"] = array(  );
        foreach( $shippings as $shipping ) 
        {
            if( !$this->config->get("shipping_" . $shipping . "_status") ) 
            {
            }
            else
            {
                $temp_language_data = $this->load->language("extension/shipping/" . $shipping, "temp");
                $data["shipping_methods"][$shipping] = $temp_language_data["temp"]->get("heading_title");
            }

        }
        $this->load->model("localisation/order_status");
        $data["order_statuses"] = $this->model_localisation_order_status->getOrderStatuses();
        if( isset($this->request->post["shipping_" . $this->extension . "_status"]) ) 
        {
            $data[$this->extension . "_status"] = $this->request->post["shipping_" . $this->extension . "_status"];
        }
        else
        {
            $data[$this->extension . "_status"] = $this->config->get("shipping_" . $this->extension . "_status");
        }

        if( isset($this->request->post["shipping_" . $this->extension . "_sort_order"]) ) 
        {
            $data[$this->extension . "_sort_order"] = $this->request->post["shipping_" . $this->extension . "_sort_order"];
        }
        else
        {
            $data[$this->extension . "_sort_order"] = $this->config->get("shipping_" . $this->extension . "_sort_order");
        }

        if( isset($this->request->post["shipping_" . $this->extension]) ) 
        {
            $data[$this->extension] = $this->request->post["shipping_" . $this->extension];
        }
        else
        {
            $data[$this->extension] = $this->settings;
        }

        if( isset($this->request->post["shipping_" . $this->extension]["image"]) && is_file(DIR_IMAGE . $this->request->post["shipping_" . $this->extension]["image"]) ) 
        {
            $data["thumb"] = $this->model_tool_image->resize($this->request->post["shipping_" . $this->extension]["image"], 100, 100);
        }
        else
        {
            if( !empty($this->settings["image"]) && is_file(DIR_IMAGE . $this->settings["image"]) ) 
            {
                $data["thumb"] = $this->model_tool_image->resize($this->settings["image"], 100, 100);
            }
            else
            {
                $data["thumb"] = $this->model_tool_image->resize("no_image.png", 100, 100);
            }

        }

        $references = $this->novaposhta->getReferences();
        if( isset($references["warehouse_types"]) ) 
        {
            $data["warehouse_types"] = $references["warehouse_types"];
        }
        else
        {
            $data["warehouse_types"] = array(  );
        }

        if( isset($references["database"]) ) 
        {
            $data["database"] = $references["database"];
        }
        else
        {
            $data["database"] = array(  );
        }

        if( isset($references["senders"]) ) 
        {
            $data["senders"] = $references["senders"];
        }
        else
        {
            $data["senders"] = array(  );
        }

        if( isset($references["sender_contact_persons"]) && isset($references["sender_contact_persons"][$data["novaposhta"]["sender"]]) ) 
        {
            $data["sender_contact_persons"] = $references["sender_contact_persons"][$data["novaposhta"]["sender"]];
        }
        else
        {
            $data["sender_contact_persons"] = array(  );
        }

        if( isset($references["sender_addresses"]) && $data["novaposhta"]["sender_city"] ) 
        {
            $data["sender_addresses"] = array_merge($this->novaposhta->getSenderAddresses($data["novaposhta"]["sender"], $data["novaposhta"]["sender_city"]), $this->novaposhta->getWarehousesByCityRef($data["novaposhta"]["sender_city"]));
        }
        else
        {
            $data["sender_addresses"] = array(  );
        }

        if( isset($references["cargo_types"]) && is_array($references["cargo_types"]) ) 
        {
            $data["cargo_types"] = $references["cargo_types"];
        }
        else
        {
            $data["cargo_types"] = array(  );
        }

        if( isset($references["pack_types"]) && is_array($references["pack_types"]) ) 
        {
            $data["pack_types"] = $references["pack_types"];
        }
        else
        {
            $data["pack_types"] = array(  );
        }

        if( isset($references["payer_types"]) && is_array($references["payer_types"]) ) 
        {
            $data["payer_types"] = $references["payer_types"];
        }
        else
        {
            $data["payer_types"] = array(  );
        }

        if( isset($references["third_persons"]) && is_array($references["third_persons"]) ) 
        {
            $data["third_persons"] = $references["third_persons"];
        }
        else
        {
            $data["third_persons"] = array(  );
        }

        if( isset($references["payment_types"]) && is_array($references["payment_types"]) ) 
        {
            $data["payment_types"] = $references["payment_types"];
        }
        else
        {
            $data["payment_types"] = array(  );
        }

        if( isset($references["backward_delivery_types"]) && is_array($references["backward_delivery_types"]) ) 
        {
            $data["backward_delivery_types"] = $references["backward_delivery_types"];
        }
        else
        {
            $data["backward_delivery_types"] = array(  );
        }

        if( isset($references["backward_delivery_payers"]) && is_array($references["backward_delivery_payers"]) ) 
        {
            $data["backward_delivery_payers"] = $references["backward_delivery_payers"];
        }
        else
        {
            $data["backward_delivery_payers"] = array(  );
        }

        if( isset($references["payment_cards"]) && is_array($references["payment_cards"]) ) 
        {
            $data["payment_cards"] = $references["payment_cards"];
        }
        else
        {
            $data["payment_cards"] = array(  );
        }

        if( isset($references["document_statuses"]) && is_array($references["document_statuses"]) ) 
        {
            $data["document_statuses"] = $references["document_statuses"];
        }
        else
        {
            $data["document_statuses"] = array(  );
        }

        $data["image_output_places"] = array( "title" => $this->language->get("text_image_output_place_title"), "img_key" => $this->language->get("text_image_output_place_img_key") );
        $data["calculate_volume_types"] = array( "sum_all_products" => $this->language->get("text_sum_all_products_volume"), "largest_product" => $this->language->get("text_largest_product_volume") );
        $data["use_parameters"] = array( "products_without_parameters" => $this->language->get("text_products_without_parameters"), "all_products" => $this->language->get("text_all_products"), "whole_order" => $this->language->get("text_whole_order") );
        $data["money_transfer_methods"] = array( "on_warehouse" => $this->language->get("text_on_warehouse"), "to_payment_card" => $this->language->get("text_to_payment_card") );
        $data["consignment_displayed_information"] = array( "cn_identifier" => $this->language->get("column_cn_identifier"), "cn_number" => $this->language->get("column_cn_number"), "order_number" => $this->language->get("column_order_number"), "create_date" => $this->language->get("column_create_date"), "actual_shipping_date" => $this->language->get("column_actual_shipping_date"), "preferred_shipping_date" => $this->language->get("column_preferred_shipping_date"), "estimated_shipping_date" => $this->language->get("column_estimated_shipping_date"), "recipient_date" => $this->language->get("column_recipient_date"), "last_updated_status_date" => $this->language->get("column_last_updated_status_date"), "sender" => $this->language->get("column_sender"), "sender_address" => $this->language->get("column_sender_address"), "recipient" => $this->language->get("column_recipient"), "recipient_address" => $this->language->get("column_recipient_address"), "weight" => $this->language->get("column_weight"), "seats_amount" => $this->language->get("column_seats_amount"), "announced_price" => $this->language->get("column_announced_price"), "shipping_cost" => $this->language->get("column_shipping_cost"), "backward_shipping" => $this->language->get("column_backward_shipping"), "service_type" => $this->language->get("column_service_type"), "description" => $this->language->get("column_description"), "additional_information" => $this->language->get("column_additional_information"), "payer_type" => $this->language->get("column_payer_type"), "payment_method" => $this->language->get("column_payment_method"), "departure_type" => $this->language->get("column_departure_type"), "packing_number" => $this->language->get("column_packing_number"), "rejection_reason" => $this->language->get("column_rejection_reason"), "status" => $this->language->get("column_status") );
        $data["print_formats"] = array( "document_A4" => $this->language->get("text_cn_a4"), "document_A5" => $this->language->get("text_cn_a5"), "markings_A4" => $this->language->get("text_mark") );
        $data["template_types"] = array( "html" => $this->language->get("text_html"), "pdf" => $this->language->get("text_pdf") );
        $data["print_types"] = array( "horPrint" => $this->language->get("text_horizontally"), "verPrint" => $this->language->get("text_vertically") );
        $data["time"] = array( "hour" => $this->language->get("text_hour"), "day" => $this->language->get("text_day"), "month" => $this->language->get("text_month"), "year" => $this->language->get("text_year") );
        if( $this->config->get("config_secure") ) 
        {
            $protocol = "https://";
        }
        else
        {
            $protocol = "http://";
        }

        $data["cron_path"] = "/usr/bin/wget -t 1 -O -";
        $data["cron_update_areas"] = $data["cron_path"] . " '" . $protocol . $_SERVER["HTTP_HOST"] . "/index.php?route=extension/module/" . $this->extension . "_cron/update&type=areas&key=" . $this->settings["key_cron"] . "'";
        $data["cron_update_cities"] = $data["cron_path"] . " '" . $protocol . $_SERVER["HTTP_HOST"] . "/index.php?route=extension/module/" . $this->extension . "_cron/update&type=cities&key=" . $this->settings["key_cron"] . "'";
        $data["cron_update_warehouses"] = $data["cron_path"] . " '" . $protocol . $_SERVER["HTTP_HOST"] . "/index.php?route=extension/module/" . $this->extension . "_cron/update&type=warehouses&key=" . $this->settings["key_cron"] . "'";
        $data["cron_update_references"] = $data["cron_path"] . " '" . $protocol . $_SERVER["HTTP_HOST"] . "/index.php?route=extension/module/" . $this->extension . "_cron/update&type=references&key=" . $this->settings["key_cron"] . "'";
        $data["cron_departures_tracking"] = $data["cron_path"] . " '" . $protocol . $_SERVER["HTTP_HOST"] . "/index.php?route=extension/module/" . $this->extension . "_cron/departuresTracking&key=" . $this->settings["key_cron"] . "'";
        $data["cron_departures_tracking_href"] = $protocol . $_SERVER["HTTP_HOST"] . "/index.php?route=extension/module/" . $this->extension . "_cron/departuresTracking&key=" . $this->settings["key_cron"];
        $data["v"] = $this->version;
        $data["license"] = $this->license;
        $data["instruction_href"] = "https://oc-max.com/docs/" . $this->extension . "/instruction.html";
        $data["header"] = $this->load->controller("common/header");
        $data["column_left"] = $this->load->controller("common/column_left");
        $data["footer"] = $this->load->controller("common/footer");
        $data["support"] = $this->pr->support($this->license, $this->extension);
        $this->response->setOutput($this->load->view("extension/shipping/" . $this->extension, $data));
        return NULL;
    }

    public function getCNForm()
    {
        $this->load->language("extension/shipping/novaposhta");
        $this->load->model("extension/shipping/novaposhta");
        if( $this->request->server["REQUEST_METHOD"] == "POST" ) 
        {
            $json = array(  );
            if( $this->validate() && $this->validateCNForm() ) 
            {
                $json["success"] = $this->request->post;
            }
            else
            {
                $json = $this->error;
            }

            $this->response->addHeader("Content-Type: application/json");
            $this->response->setOutput(json_encode($json));
        }
        else
        {
            if( isset($this->error["warning"]) ) 
            {
                $data["error_warning"][] = $this->error["warning"];
            }
            else
            {
                $data["error_warning"] = array(  );
            }

            if( isset($this->request->get["order_id"]) ) 
            {
                $order_id = $this->request->get["order_id"];
                $this->load->model("sale/order");
                $order_info = $this->model_sale_order->getOrder($order_id);
                if( $order_info ) 
                {
                }
                else
                {
                    $data["error_warning"][] = $this->language->get("error_get_order");
                }

            }
            else
            {
                $order_id = 0;
                $order_info = array(  );
            }

            if( !empty($order_info["novaposhta_cn_ref"]) ) 
            {
                $cn_ref = $order_info["novaposhta_cn_ref"];
            }
            else
            {
                if( isset($this->request->get["cn_ref"]) ) 
                {
                    $cn_ref = $this->request->get["cn_ref"];
                }
                else
                {
                    $cn_ref = "";
                }

            }

            if( $cn_ref ) 
            {
                $cn = $this->novaposhta->getCN($cn_ref);
                if( $cn ) 
                {
                }
                else
                {
                    $data["error_warning"] = $this->novaposhta->error;
                    $data["error_warning"][] = $this->language->get("error_get_cn");
                }

            }
            else
            {
                $cn = array(  );
            }

            $this->document->setTitle($this->language->get("heading_title"));
            $data["breadcrumbs"] = array(  );
            $data["breadcrumbs"][] = array( "text" => $this->language->get("text_home"), "href" => $this->url->link("common/dashboard", "user_token=" . $this->session->data["user_token"], "SSL") );
            $data["breadcrumbs"][] = array( "text" => $this->language->get("text_orders"), "href" => $this->url->link("sale/order", "user_token=" . $this->session->data["user_token"], "SSL") );
            $data["breadcrumbs"][] = array( "text" => $this->language->get("text_order"), "href" => $this->url->link("sale/order/info&order_id=" . $order_id, "user_token=" . $this->session->data["user_token"], "SSL") );
            $data["cn_list"] = $this->url->link("extension/shipping/novaposhta/getCNList", "user_token=" . $this->session->data["user_token"], "SSL");
            $data["cancel"] = $this->url->link("sale/order", "user_token=" . $this->session->data["user_token"], "SSL");
            $data["user_token"] = $this->session->data["user_token"];
            $data["text_form"] = ($cn ? $this->language->get("text_form_edit") : $this->language->get("text_form_create"));
            if( $cn ) 
            {
                $data["sender"] = $cn["SenderRef"];
                $data["sender_contact_person"] = $cn["ContactSenderRef"];
                $data["sender_contact_person_phone"] = $cn["SendersPhone"];
                $data["sender_city_name"] = $cn["CitySender"];
                $data["sender_city"] = $cn["CitySenderRef"];
                $data["sender_address_name"] = $cn["SenderAddress"];
                $data["sender_address"] = $cn["SenderAddressRef"];
                $data["recipient_name"] = $cn["Recipient"];
                $data["recipient"] = $cn["RecipientRef"];
                $data["recipient_contact_person"] = $cn["ContactRecipient"];
                $data["recipient_contact_person_phone"] = $cn["RecipientsPhone"];
                $data["recipient_area_name"] = $this->novaposhta->getAreaName($cn["AreaRecipientRef"]);
                $data["recipient_area"] = $cn["AreaRecipientRef"];
                $data["recipient_warehouse_name"] = $this->novaposhta->getWarehouseName($cn["RecipientAddressRef"]);
                if( $data["recipient_warehouse_name"] ) 
                {
                    $data["recipient_address_type"] = "warehouse";
                    $data["recipient_region_name"] = "";
                    $data["recipient_city_name"] = $cn["CityRecipient"];
                    $data["recipient_city"] = $cn["CityRecipientRef"];
                    $data["recipient_warehouse"] = $cn["RecipientAddressRef"];
                    $data["recipient_street_name"] = "";
                    $data["recipient_street"] = "";
                    $data["recipient_house"] = "";
                    $data["recipient_flat"] = "";
                }
                else
                {
                    $data["recipient_address_type"] = "doors";
                    $data["recipient_region_name"] = $cn["OriginalGeoData"]["RecipientAreaRegions"];
                    $data["recipient_city_name"] = $cn["OriginalGeoData"]["RecipientCityName"];
                    $data["recipient_city"] = "";
                    $data["recipient_warehouse"] = "";
                    $data["recipient_street_name"] = $cn["OriginalGeoData"]["RecipientAddressName"];
                    $data["recipient_street"] = "";
                    $data["recipient_house"] = $cn["OriginalGeoData"]["RecipientHouse"];
                    $data["recipient_flat"] = $cn["OriginalGeoData"]["RecipientFlat"];
                    if( !$data["recipient_city_name"] ) 
                    {
                    }
                    else
                    {
                        $settlements = $this->novaposhta->searchSettlements($data["recipient_city_name"]);
                        foreach( $settlements as $settlement ) 
                        {
                            if( !($data["recipient_area"] == $this->novaposhta->getAreaRef($settlement["Area"]) && (!$data["recipient_region_name"] || $data["recipient_region_name"] == $settlement["Region"])) ) 
                            {
                            }
                            else
                            {
                                $data["recipient_region_name"] = $settlement["Region"];
                                $data["recipient_city"] = $settlement["Ref"];
                                break;
                            }

                        }
                    }

                    if( !($data["recipient_street_name"] && $data["recipient_city"]) ) 
                    {
                    }
                    else
                    {
                        $streets = $this->novaposhta->searchSettlementStreets($data["recipient_city"], $data["recipient_street_name"], 1);
                        if( !isset($streets[0]) ) 
                        {
                        }
                        else
                        {
                            $data["recipient_street"] = $streets[0]["SettlementStreetRef"];
                        }

                    }

                }

                $data["departure"] = $cn["CargoTypeRef"];
                $data["width"] = (isset($cn["OptionsSeat"][0]) ? $cn["OptionsSeat"][0]["volumetricWidth"] : "");
                $data["length"] = (isset($cn["OptionsSeat"][0]) ? $cn["OptionsSeat"][0]["volumetricLength"] : "");
                $data["height"] = (isset($cn["OptionsSeat"][0]) ? $cn["OptionsSeat"][0]["volumetricHeight"] : "");
                $data["weight"] = (isset($cn["OptionsSeat"][0]) ? $cn["OptionsSeat"][0]["weight"] : $cn["Weight"]);
                $data["volume_general"] = (isset($cn["OptionsSeat"][0]) ? $cn["OptionsSeat"][0]["volumetricVolume"] : $cn["VolumeGeneral"]);
                $data["volume_weight"] = (isset($cn["OptionsSeat"][0]) ? $cn["OptionsSeat"][0]["volumetricWeight"] : $cn["VolumeWeight"]);
                $data["seats_amount"] = $cn["SeatsAmount"];
                $data["announced_price"] = $cn["Cost"];
                $data["departure_description"] = $cn["Description"];
                if( isset($cn["CargoDetails"]) && $cn["CargoTypeRef"] == "TiresWheels" ) 
                {
                    foreach( $cn["CargoDetails"] as $cargo ) 
                    {
                        $data["tires_and_wheels"][$cargo["CargoDescriptionRef"]] = $cargo["Amount"];
                    }
                }
                else
                {
                    $data["tires_and_wheels"] = array(  );
                }

                $data["payer"] = $cn["PayerTypeRef"];
                $data["third_person"] = $cn["ThirdPersonRef"];
                $data["payment_type"] = $cn["PaymentMethodRef"];
                $data["backward_delivery"] = (isset($cn["BackwardDeliveryData"][0]) ? $cn["BackwardDeliveryData"][0]["CargoTypeRef"] : "N");
                $data["backward_delivery_total"] = (isset($cn["BackwardDeliveryData"][0]) ? $cn["BackwardDeliveryData"][0]["RedeliveryString"] : "");
                $data["payment_control"] = $cn["AfterpaymentOnGoodsCost"];
                $data["backward_delivery_payer"] = (isset($cn["BackwardDeliveryData"][0]) ? $cn["BackwardDeliveryData"][0]["PayerTypeRef"] : $this->settings["backward_delivery_payer"]);
                if( $cn["PaymentCardToken"] ) 
                {
                    $data["money_transfer_method"] = "to_payment_card";
                    $data["payment_card"] = $cn["PaymentCardToken"];
                }
                else
                {
                    $data["money_transfer_method"] = "on_warehouse";
                    $data["payment_card"] = "";
                }

                $data["departure_date"] = date("d.m.Y", strtotime($cn["DateTime"]));
                $data["preferred_delivery_date"] = ($cn["PreferredDeliveryDate"] != "0001-01-01 00:00:00" ? date("d.m.Y", strtotime($cn["PreferredDeliveryDate"])) : "");
                $data["time_interval"] = $cn["TimeIntervalRef"];
                $data["packing_number"] = $cn["PackingNumber"];
                $data["sales_order_number"] = $cn["InfoRegClientBarcodes"];
                $data["additional_information"] = $cn["AdditionalInformation"];
                $data["time_intervals"] = $this->novaposhta->getTimeIntervals($data["recipient_city"], $data["preferred_delivery_date"]);
            }
            else
            {
                if( $order_info ) 
                {
                    $order_products = $this->model_extension_shipping_novaposhta->getOrderProducts($order_id);
                    $order_totals = $this->model_sale_order->getOrderTotals($order_id);
                    $find_order = array( "{order_id}", "{invoice_no}", "{invoice_prefix}", "{store_name}", "{store_url}", "{customer}", "{firstname}", "{lastname}", "{email}", "{telephone}", "{payment_firstname}", "{payment_lastname}", "{payment_company}", "{payment_address_1}", "{payment_address_2}", "{payment_postcode}", "{payment_city}", "{payment_zone}", "{payment_country}", "{shipping_firstname}", "{shipping_lastname}", "{shipping_company}", "{shipping_address_1}", "{shipping_address_2}", "{shipping_postcode}", "{shipping_city}", "{shipping_zone}", "{shipping_country}", "{comment}", "{total}", "{commission}", "{date_added}", "{date_modified}" );
                    $replace_order = array( "order_id" => $order_info["order_id"], "invoice_no" => $order_info["invoice_no"], "invoice_prefix" => $order_info["invoice_prefix"], "store_name" => $order_info["store_name"], "store_url" => $order_info["store_url"], "customer" => $order_info["customer"], "firstname" => $order_info["firstname"], "lastname" => $order_info["lastname"], "email" => $order_info["email"], "telephone" => $order_info["telephone"], "payment_firstname" => $order_info["payment_firstname"], "payment_lastname" => $order_info["payment_lastname"], "payment_company" => $order_info["payment_company"], "payment_address_1" => $order_info["payment_address_1"], "payment_address_2" => $order_info["payment_address_2"], "payment_postcode" => $order_info["payment_postcode"], "payment_city" => $order_info["payment_city"], "payment_zone" => $order_info["payment_zone"], "payment_country" => $order_info["payment_country"], "shipping_firstname" => $order_info["shipping_firstname"], "shipping_lastname" => $order_info["shipping_lastname"], "shipping_company" => $order_info["shipping_company"], "shipping_address_1" => $order_info["shipping_address_1"], "shipping_address_2" => $order_info["shipping_address_2"], "shipping_postcode" => $order_info["shipping_postcode"], "shipping_city" => $order_info["shipping_city"], "shipping_zone" => $order_info["shipping_zone"], "shipping_country" => $order_info["shipping_country"], "comment" => $order_info["comment"], "total" => $order_info["total"], "commission" => $order_info["commission"], "date_added" => $order_info["date_added"], "date_modified" => $order_info["date_modified"] );
                    foreach( $this->model_extension_shipping_novaposhta->getSimpleFields($order_id) as $k => $v ) 
                    {
                        if( in_array("{" . $k . "}", $find_order) ) 
                        {
                        }
                        else
                        {
                            $find_order[] = "{" . $k . "}";
                            $replace_order[$k] = $v;
                        }

                    }
                    $find_product = array( "{name}", "{model}", "{sku}", "{ean}", "{upc}", "{jan}", "{isbn}", "{mpn}", "{quantity}" );
                    $data["sender"] = $this->settings["sender"];
                    $data["sender_contact_person"] = $this->settings["sender_contact_person"];
                    $data["sender_city_name"] = $this->settings["sender_city_name"];
                    $data["sender_city"] = $this->settings["sender_city"];
                    $data["sender_address_name"] = $this->settings["sender_address_name"];
                    $data["sender_address"] = $this->settings["sender_address"];
                    $data["recipient_name"] = $this->settings["recipient_name"];
                    $data["recipient"] = $this->settings["recipient"];
                    $data["recipient_contact_person"] = preg_replace("/ {2,}/", " ", mb_convert_case(trim(str_replace($find_order, $replace_order, $this->settings["recipient_contact_person"])), MB_CASE_TITLE, "UTF-8"));
                    $data["recipient_contact_person_phone"] = preg_replace("/[^0-9]/", "", str_replace($find_order, $replace_order, $this->settings["recipient_contact_person_phone"]));
                    $data["recipient_address_type"] = "warehouse";
                    $data["recipient_area_name"] = trim(str_replace($find_order, $replace_order, $this->settings["recipient_area"]));
                    $data["recipient_area"] = $this->novaposhta->getAreaRef($data["recipient_area_name"]);
                    $data["recipient_region_name"] = "";
                    $data["recipient_city_name"] = trim(str_replace($find_order, $replace_order, $this->settings["recipient_city"]));
                    $data["recipient_city"] = $this->novaposhta->getCityRef($data["recipient_city_name"]);
                    $data["recipient_warehouse_name"] = trim(str_replace($find_order, $replace_order, $this->settings["recipient_warehouse"]));
                    $data["recipient_warehouse"] = $this->novaposhta->getWarehouseRef($data["recipient_warehouse_name"]);
                    $data["recipient_address"] = trim(str_replace($find_order, $replace_order, $this->settings["recipient_address"]));
                    $data["recipient_street_name"] = trim(str_replace($find_order, $replace_order, $this->settings["recipient_street"]));
                    $data["recipient_street"] = "";
                    $data["recipient_house"] = trim(str_replace($find_order, $replace_order, $this->settings["recipient_house"]));
                    $data["recipient_flat"] = trim(str_replace($find_order, $replace_order, $this->settings["recipient_flat"]));
                    if( utf8_strlen($data["recipient_contact_person_phone"]) != 10 ) 
                    {
                    }
                    else
                    {
                        $data["recipient_contact_person_phone"] = "38" . $data["recipient_contact_person_phone"];
                    }

                    if( !(!$data["recipient_warehouse"] && !preg_match("/відділення|отделение|поштомат|почтомат|склад нп/ui", $data["recipient_warehouse_name"]) && (!isset($order_info["shipping_code"]) || $order_info["shipping_code"] == "novaposhta.doors")) ) 
                    {
                    }
                    else
                    {
                        $data["recipient_address_type"] = "doors";
                        $settlements = $this->novaposhta->searchSettlements($data["recipient_city_name"]);
                        foreach( $settlements as $settlement ) 
                        {
                            if( !($data["recipient_area"] == $this->novaposhta->getAreaRef($settlement["Area"]) && (!$data["recipient_region_name"] || $data["recipient_region_name"] == $settlement["Region"])) ) 
                            {
                            }
                            else
                            {
                                $data["recipient_region_name"] = $settlement["Region"];
                                $data["recipient_city"] = $settlement["Ref"];
                                break;
                            }

                        }
                    }

                    if( !$data["recipient_address"] || $data["recipient_street_name"] ) 
                    {
                    }
                    else
                    {
                        $address = $this->parseAddress($data["recipient_address"]);
                        $data["recipient_street_name"] = $address["street_type"] . " " . $address["street"];
                        $data["recipient_house"] = $address["house"];
                        $data["recipient_flat"] = $address["flat"];
                    }

                    if( !($data["recipient_street_name"] && $data["recipient_city"]) ) 
                    {
                    }
                    else
                    {
                        $streets = $this->novaposhta->searchSettlementStreets($data["recipient_city"], $data["recipient_street_name"], 1);
                        if( !isset($streets[0]) ) 
                        {
                        }
                        else
                        {
                            $data["recipient_street"] = $streets[0]["SettlementStreetRef"];
                        }

                    }

                    $weight = $this->novaposhta->getDepartureWeight($order_products);
                    $departure = $this->novaposhta->getDepartureVolume($order_products);
                    if( $this->settings["autodetection_departure_type"] ) 
                    {
                        $data["departure"] = $this->novaposhta->getDepartureType($departure, $weight);
                    }
                    else
                    {
                        $data["departure"] = $this->settings["departure_type"];
                    }

                    if( $this->settings["seats_amount"] ) 
                    {
                        $data["seats_amount"] = $this->settings["seats_amount"];
                    }
                    else
                    {
                        $data["seats_amount"] = $this->novaposhta->getDepartureSeats($order_products);
                    }

                    $data["tires_and_wheels"] = array(  );
                    $data["width"] = $departure["width"];
                    $data["length"] = $departure["length"];
                    $data["height"] = $departure["height"];
                    $data["weight"] = $weight;
                    $data["volume_general"] = $departure["volume"];
                    $data["volume_weight"] = $data["volume_general"] * 250;
                    $data["announced_price"] = $this->convertToUAH($this->getAnnouncedPrice($order_totals), $order_info["currency_code"], $order_info["currency_value"]);
                    $data["departure_description"] = $this->settings["departure_description"];
                    if( $this->settings["shipping_methods"][$data["recipient_address_type"]]["free_shipping"] && $this->settings["shipping_methods"][$data["recipient_address_type"]]["free_shipping"] <= $this->convertToUAH($order_totals[count($order_totals) - 1]["value"], $order_info["currency_code"], $order_info["currency_value"]) ) 
                    {
                        $data["payer"] = "Sender";
                    }
                    else
                    {
                        $data["payer"] = $this->settings["payer"];
                    }

                    if( isset($order_info["payment_code"]) && isset($this->settings["payment_cod"]) && (in_array($order_info["payment_code"], $this->settings["payment_cod"]) || in_array(stristr($order_info["payment_code"], ".", true), $this->settings["payment_cod"])) ) 
                    {
                        $data["backward_delivery"] = "Money";
                    }
                    else
                    {
                        $data["backward_delivery"] = $this->settings["backward_delivery"];
                    }

                    if( !((!$data["backward_delivery"] || $data["backward_delivery"] == "N" || !$data["announced_price"]) && $this->settings["announced_price_default"]) ) 
                    {
                    }
                    else
                    {
                        $data["announced_price"] = $this->settings["announced_price_default"];
                    }

                    $cod_plus_settings = $this->config->get("payment_cod_plus");
                    if( isset($cod_plus_settings[$order_info["shipping_code"]]) ) 
                    {
                        $cod_plus_free = $cod_plus_settings[$order_info["shipping_code"]]["free"];
                    }
                    else
                    {
                        if( isset($cod_plus_settings[stristr($order_info["shipping_code"], ".", true)]) ) 
                        {
                            $cod_plus_free = $cod_plus_settings[stristr($order_info["shipping_code"], ".", true)]["free"];
                        }
                        else
                        {
                            $cod_plus_free = 0;
                        }

                    }

                    if( $cod_plus_free && $cod_plus_free <= $data["announced_price"] ) 
                    {
                        $data["backward_delivery_payer"] = "Sender";
                    }
                    else
                    {
                        $data["backward_delivery_payer"] = $this->settings["backward_delivery_payer"];
                    }

                    $data["third_person"] = $this->settings["third_person"];
                    $data["payment_type"] = $this->settings["payment_type"];
                    $data["backward_delivery_total"] = $data["announced_price"];
                    $data["payment_control"] = "";
                    $data["money_transfer_method"] = $this->settings["money_transfer_method"];
                    $data["payment_card"] = $this->settings["default_payment_card"];
                    $data["departure_date"] = date("d.m.Y");
                    $data["preferred_delivery_date"] = str_replace($find_order, $replace_order, $this->settings["preferred_delivery_date"]);
                    $data["time_interval"] = str_replace($find_order, $replace_order, $this->settings["preferred_delivery_time"]);
                    $data["packing_number"] = "";
                    $data["sales_order_number"] = $order_id;
                    $data["additional_information"] = "";
                    if( !$data["time_interval"] ) 
                    {
                    }
                    else
                    {
                        $preferred_delivery_time = str_replace(":", "", $data["time_interval"]);
                        $data["time_intervals"] = $this->novaposhta->getTimeIntervals($data["recipient_city"], $data["preferred_delivery_date"]);
                        foreach( (array) $data["time_intervals"] as $interval ) 
                        {
                            $start = str_replace(":", "", $interval["Start"]);
                            $end = str_replace(":", "", $interval["End"]);
                            if( !($start <= $preferred_delivery_time && $preferred_delivery_time <= $end) ) 
                            {
                            }
                            else
                            {
                                $data["time_interval"] = $interval["Number"];
                                break;
                            }

                        }
                    }

                    $template = explode("|", $this->settings["departure_additional_information"]);
                    if( !$template[0] ) 
                    {
                    }
                    else
                    {
                        $data["additional_information"] .= preg_replace(array( "/\\s\\s+/", "/\\r\\r+/", "/\\n\\n+/" ), " ", trim(str_replace($find_order, $replace_order, $template[0])));
                    }

                    if( !isset($template[1]) ) 
                    {
                    }
                    else
                    {
                        foreach( $order_products as $k => $product ) 
                        {
                            $replace_product = array( "name" => $product["name"], "model" => $product["model"], "sku" => $product["sku"], "ean" => $product["ean"], "upc" => $product["upc"], "jan" => $product["jan"], "isbn" => $product["isbn"], "mpn" => $product["mpn"], "quantity" => $product["quantity"] );
                            $data["additional_information"] .= preg_replace(array( "/\\s\\s+/", "/\\r\\r+/", "/\\n\\n+/" ), " ", trim(str_replace($find_product, $replace_product, $template[1])));
                        }
                    }

                }
                else
                {
                    $data["sender"] = $this->settings["sender"];
                    $data["sender_contact_person"] = $this->settings["sender_contact_person"];
                    $data["sender_city_name"] = $this->settings["sender_city_name"];
                    $data["sender_city"] = $this->settings["sender_city"];
                    $data["sender_address_name"] = $this->settings["sender_address_name"];
                    $data["sender_address"] = $this->settings["sender_address"];
                    $data["recipient_name"] = $this->settings["recipient_name"];
                    $data["recipient"] = $this->settings["recipient"];
                    $data["recipient_contact_person"] = "";
                    $data["recipient_contact_person_phone"] = "";
                    $data["recipient_address_type"] = "warehouse";
                    $data["recipient_area_name"] = "";
                    $data["recipient_area"] = "";
                    $data["recipient_region_name"] = "";
                    $data["recipient_city_name"] = "";
                    $data["recipient_city"] = "";
                    $data["recipient_warehouse_name"] = "";
                    $data["recipient_warehouse"] = "";
                    $data["recipient_street_name"] = "";
                    $data["recipient_street"] = "";
                    $data["recipient_house"] = "";
                    $data["recipient_flat"] = "";
                    $data["departure"] = $this->settings["departure_type"];
                    $data["tires_and_wheels"] = array(  );
                    $data["width"] = $this->settings["dimensions_w"] + $this->settings["allowance_w"];
                    $data["length"] = $this->settings["dimensions_l"] + $this->settings["allowance_l"];
                    $data["height"] = $this->settings["dimensions_h"] + $this->settings["allowance_h"];
                    $data["weight"] = $this->settings["weight"];
                    $data["volume_general"] = max(round((($this->settings["dimensions_w"] + $this->settings["allowance_w"]) * ($this->settings["dimensions_l"] + $this->settings["allowance_l"]) * ($this->settings["dimensions_h"] + $this->settings["allowance_h"])) / 1000000, 4), 0.0001);
                    $data["volume_weight"] = $data["volume_general"] * 250;
                    $data["seats_amount"] = $this->settings["seats_amount"];
                    $data["announced_price"] = $this->settings["announced_price_default"];
                    $data["departure_description"] = $this->settings["departure_description"];
                    $data["payer"] = $this->settings["payer"];
                    $data["third_person"] = $this->settings["third_person"];
                    $data["payment_type"] = $this->settings["payment_type"];
                    $data["backward_delivery"] = $this->settings["backward_delivery"];
                    $data["backward_delivery_total"] = $data["announced_price"];
                    $data["payment_control"] = "";
                    $data["backward_delivery_payer"] = $this->settings["backward_delivery_payer"];
                    $data["money_transfer_method"] = $this->settings["money_transfer_method"];
                    $data["payment_card"] = $this->settings["default_payment_card"];
                    $data["departure_date"] = date("d.m.Y");
                    $data["preferred_delivery_date"] = "";
                    $data["time_interval"] = "";
                    $data["packing_number"] = "";
                    $data["sales_order_number"] = "";
                    $data["additional_information"] = "";
                }

            }

            $data["references"] = $this->novaposhta->getReferences();
            if( isset($data["references"]["senders"]) ) 
            {
                $data["senders"] = $data["references"]["senders"];
            }
            else
            {
                $data["senders"] = array(  );
            }

            if( isset($data["references"]["sender_options"][$data["sender"]]) ) 
            {
                $data["sender_options"] = $data["references"]["sender_options"][$data["sender"]];
            }
            else
            {
                $data["sender_options"] = array(  );
            }

            if( isset($data["references"]["sender_contact_persons"][$data["sender"]]) ) 
            {
                $data["sender_contact_persons"] = $data["references"]["sender_contact_persons"][$data["sender"]];
            }
            else
            {
                $data["sender_contact_persons"] = array(  );
            }

            if( !isset($data["references"]["tires_and_wheels"]) ) 
            {
            }
            else
            {
                foreach( $data["references"]["tires_and_wheels"] as $i => $v ) 
                {
                    $data["references"]["tires_and_wheels"][$i]["Description"] = $v[$this->novaposhta->description_field];
                    unset($data["references"]["tires_and_wheels"][$i]["DescriptionRu"]);
                }
            }

            $data["totals"] = array(  );
            if( !isset($order_totals) ) 
            {
            }
            else
            {
                foreach( $order_totals as $total ) 
                {
                    $data["totals"][] = array( "title" => strip_tags($total["title"]), "price" => $this->convertToUAH($total["value"], $order_info["currency_code"], $order_info["currency_value"]), "status" => (isset($this->settings["announced_price"]) && in_array($total["code"], (array) $this->settings["announced_price"]) ? 1 : 0) );
                }
            }

            $data["money_transfer_methods"] = array( "on_warehouse" => $this->language->get("text_on_warehouse"), "to_payment_card" => $this->language->get("text_to_payment_card") );
            $data["order_id"] = $order_id;
            $data["cn_ref"] = $cn_ref;
            $data["v"] = $this->version;
            $data["header"] = $this->load->controller("common/header");
            $data["column_left"] = $this->load->controller("common/column_left");
            $data["footer"] = $this->load->controller("common/footer");
            $this->response->setOutput($this->load->view("extension/shipping/novaposhta_cn_form", $data));
            return NULL;
        }

    }

    public function getCNList()
    {
        $this->load->language("extension/shipping/novaposhta");
        $this->document->setTitle($this->language->get("heading_title"));
        $url = "";
        if( isset($this->request->get["filter_cn_type"]) ) 
        {
            $filter_cn_type = $this->request->get["filter_cn_type"];
            foreach( $this->request->get["filter_cn_type"] as $v ) 
            {
                $url .= "&filter_cn_type[]=" . urlencode(html_entity_decode($v, ENT_QUOTES, "UTF-8"));
            }
        }
        else
        {
            $filter_cn_type = array(  );
        }

        if( isset($this->request->get["filter_departure_date_from"]) ) 
        {
            $filter_departure_date_from = $this->request->get["filter_departure_date_from"];
            $url .= "&filter_departure_date_from=" . urlencode(html_entity_decode($this->request->get["filter_departure_date_from"], ENT_QUOTES, "UTF-8"));
        }
        else
        {
            $filter_departure_date_from = date("d.m.Y");
        }

        if( isset($this->request->get["filter_departure_date_to"]) ) 
        {
            $filter_departure_date_to = $this->request->get["filter_departure_date_to"];
            $url .= "&filter_departure_date_to=" . urlencode(html_entity_decode($this->request->get["filter_departure_date_to"], ENT_QUOTES, "UTF-8"));
        }
        else
        {
            $filter_departure_date_to = "";
        }

        if( isset($this->request->get["page"]) ) 
        {
            $page = $this->request->get["page"];
        }
        else
        {
            $page = 1;
        }

        if( $this->settings["print_format"] == "document_A4" ) 
        {
            $print_format = "printDocument";
            $page_format = "A4";
        }
        else
        {
            if( $this->settings["print_format"] == "document_A5" ) 
            {
                $print_format = "printDocument";
                $page_format = "A5";
            }
            else
            {
                if( $this->settings["print_format"] == "markings_A4" ) 
                {
                    $print_format = "printMarkings";
                    $page_format = "A4";
                    if( $this->settings["template_type"] != "html" ) 
                    {
                    }
                    else
                    {
                        $print_direction = $this->settings["print_type"];
                        $position = $this->settings["print_start"];
                    }

                }

            }

        }

        $data["customized_printing"] = "https://my.novaposhta.ua/orders/" . $print_format . "/apiKey/" . $this->novaposhta->key_api . "/type/" . $this->settings["template_type"] . "/pageFormat/" . $page_format . "/copies/" . $this->settings["number_of_copies"];
        if( !isset($print_direction) ) 
        {
        }
        else
        {
            $data["customized_printing"] .= "/printDirection/" . $print_direction . "/position/" . $position;
        }

        $data["print_cn_pdf"] = "https://my.novaposhta.ua/orders/printDocument/apiKey/" . $this->novaposhta->key_api . "/type/pdf";
        $data["print_cn_html"] = "https://my.novaposhta.ua/orders/printDocument/apiKey/" . $this->novaposhta->key_api . "/type/html";
        $data["print_markings_pdf"] = "https://my.novaposhta.ua/orders/printMarkings/apiKey/" . $this->novaposhta->key_api . "/type/pdf";
        $data["print_markings_html"] = "https://my.novaposhta.ua/orders/printMarkings/apiKey/" . $this->novaposhta->key_api . "/type/html";
        $data["print_markings_zebra_pdf"] = "https://my.novaposhta.ua/orders/printMarkings/apiKey/" . $this->novaposhta->key_api . "/zebra/zebra/type/pdf";
        $data["print_markings_zebra_html"] = "https://my.novaposhta.ua/orders/printMarkings/apiKey/" . $this->novaposhta->key_api . "/zebra/zebra/type/html";
        $data["add"] = $this->url->link("extension/shipping/novaposhta/getCNForm", "user_token=" . $this->session->data["user_token"], "SSL");
        $data["back_to_orders"] = $this->url->link("sale/order", "user_token=" . $this->session->data["user_token"], "SSL");
        $data["breadcrumbs"] = array(  );
        $data["breadcrumbs"][] = array( "text" => $this->language->get("text_home"), "href" => $this->url->link("common/dashboard", "user_token=" . $this->session->data["user_token"], "SSL") );
        $data["breadcrumbs"][] = array( "text" => $this->language->get("text_orders"), "href" => $this->url->link("sale/order", "user_token=" . $this->session->data["user_token"], "SSL") );
        $data["user_token"] = $this->session->data["user_token"];
        if( isset($this->session->data["success"]) ) 
        {
            $data["success"] = $this->session->data["success"];
            $data["cn_number"] = $this->session->data["cn"];
            unset($this->session->data["success"]);
            unset($this->session->data["cn"]);
        }
        else
        {
            $data["success"] = "";
            $data["cn_number"] = "";
        }

        $cns = $this->novaposhta->getCNList($filter_departure_date_from, $filter_departure_date_to, $filter_cn_type);
        $cns_total = count($cns);
        if( !($cns && is_array($cns)) ) 
        {
        }
        else
        {
            $this->load->model("extension/shipping/novaposhta");
            $service_types = $this->novaposhta->getReferences();
            foreach( $service_types as $i => $service_type ) 
            {
                foreach( $service_type as $k => $v ) 
                {
                    if( !isset($v["Ref"]) ) 
                    {
                    }
                    else
                    {
                        $service_types[$i][$v["Ref"]] = $v["Description"];
                        unset($service_types[$i][$k]);
                    }

                }
            }
            $cns = array_slice($cns, ($page - 1) * $this->config->get("config_limit_admin"), $this->config->get("config_limit_admin"));
            foreach( $cns as $k => $cn ) 
            {
                $order = $this->model_extension_shipping_novaposhta->getOrderByDocumentNumber($cn["IntDocNumber"]);
                if( $order ) 
                {
                    $cns[$k]["order"] = $this->url->link("sale/order/info", "user_token=" . $this->session->data["user_token"] . "&order_id=" . $order["order_id"], "SSL");
                    $cns[$k]["order_id"] = $order["order_id"];
                }
                else
                {
                    if( !$this->settings["display_all_consignments"] && !$order ) 
                    {
                        unset($cns[$k]);
                    }

                }

                $cns[$k]["create_date"] = date($this->language->get("datetime_format"), strtotime($cn["CreateTime"]));
                $cns[$k]["actual_shipping_date"] = date($this->language->get("datetime_format"), strtotime($cn["DateTime"]));
                if( strtotime($cn["PreferredDeliveryDate"]) ) 
                {
                    $cns[$k]["preferred_shipping_date"] = date($this->language->get("datetime_format"), strtotime($cn["PreferredDeliveryDate"]));
                }
                else
                {
                    $cns[$k]["preferred_shipping_date"] = "";
                }

                $cns[$k]["estimated_shipping_date"] = date($this->language->get("date_format_short"), strtotime($cn["EstimatedDeliveryDate"]));
                if( strtotime($cn["RecipientDateTime"]) ) 
                {
                    $cns[$k]["recipient_date"] = date($this->language->get("datetime_format"), strtotime($cn["RecipientDateTime"]));
                }
                else
                {
                    $cns[$k]["recipient_date"] = "";
                }

                $cns[$k]["last_updated_status_date"] = date($this->language->get("datetime_format"), strtotime($cn["DateLastUpdatedStatus"]));
                $cns[$k]["sender"] = $cn["SenderDescription"];
                if( !$cn["SendersPhone"] ) 
                {
                }
                else
                {
                    $cns[$k]["sender"] .= " " . $cn["SendersPhone"];
                }

                $cns[$k]["sender_address"] = $cn["CitySenderDescription"] . ", " . $cn["SenderAddressDescription"];
                $cns[$k]["recipient"] = $cn["RecipientDescription"] . ": " . $cn["RecipientContactPerson"] . " " . $cn["RecipientContactPhone"];
                $cns[$k]["recipient_address"] = $cn["CityRecipientDescription"] . ", " . $cn["RecipientAddressDescription"];
                $cns[$k]["announced_price"] = $this->currency->format($cn["Cost"], "UAH", 1);
                $cns[$k]["shipping_cost"] = $this->currency->format($cn["CostOnSite"], "UAH", 1);
                if( $cn["BackwardDeliveryCargoType"] ) 
                {
                    $cns[$k]["backward_shipping"] = $cn["BackwardDeliveryCargoType"] . ": " . $this->currency->format($cn["BackwardDeliverySum"], "UAH", 1);
                }
                else
                {
                    $cns[$k]["backward_shipping"] = "";
                }

                $cns[$k]["service_type"] = $service_types["service_types"][$cn["ServiceType"]];
                $cns[$k]["payer_type"] = $service_types["payer_types"][$cn["PayerType"]];
                $cns[$k]["payment_method"] = $service_types["payment_types"][$cn["PaymentMethod"]];
                $cns[$k]["departure_type"] = $service_types["cargo_types"][$cn["CargoType"]];
            }
        }

        $data["cns"] = $cns;
        $pagination = new Pagination();
        $pagination->total = $cns_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get("config_limit_admin");
        $pagination->url = $this->url->link("extension/shipping/novaposhta/getCNList", "user_token=" . $this->session->data["user_token"] . $url . "&page={page}", "SSL");
        $data["pagination"] = $pagination->render();
        $data["results"] = sprintf($this->language->get("text_pagination"), ($cns_total ? ($page - 1) * $this->config->get("config_limit_admin") + 1 : 0), ($cns_total - $this->config->get("config_limit_admin") < ($page - 1) * $this->config->get("config_limit_admin") ? $cns_total : ($page - 1) * $this->config->get("config_limit_admin") + $this->config->get("config_limit_admin")), $cns_total, ceil($cns_total / $this->config->get("config_limit_admin")));
        $data["key_api"] = $this->novaposhta->key_api;
        $data["filters"] = array( "RedeliveryMoney" => $this->language->get("text_redelivery_money"), "UnassembledCargo" => $this->language->get("text_unassembled_cargo") );
        $data["print_formats"] = array( "document_A4" => $this->language->get("text_cn_a4"), "document_A5" => $this->language->get("text_cn_a5"), "markings_A4" => $this->language->get("text_mark") );
        $data["template_types"] = array( "html" => $this->language->get("text_html"), "pdf" => $this->language->get("text_pdf") );
        $data["print_types"] = array( "horPrint" => $this->language->get("text_horizontally"), "verPrint" => $this->language->get("text_vertically") );
        if( !empty($this->settings["consignment_displayed_information"]) ) 
        {
            $data["displayed_information"] = $this->settings["consignment_displayed_information"];
        }
        else
        {
            $data["displayed_information"] = array(  );
        }

        $data["filter_cn_type"] = $filter_cn_type;
        $data["filter_departure_date_from"] = $filter_departure_date_from;
        $data["filter_departure_date_to"] = $filter_departure_date_to;
        $data["v"] = $this->version;
        $data["header"] = $this->load->controller("common/header");
        $data["column_left"] = $this->load->controller("common/column_left");
        $data["footer"] = $this->load->controller("common/footer");
        $this->response->setOutput($this->load->view("extension/shipping/novaposhta_cn_list", $data));
        return NULL;
    }

    public function saveCN()
    {
        $this->load->language("extension/shipping/novaposhta");
        $this->load->model("extension/shipping/novaposhta");
        if( !($this->request->server["REQUEST_METHOD"] == "POST" && $this->validate() && $this->validateCNForm()) ) 
        {
        }
        else
        {
            $sender_address_type = ($this->novaposhta->getWarehouseName($this->request->post["sender_address"]) ? "Warehouse" : "Doors");
            if( !$this->request->post["recipient"] ) 
            {
                $result = $this->novaposhta->getCounterparties("Recipient", $this->request->post["recipient_name"]);
                if( $result ) 
                {
                    $recipient_data = array_shift($result);
                }
                else
                {
                    $recipient_name_parts = explode(" ", preg_replace("/ {2,}/", " ", trim($this->request->post["recipient_name"])), 2);
                    if( !empty($recipient_name_parts[1]) ) 
                    {
                    }
                    else
                    {
                        $recipient_name_parts[1] = $recipient_name_parts[0];
                        $recipient_name_parts[0] = "ПП";
                    }

                    $ownership_form = $this->getOwnreshipForm($recipient_name_parts[0]);
                    $properties_r = array( "CityRef" => $this->request->post["recipient_city"], "FirstName" => $recipient_name_parts[1], "CounterpartyType" => "Organization", "CounterpartyProperty" => "Recipient", "OwnershipForm" => $ownership_form["Ref"] );
                    $recipient_data = $this->novaposhta->saveCounterparties($properties_r);
                }

                if( $recipient_data ) 
                {
                    $recipient = $recipient_data["Ref"];
                }
                else
                {
                    $recipient = false;
                }

            }
            else
            {
                $recipient = $this->request->post["recipient"];
            }

            $recipient_contact_person = "";
            $recipient_contact_person_phone = "";
            if( !$recipient ) 
            {
            }
            else
            {
                $recipient_contact_person_name_parts = explode(" ", preg_replace("/ {2,}/", " ", mb_convert_case(trim($this->request->post["recipient_contact_person"]), MB_CASE_TITLE, "UTF-8")));
                $recipient_contact_persons = $this->novaposhta->getContactPerson($recipient, $recipient_contact_person_name_parts[0] . " " . $recipient_contact_person_name_parts[1]);
                if( !$recipient_contact_persons ) 
                {
                }
                else
                {
                    foreach( $recipient_contact_persons as $contact_person ) 
                    {
                        if( $contact_person["Phones"] != $this->request->post["recipient_contact_person_phone"] ) 
                        {
                        }
                        else
                        {
                            if( !(isset($recipient_contact_person_name_parts[2]) && $contact_person["MiddleName"] != $recipient_contact_person_name_parts[2]) ) 
                            {
                            }
                            else
                            {
                                $contact_person["CounterpartyRef"] = $recipient;
                                $contact_person["MiddleName"] = $recipient_contact_person_name_parts[2];
                                $contact_person["Phone"] = $contact_person["Phones"];
                                $this->novaposhta->updateContactPerson($contact_person);
                            }

                            $recipient_contact_person = $contact_person["Ref"];
                            $recipient_contact_person_phone = $contact_person["Phones"];
                            break;
                        }

                    }
                }

                if( $recipient_contact_person ) 
                {
                }
                else
                {
                    $properties_c_p = array( "CounterpartyRef" => $recipient, "LastName" => $recipient_contact_person_name_parts[0], "FirstName" => $recipient_contact_person_name_parts[1], "MiddleName" => (isset($recipient_contact_person_name_parts[2]) ? $recipient_contact_person_name_parts[2] : ""), "Phone" => $this->request->post["recipient_contact_person_phone"], "Email" => "" );
                    $result = $this->novaposhta->saveContactPerson($properties_c_p);
                    if( !$result ) 
                    {
                    }
                    else
                    {
                        $recipient_contact_person = $result["Ref"];
                        $recipient_contact_person_phone = $result["Phones"];
                    }

                }

            }

            $recipient_address = "";
            if( $this->request->post["recipient_address_type"] == "warehouse" ) 
            {
                $recipient_address = $this->request->post["recipient_warehouse"];
            }
            else
            {
                $counterparty_addresses = $this->novaposhta->getCounterpartyAddresses($recipient, "Recipient", $this->request->post["recipient_city"]);
                if( !$counterparty_addresses ) 
                {
                }
                else
                {
                    foreach( $counterparty_addresses as $k => $v ) 
                    {
                        if( !($v["BuildingDescription"] == $this->request->post["recipient_house"] && (!$this->request->post["recipient_flat"] && mb_stripos($v["Description"], "кв.") === false || $this->request->post["recipient_flat"] && mb_stripos($v["Description"], "кв. " . $this->request->post["recipient_flat"]) !== false)) ) 
                        {
                        }
                        else
                        {
                            $recipient_address = $k;
                            break;
                        }

                    }
                }

                if( $recipient_address ) 
                {
                }
                else
                {
                    $new_address = array( "NewAddress" => 1, "RecipientType" => (isset($recipient_data["CounterpartyType"]) ? $recipient_data["CounterpartyType"] : "PrivatePerson"), "RecipientArea" => $this->request->post["recipient_area_name"], "RecipientAreaRegions" => $this->request->post["recipient_region_name"], "RecipientCityName" => $this->request->post["recipient_city_name"], "RecipientAddressName" => $this->request->post["recipient_street_name"], "RecipientHouse" => $this->request->post["recipient_house"], "RecipientFlat" => $this->request->post["recipient_flat"] );
                }

            }

            $properties_cn = array( "Sender" => $this->request->post["sender"], "ContactSender" => $this->request->post["sender_contact_person"], "SendersPhone" => $this->request->post["sender_contact_person_phone"], "CitySender" => $this->request->post["sender_city"], "SenderAddress" => $this->request->post["sender_address"], "Recipient" => $recipient, "ContactRecipient" => $recipient_contact_person, "RecipientsPhone" => $recipient_contact_person_phone, "CargoType" => $this->request->post["departure_type"], "SeatsAmount" => $this->request->post["seats_amount"], "Cost" => $this->request->post["announced_price"], "Description" => $this->request->post["departure_description"], "PayerType" => $this->request->post["payer"], "PaymentMethod" => $this->request->post["payment_type"], "DateTime" => $this->request->post["departure_date"], "ServiceType" => $sender_address_type . ucfirst($this->request->post["recipient_address_type"]) );
            if( $recipient_address ) 
            {
                $properties_cn["CityRecipient"] = $this->request->post["recipient_city"];
                $properties_cn["RecipientAddress"] = $recipient_address;
            }
            else
            {
                if( isset($new_address) ) 
                {
                    $properties_cn = array_merge($properties_cn, $new_address);
                }

            }

            if( !(isset($recipient_data["CounterpartyType"]) && $recipient_data["CounterpartyType"] == "Organization") ) 
            {
            }
            else
            {
                if( !empty($recipient_data["OwnershipForm"]) ) 
                {
                    $properties_cn["OwnershipForm"] = $recipient_data["OwnershipForm"];
                }
                else
                {
                    if( !empty($recipient_data["OwnershipFormRef"]) ) 
                    {
                        $properties_cn["OwnershipForm"] = $recipient_data["OwnershipFormRef"];
                    }

                }

            }

            if( !isset($this->request->post["third_person"]) ) 
            {
            }
            else
            {
                $properties_cn["ThirdPerson"] = $this->request->post["third_person"];
            }

            if( isset($this->request->post["recipient_warehouse_name"]) && preg_match("/поштомат|почтомат/ui", $this->request->post["recipient_warehouse_name"]) && ($this->request->post["departure_type"] == "Parcel" || $this->request->post["departure_type"] == "Cargo") ) 
            {
                $properties_cn["OptionsSeat"][] = array( "volumetricVolume" => $this->request->post["volume_general"], "volumetricWidth" => $this->request->post["width"], "volumetricLength" => $this->request->post["length"], "volumetricHeight" => $this->request->post["height"], "weight" => $this->request->post["weight"], "volumetricWeight" => $this->request->post["volume_weight"] );
            }
            else
            {
                if( $this->request->post["departure_type"] == "TiresWheels" ) 
                {
                    foreach( $this->request->post["tires_and_wheels"] as $ref => $amount ) 
                    {
                        if( !$amount ) 
                        {
                        }
                        else
                        {
                            $properties_cn["CargoDetails"][] = array( "CargoDescription" => $ref, "Amount" => $amount );
                        }

                    }
                }
                else
                {
                    if( !isset($this->request->post["weight"]) ) 
                    {
                    }
                    else
                    {
                        $properties_cn["Weight"] = $this->request->post["weight"];
                    }

                    if( !isset($this->request->post["volume_general"]) ) 
                    {
                    }
                    else
                    {
                        $properties_cn["VolumeGeneral"] = $this->request->post["volume_general"];
                    }

                    if( !isset($this->request->post["volume_weight"]) ) 
                    {
                    }
                    else
                    {
                        $properties_cn["VolumeWeight"] = $this->request->post["volume_weight"];
                    }

                }

            }

            if( !($this->request->post["backward_delivery"] && $this->request->post["backward_delivery"] != "N") ) 
            {
            }
            else
            {
                if( $this->request->post["backward_delivery"] != "Money" ) 
                {
                }
                else
                {
                    $properties_cn["BackwardDeliveryData"][0] = array( "CargoType" => $this->request->post["backward_delivery"], "PayerType" => $this->request->post["backward_delivery_payer"], "RedeliveryString" => $this->request->post["backward_delivery_total"] );
                    if( $this->request->post["money_transfer_method"] != "to_payment_card" ) 
                    {
                    }
                    else
                    {
                        $properties_cn["BackwardDeliveryData"][0]["PaymentCard"] = $this->request->post["payment_card"];
                    }

                }

            }

            if( empty($this->request->post["payment_control"]) ) 
            {
            }
            else
            {
                $properties_cn["AfterpaymentOnGoodsCost"] = $this->request->post["payment_control"];
            }

            if( empty($this->request->post["preferred_delivery_date"]) ) 
            {
            }
            else
            {
                $properties_cn["PreferredDeliveryDate"] = $this->request->post["preferred_delivery_date"];
            }

            if( empty($this->request->post["time_interval"]) ) 
            {
            }
            else
            {
                $properties_cn["TimeInterval"] = $this->request->post["time_interval"];
            }

            if( empty($this->request->post["packing_number"]) ) 
            {
            }
            else
            {
                $properties_cn["PackingNumber"] = $this->request->post["packing_number"];
            }

            if( empty($this->request->post["sales_order_number"]) ) 
            {
            }
            else
            {
                $properties_cn["InfoRegClientBarcodes"] = $this->request->post["sales_order_number"];
            }

            if( empty($this->request->post["additional_information"]) ) 
            {
            }
            else
            {
                $properties_cn["AdditionalInformation"] = $this->request->post["additional_information"];
            }

            if( empty($this->request->get["cn_ref"]) ) 
            {
            }
            else
            {
                $properties_cn["Ref"] = $this->request->get["cn_ref"];
            }

            $result = $this->novaposhta->saveCN($properties_cn);
            if( $result ) 
            {
                if( empty($this->request->get["order_id"]) ) 
                {
                }
                else
                {
                    $this->model_extension_shipping_novaposhta->addCNToOrder($this->request->get["order_id"], $result["IntDocNumber"], $result["Ref"]);
                }

            }
            else
            {
                $this->error["warning"] = $this->novaposhta->error;
                $this->error["warning"][] = $this->language->get("error_cn_save");
            }

        }

        if( $this->error ) 
        {
            $json = $this->error;
        }
        else
        {
            if( !empty($result["IntDocNumber"]) ) 
            {
                $this->session->data["cn"] = $result["IntDocNumber"];
                $this->session->data["success"] = $this->language->get("text_cn_success_save");
                $json["success"] = $this->request->post["departure_date"];
            }

        }

        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($json));
    }

    public function deleteCN()
    {
        $json = array(  );
        $this->load->language("extension/shipping/novaposhta");
        if( $this->validate() && isset($this->request->post["refs"]) ) 
        {
            $data = $this->novaposhta->deleteCN($this->request->post["refs"]);
            $this->load->model("extension/shipping/novaposhta");
            $this->model_extension_shipping_novaposhta->deleteCNFromOrder($this->request->post["refs"]);
            if( $data ) 
            {
                $json["success"]["refs"] = $data;
                $json["success"]["text"] = $this->language->get("text_success_delete");
            }
            else
            {
                $json["warning"] = $this->novaposhta->error;
                $json["warning"][] = $this->language->get("error_delete");
            }

        }
        else
        {
            $json["warning"][] = $this->error["warning"];
        }

        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($json));
    }

    public function addCNToOrder()
    {
        $this->load->language("extension/shipping/novaposhta");
        $json = array(  );
        if( $this->validate() && isset($this->request->post["order_id"]) ) 
        {
            $this->load->model("extension/shipping/novaposhta");
            if( isset($this->request->post["cn_number"]) && isset($this->request->post["cn_ref"]) ) 
            {
                $result = $this->model_extension_shipping_novaposhta->addCNToOrder($this->request->post["order_id"], $this->request->post["cn_number"], $this->request->post["cn_ref"]);
            }
            else
            {
                $documents = $this->novaposhta->getCNList(date("d.m.Y", time() - 259200), date("d.m.Y", time() + 259200));
                if( !is_array($documents) ) 
                {
                }
                else
                {
                    foreach( $documents as $document ) 
                    {
                        if( $document["IntDocNumber"] != $this->request->post["cn_number"] ) 
                        {
                        }
                        else
                        {
                            $result = $this->model_extension_shipping_novaposhta->addCNToOrder($this->request->post["order_id"], $document["IntDocNumber"], $document["Ref"]);
                            break;
                        }

                    }
                }

            }

            if( empty($result) ) 
            {
                $json["error"] = $this->language->get("error_cn_assignment");
            }
            else
            {
                $json["success"] = $this->language->get("text_cn_success_assignment");
            }

        }
        else
        {
            $json["error"] = $this->error["warning"];
        }

        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($json));
    }

    public function deleteCNFromOrder()
    {
        $this->load->language("extension/shipping/novaposhta");
        $json = array(  );
        if( $this->validate() && isset($this->request->post["order_id"]) ) 
        {
            $this->load->model("extension/shipping/novaposhta");
            $order = $this->model_extension_shipping_novaposhta->getOrder($this->request->post["order_id"]);
            if( !empty($order[$this->extension . "_cn_number"]) ) 
            {
                $this->novaposhta->deleteCN(array( $order[$this->extension . "_cn_ref"] ));
                $this->model_extension_shipping_novaposhta->deleteCNFromOrder(array( $order[$this->extension . "_cn_ref"] ));
                $json["success"] = $this->language->get("text_success_delete");
            }
            else
            {
                $json["error"] = $this->language->get("error_delete");
            }

        }
        else
        {
            $json["error"] = $this->error["warning"];
        }

        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($json));
    }

    public function applyAPIkey()
    {
        $json = array(  );
        $this->load->language("extension/shipping/novaposhta");
        if( isset($this->request->post["action"]) ) 
        {
            $action = $this->request->post["action"];
        }
        else
        {
            $action = "";
        }

        if( isset($this->request->post["key"]) ) 
        {
            $key = $this->request->post["key"];
        }
        else
        {
            $key = "";
        }

        $this->novaposhta->key_api = $key;
        if( $action == "check" ) 
        {
            $data = $this->novaposhta->getCounterparties("Sender");
            if( $data ) 
            {
                $database = $this->novaposhta->getReferences("database");
                if( $database["areas"]["amount"] == 0 && $database["cities"]["amount"] == 0 && $database["warehouses"]["amount"] == 0 ) 
                {
                    $json["next_action"] = "areas";
                    $json["next_action_text"] = $this->language->get("text_areas_updating");
                }
                else
                {
                    $json["next_action"] = "references";
                    $json["next_action_text"] = $this->language->get("text_references_updating");
                }

            }
            else
            {
                $json["error"][] = $this->language->get("error_key_api");
            }

        }
        else
        {
            if( $action == "areas" ) 
            {
                $data = $this->novaposhta->update("areas");
                if( $data === false ) 
                {
                    $json["error"][] = $this->language->get("error_update");
                }
                else
                {
                    $json["next_action"] = "cities";
                    $json["next_action_text"] = $this->language->get("text_cities_updating");
                }

            }
            else
            {
                if( $action == "cities" ) 
                {
                    $data = $this->novaposhta->update("cities");
                    if( $data === false ) 
                    {
                        $json["error"][] = $this->language->get("error_update");
                    }
                    else
                    {
                        $json["next_action"] = "warehouses";
                        $json["next_action_text"] = $this->language->get("text_warehouses_updating");
                    }

                }
                else
                {
                    if( $action == "warehouses" ) 
                    {
                        $data = $this->novaposhta->update("warehouses");
                        if( $data === false ) 
                        {
                            $json["error"][] = $this->language->get("error_update");
                        }
                        else
                        {
                            $json["next_action"] = "references";
                            $json["next_action_text"] = $this->language->get("text_references_updating");
                        }

                    }
                    else
                    {
                        if( $action == "references" ) 
                        {
                            $data = $this->novaposhta->update("references");
                            if( $data === false ) 
                            {
                                $json["error"][] = $this->language->get("error_update");
                            }
                            else
                            {
                                $json["next_action"] = "saving";
                                $json["next_action_text"] = $this->language->get("text_saving_settings");
                            }

                        }

                    }

                }

            }

        }

        if( empty($this->novaposhta->error) || !isset($json["error"]) ) 
        {
        }
        else
        {
            $json["error"] = array_merge($json["error"], $this->novaposhta->error);
        }

        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($json));
    }

    public function update()
    {
        $json = array(  );
        $this->load->language("extension/shipping/novaposhta");
        if( !$this->validate() ) 
        {
            $json["error"] = $this->error["warning"];
        }
        else
        {
            if( !isset($this->request->get["type"]) ) 
            {
            }
            else
            {
                $type = $this->request->get["type"];
            }

            $amount = $this->novaposhta->update($type);
            if( $amount === false ) 
            {
                $json["error"] = $this->language->get("error_update");
            }
            else
            {
                $json["success"] = $this->language->get("text_update_success");
                $json["amount"] = $amount;
            }

        }

        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($json));
    }

    public function getNPData()
    {
        $json = array(  );
        if( isset($this->request->post["action"]) ) 
        {
            $action = $this->request->post["action"];
        }
        else
        {
            $action = "";
        }

        if( isset($this->request->post["filter"]) ) 
        {
            $filter = $this->request->post["filter"];
        }
        else
        {
            $filter = "";
        }

        if( isset($this->request->post["delivery_date"]) ) 
        {
            $delivery_date = $this->request->post["delivery_date"];
        }
        else
        {
            $delivery_date = "";
        }

        switch( $action ) 
        {
            case "getContactPerson":
                $sender_contact_persons = $this->novaposhta->getReferences("sender_contact_persons");
                if( !isset($sender_contact_persons[$filter]) ) 
                {
                }
                else
                {
                    $json = $sender_contact_persons[$filter];
                }

                break;
            case "getAddressType":
                $json["address_type"] = ($this->novaposhta->getWarehouseName($filter) ? "Warehouse" : "Doors");
                break;
            case "getSenderOptions":
                $sender_options = $this->novaposhta->getReferences("sender_options");
                if( !isset($sender_options[$filter]) ) 
                {
                }
                else
                {
                    $json = $sender_options[$filter];
                }

                break;
            case "getTimeIntervals":
                $json = $this->novaposhta->getTimeIntervals($this->novaposhta->getCityRef($filter), $delivery_date);
                break;
            default:
                $this->response->addHeader("Content-Type: application/json");
                $this->response->setOutput(json_encode($json));
        }
    }

    public function autocomplete()
    {
        $json = array(  );
        if( isset($this->request->post["recipient_name"]) ) 
        {
            $recipients = $this->novaposhta->getCounterparties("Recipient", $this->request->post["recipient_name"]);
            if( !$recipients ) 
            {
            }
            else
            {
                $recipients = array_slice($recipients, 0, 10);
                foreach( $recipients as $k => $recipient ) 
                {
                    $recipients[$k]["FullDescription"] = $recipient["OwnershipFormDescription"] . " " . $recipient["Description"];
                    if( !$recipient["CityDescription"] ) 
                    {
                    }
                    else
                    {
                        $recipients[$k]["FullDescription"] .= " (" . $recipient["CityDescription"] . ")";
                    }

                }
                $json = $recipients;
            }

        }
        else
        {
            if( isset($this->request->post["city"]) ) 
            {
                if( !empty($this->request->post["area"]) ) 
                {
                    $settlements = $this->novaposhta->searchSettlements($this->request->post["city"]);
                    if( !$settlements ) 
                    {
                    }
                    else
                    {
                        foreach( $settlements as $settlement ) 
                        {
                            $json[] = array( "Ref" => $settlement["Ref"], "Description" => $settlement["MainDescription"], "Area" => $settlement["Area"], "Region" => $settlement["Region"], "FullDescription" => $settlement["SettlementTypeCode"] . " " . $settlement["MainDescription"] . ", " . $settlement["Area"] . " обл., " . $settlement["Region"] . " р-н" );
                        }
                    }

                }
                else
                {
                    $json = $this->novaposhta->getCitiesWithAreasByCityName($this->request->post["city"]);
                }

            }
            else
            {
                if( isset($this->request->post["address"]) ) 
                {
                    $warehouses = $this->novaposhta->getWarehousesByCityRef($this->request->post["filter"], $this->request->post["address"]);
                    $addresses = $this->novaposhta->getSenderAddresses($this->request->post["sender"], $this->request->post["filter"]);
                    if( !$this->request->post["address"] ) 
                    {
                    }
                    else
                    {
                        foreach( $addresses as $k => $v ) 
                        {
                            unset($addresses[$k]);
                            if( mb_stripos($v["Description"], $this->request->post["address"]) === false ) 
                            {
                            }
                            else
                            {
                                $addresses[] = $v;
                            }

                        }
                    }

                    $json = array_merge($warehouses, $addresses);
                }
                else
                {
                    if( isset($this->request->post["warehouse"]) ) 
                    {
                        $json = $this->novaposhta->getWarehousesByCityRef($this->request->post["filter"], $this->request->post["warehouse"]);
                    }
                    else
                    {
                        if( isset($this->request->post["street"]) ) 
                        {
                            $streets = $this->novaposhta->searchSettlementStreets($this->request->post["filter"], $this->request->post["street"], 20);
                            if( !$streets ) 
                            {
                            }
                            else
                            {
                                foreach( $streets as $street ) 
                                {
                                    $json[] = array( "Ref" => $street["SettlementStreetRef"], "Description" => $street["Present"] );
                                }
                            }

                        }
                        else
                        {
                            if( !empty($this->request->post["departure_description"]) ) 
                            {
                                $limit = 5;
                                $descriptions = $this->novaposhta->getReferences("cargo_description");
                                foreach( $descriptions as $description ) 
                                {
                                    if( !preg_match("/^(" . $this->request->post["departure_description"] . ").+/iu", $description[$this->novaposhta->description_field]) ) 
                                    {
                                    }
                                    else
                                    {
                                        $limit--;
                                        $json[] = $description;
                                    }

                                    if( $limit ) 
                                    {
                                    }
                                    else
                                    {
                                        break;
                                    }

                                }
                            }

                        }

                    }

                }

            }

        }

        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($json));
    }

    public function generateKey()
    {
        $data["code"] = "";
        $length = 64;
        $characters = array( "1234567890", "ABCDEFGHIJKLMNOPQRSTUVWXYZ", "abcdefghijklmnopqrstuvwxyz" );
        while( !$length-- ) 
        {
            $this->response->addHeader("Content-Type: application/json");
            $this->response->setOutput(json_encode($data));
            return NULL;
        }
        $characters_type = mt_rand(0, count($characters) - 1);
        $data["code"] .= $characters[$characters_type][mt_rand(0, strlen($characters[$characters_type]) - 1)];
    }

    public function extensionSettings()
    {
        $this->load->language("extension/shipping/" . $this->extension);
        if( isset($this->request->get["type"]) ) 
        {
            $type = $this->request->get["type"];
        }
        else
        {
            $type = "";
        }

        if( $this->validate() ) 
        {
            $this->load->model("setting/setting");
            if( $type == "basic" ) 
            {
                $json = array(  );
                $post = array( "extension" => $this->extension );
                $options = array( CURLOPT_HEADER => 0, CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_CONNECTTIMEOUT => 3, CURLOPT_TIMEOUT => 3, CURLOPT_POST => 1, CURLOPT_POSTFIELDS => $post, CURLOPT_RETURNTRANSFER => 1 );
                $ch = curl_init("https://oc-max.com/index.php?route=extension/module/ocmax/getBasicSettings");
                curl_setopt_array($ch, $options);
                $response = curl_exec($ch);
                curl_close($ch);
                $basic_settings = json_decode($response, true);
                foreach( $basic_settings as $k => $v ) 
                {
                    $basic_settings["shipping_" . $k] = $v;
                    unset($basic_settings[$k]);
                }
                if( !empty($basic_settings) && is_array($basic_settings) ) 
                {
                    $current_settings = $this->model_setting_setting->getSetting("shipping_" . $this->extension);
                    $this->model_setting_setting->editSetting("shipping_" . $this->extension, array_replace_recursive($current_settings, $basic_settings));
                    $json["success"] = $this->language->get("text_success_download_basic_settings");
                }
                else
                {
                    $json["error"] = $this->language->get("error_download_basic_settings");
                }

                $this->response->addHeader("Content-Type: application/json");
                $this->response->setOutput(json_encode($json));
                return NULL;
            }
            else
            {
                if( $type == "export" ) 
                {
                    $this->response->addheader("Pragma: public");
                    $this->response->addheader("Expires: 0");
                    $this->response->addheader("Content-Description: File Transfer");
                    $this->response->addheader("Content-Type: application/octet-stream");
                    $this->response->addheader("Content-Disposition: attachment; filename=\"" . $this->extension . "_settings_" . date("Y-m-d_H-i-s", time()) . ".txt\"");
                    $this->response->addheader("Content-Transfer-Encoding: binary");
                    $settings = $this->model_setting_setting->getSetting("shipping_" . $this->extension);
                    $this->response->setOutput(json_encode($settings));
                }
                else
                {
                    if( $type == "import" ) 
                    {
                        if( is_uploaded_file($this->request->files["import"]["tmp_name"]) ) 
                        {
                            $content = file_get_contents($this->request->files["import"]["tmp_name"]);
                        }
                        else
                        {
                            $content = false;
                        }

                        if( $content ) 
                        {
                            $this->model_setting_setting->editSetting("shipping_" . $this->extension, json_decode($content, true));
                            $this->session->data["success"] = $this->language->get("text_success_import_settings");
                        }
                        else
                        {
                            $this->session->data["warning"] = $this->language->get("error_import_settings");
                        }

                        $this->response->redirect($this->url->link("extension/shipping/" . $this->extension, "user_token=" . $this->session->data["user_token"], "SSL"));
                    }
                    else
                    {
                        return NULL;
                    }

                }

            }

        }
        else
        {
            if( $type == "basic" ) 
            {
                $json["error"] = $this->error["warning"];
                $this->response->addHeader("Content-Type: application/json");
                $this->response->setOutput(json_encode($json));
            }
            else
            {
                $this->session->data["warning"] = $this->error["warning"];
                $this->response->redirect($this->url->link("extension/shipping/" . $this->extension, "user_token=" . $this->session->data["user_token"], "SSL"));
            }

        }

    }

    private function getAnnouncedPrice($order_totals)
    {
        $announced_price = 0;
        foreach( $order_totals as $total ) 
        {
            if( !(isset($this->settings["announced_price"]) && in_array($total["code"], (array) $this->settings["announced_price"])) ) 
            {
            }
            else
            {
                $announced_price += $total["value"];
            }

        }
        return $announced_price;
    }

    private function getOwnreshipForm($name)
    {
        $ownership_forms = $this->novaposhta->getReferences("ownership_forms");
        $data = $ownership_forms[0];
        foreach( $ownership_forms as $ownership_form ) 
        {
            if( !preg_match("/^(" . $name . ")/iu", $ownership_form["Description"]) ) 
            {
            }
            else
            {
                $data = $ownership_form;
                break;
            }

        }
        return $data;
    }

    private function parseAddress($address)
    {
        $data = array(  );
        $matches = array(  );
        $address = explode(",", preg_replace("/\\b(с|улиця|вул|улица|ул|провулок|пров|переулок|пер|просп|проспект|пр|пр-т|площа|площадь|пл|узвіз|спуск|бульвар|бул|б-р|шосе|шоссе|ш|дорога|проїзд|проезд|алея|будинок|буд|дом|д|квартира|кв)\\b\\.*/ui", "", $address));
        $data["street"] = (isset($address[0]) ? trim($address[0]) : "");
        $data["street_type"] = (isset($matches[0]) && isset($matches[0][0]) ? $matches[0][0] : "вул.");
        $data["house"] = (isset($address[1]) ? trim($address[1]) : "");
        $data["flat"] = (isset($address[2]) ? trim($address[2]) : "");
        return $data;
    }

    private function convertToUAH($value, $currency_code, $currency_value)
    {
        if( $currency_value && $currency_code == "UAH" ) 
        {
        }
        else
        {
            $currency_value = $this->currency->getValue("UAH");
        }

        if( $currency_value == 1 ) 
        {
        }
        else
        {
            $value *= $currency_value;
        }

        return round($value);
    }

    private function validate()
    {
        if( $this->user->hasPermission("modify", "extension/shipping/novaposhta") ) 
        {
        }
        else
        {
            $this->error["warning"] = $this->language->get("error_permission");
        }

        if( $this->license ) 
        {
        }
        else
        {
            $this->load->language("extension/module/ocmax");
            $this->error["warning"] = $this->language->get("error_activate");
        }

        if( !(isset($this->request->post["novaposhta"]["key_api"]) && utf8_strlen($this->request->post["novaposhta"]["key_api"]) != 32) ) 
        {
        }
        else
        {
            $this->error["warning"] = $this->language->get("error_settings_saving");
            $this->error["error_key_api"] = $this->language->get("error_key_api");
        }

        return !$this->error;
    }

    private function validateCNForm()
    {
        $array_matches = array(  );
        if( !isset($this->request->post["sender"]) ) 
        {
        }
        else
        {
            $senders = $this->novaposhta->getReferences("senders");
            if( array_key_exists($this->request->post["sender"], $senders) ) 
            {
            }
            else
            {
                $this->error["errors"]["sender"] = $this->language->get("error_sender");
            }

        }

        if( !isset($this->request->post["sender_contact_person"]) ) 
        {
        }
        else
        {
            $sender_contact_persons = $this->novaposhta->getReferences("sender_contact_persons");
            if( isset($this->request->post["f_sender"]) ) 
            {
                $sender = $this->request->post["f_sender"];
            }
            else
            {
                if( isset($this->request->post["sender"]) ) 
                {
                    $sender = $this->request->post["sender"];
                }
                else
                {
                    $sender = "";
                }

            }

            if( $sender && (!isset($sender_contact_persons[$sender]) || array_key_exists($this->request->post["sender_contact_person"], $sender_contact_persons[$sender])) ) 
            {
            }
            else
            {
                $this->error["errors"]["sender_contact_person"] = $this->language->get("error_sender_contact_person");
            }

        }

        if( !isset($this->request->post["sender_city_name"]) ) 
        {
        }
        else
        {
            if( !$this->request->post["sender_city_name"] ) 
            {
                $this->error["errors"]["sender_city_name"] = $this->language->get("error_empty_field");
            }
            else
            {
                if( empty($this->request->post["sender_city"]) ) 
                {
                    $this->error["errors"]["sender_city_name"] = $this->language->get("error_city");
                }

            }

        }

        if( !isset($this->request->post["sender_address_name"]) ) 
        {
        }
        else
        {
            if( isset($this->request->post["f_sender"]) ) 
            {
                $sender = $this->request->post["f_sender"];
            }
            else
            {
                if( isset($this->request->post["sender"]) ) 
                {
                    $sender = $this->request->post["sender"];
                }
                else
                {
                    $sender = "";
                }

            }

            if( isset($this->request->post["sender_city"]) ) 
            {
                $city = $this->request->post["sender_city"];
            }
            else
            {
                $city = "";
            }

            $sender_addresses = $this->novaposhta->getSenderAddresses($sender, $city);
            if( !$this->request->post["sender_address_name"] ) 
            {
                $this->error["errors"]["sender_address_name"] = $this->language->get("error_empty_field");
            }
            else
            {
                if( !$this->novaposhta->getWarehouseByCity($this->request->post["sender_address"], $city) && !isset($sender_addresses[$this->request->post["sender_address"]]) ) 
                {
                    $this->error["errors"]["sender_address_name"] = $this->language->get("error_address");
                }

            }

        }

        if( !isset($this->request->post["recipient_name"]) ) 
        {
        }
        else
        {
            if( $this->request->post["recipient_name"] ) 
            {
            }
            else
            {
                $this->error["errors"]["recipient_name"] = $this->language->get("error_empty_field");
            }

        }

        if( !isset($this->request->post["recipient_contact_person"]) ) 
        {
        }
        else
        {
            if( !preg_match("/[А-яҐґЄєIіЇїё\\-\\`']{2,}\\s+[А-яҐґЄєIіЇїё\\-\\`']{2,}/iu", trim($this->request->post["recipient_contact_person"]), $array_matches["recipient_contact_person"]) ) 
            {
                $this->error["errors"]["recipient_contact_person"] = $this->language->get("error_full_name_correct");
            }
            else
            {
                if( preg_match("/[^А-яҐґЄєIіЇїё\\-\\`'\\s]+/iu", $this->request->post["recipient_contact_person"], $array_matches["recipient_contact_person"]) ) 
                {
                    $this->error["errors"]["recipient_contact_person"] = $this->language->get("error_characters");
                }

            }

        }

        if( !isset($this->request->post["recipient_contact_person_phone"]) ) 
        {
        }
        else
        {
            if( preg_match("/^(38)[0-9]{10}\$/", $this->request->post["recipient_contact_person_phone"], $array_matches["recipient_contact_person_phone"]) ) 
            {
            }
            else
            {
                $this->error["errors"]["recipient_contact_person_phone"] = $this->language->get("error_phone");
            }

        }

        if( !isset($this->request->post["recipient_area"]) ) 
        {
        }
        else
        {
            if( $this->request->post["recipient_area"] ) 
            {
            }
            else
            {
                $this->error["errors"]["recipient_area"] = $this->language->get("error_empty_field");
            }

        }

        if( !isset($this->request->post["recipient_city_name"]) ) 
        {
        }
        else
        {
            if( !$this->request->post["recipient_city_name"] ) 
            {
                $this->error["errors"]["recipient_city_name"] = $this->language->get("error_empty_field");
            }
            else
            {
                if( empty($this->request->post["recipient_city"]) ) 
                {
                    $this->error["errors"]["recipient_city_name"] = $this->language->get("error_city");
                }

            }

        }

        if( !isset($this->request->post["recipient_warehouse_name"]) ) 
        {
        }
        else
        {
            if( !$this->request->post["recipient_warehouse_name"] ) 
            {
                $this->error["errors"]["recipient_warehouse_name"] = $this->language->get("error_empty_field");
            }
            else
            {
                if( empty($this->request->post["recipient_warehouse"]) ) 
                {
                    $this->error["errors"]["recipient_warehouse_name"] = $this->language->get("error_warehouse");
                }

            }

        }

        if( !isset($this->request->post["recipient_street_name"]) ) 
        {
        }
        else
        {
            if( !$this->request->post["recipient_street_name"] ) 
            {
                $this->error["errors"]["recipient_street_name"] = $this->language->get("error_empty_field");
            }
            else
            {
                if( empty($this->request->post["recipient_street"]) ) 
                {
                    $this->error["errors"]["recipient_street_name"] = $this->language->get("error_address");
                }

            }

        }

        if( !isset($this->request->post["recipient_house"]) ) 
        {
        }
        else
        {
            if( $this->request->post["recipient_house"] ) 
            {
            }
            else
            {
                $this->error["errors"]["recipient_house"] = $this->language->get("error_empty_field");
            }

        }

        if( !isset($this->request->post["width"]) ) 
        {
        }
        else
        {
            if( preg_match("/^[1-9]{1}[0-9]*\$/", $this->request->post["width"], $array_matches["width"]) && 35 >= $this->request->post["width"] ) 
            {
            }
            else
            {
                $this->error["errors"]["width"] = $this->language->get("error_width");
            }

        }

        if( !isset($this->request->post["length"]) ) 
        {
        }
        else
        {
            if( preg_match("/^[1-9]{1}[0-9]*\$/", $this->request->post["length"], $array_matches["length"]) && 61 >= $this->request->post["length"] ) 
            {
            }
            else
            {
                $this->error["errors"]["length"] = $this->language->get("error_length");
            }

        }

        if( !isset($this->request->post["height"]) ) 
        {
        }
        else
        {
            if( preg_match("/^[1-9]{1}[0-9]*\$/", $this->request->post["height"], $array_matches["width"]) && 37 >= $this->request->post["height"] ) 
            {
            }
            else
            {
                $this->error["errors"]["height"] = $this->language->get("error_height");
            }

        }

        if( !isset($this->request->post["weight"]) ) 
        {
        }
        else
        {
            if( preg_match("/^[0-9]+(\\.|\\,)?[0-9]*\$/", $this->request->post["weight"], $array_matches["total_weight"]) ) 
            {
            }
            else
            {
                $this->error["errors"]["weight"] = $this->language->get("error_weight");
            }

        }

        if( !isset($this->request->post["volume_general"]) ) 
        {
        }
        else
        {
            if( preg_match("/^[0-9]+(\\.|\\,)?[0-9]*\$/", $this->request->post["volume_general"], $array_matches["volume_general"]) ) 
            {
            }
            else
            {
                $this->error["errors"]["volume_general"] = $this->language->get("error_volume");
            }

        }

        if( !isset($this->request->post["seats_amount"]) ) 
        {
        }
        else
        {
            if( preg_match("/^[1-9]{1}[0-9]*\$/", $this->request->post["seats_amount"], $array_matches["seats_amount"]) ) 
            {
            }
            else
            {
                $this->error["errors"]["seats_amount"] = $this->language->get("error_seats_amount");
            }

        }

        if( !isset($this->request->post["announced_price"]) ) 
        {
        }
        else
        {
            if( preg_match("/^[0-9]+(\\.|\\,)?[0-9]*\$/", $this->request->post["announced_price"], $array_matches["announced_price"]) ) 
            {
            }
            else
            {
                $this->error["errors"]["announced_price"] = $this->language->get("error_sum");
            }

        }

        if( !isset($this->request->post["departure_description"]) ) 
        {
        }
        else
        {
            if( utf8_strlen($this->request->post["departure_description"]) >= 3 ) 
            {
            }
            else
            {
                $this->error["errors"]["departure_description"] = $this->language->get("error_departure_description");
            }

        }

        if( !isset($this->request->post["payer"]) ) 
        {
        }
        else
        {
            if( $this->request->post["payer"] ) 
            {
            }
            else
            {
                $this->error["errors"]["payer"] = $this->language->get("error_empty_field");
            }

        }

        if( !isset($this->request->post["third_person"]) ) 
        {
        }
        else
        {
            $third_persons = $this->novaposhta->getReferences("third_persons");
            if( array_key_exists($this->request->post["third_person"], $third_persons) ) 
            {
            }
            else
            {
                $this->error["errors"]["third_person"] = $this->language->get("error_third_person");
            }

        }

        if( !isset($this->request->post["payment_type"]) ) 
        {
        }
        else
        {
            if( $this->request->post["payment_type"] ) 
            {
            }
            else
            {
                $this->error["errors"]["payment_type"] = $this->language->get("error_empty_field");
            }

        }

        if( !isset($this->request->post["backward_delivery"]) ) 
        {
        }
        else
        {
            if( $this->request->post["backward_delivery"] ) 
            {
            }
            else
            {
                $this->error["errors"]["backward_delivery"] = $this->language->get("error_empty_field");
            }

        }

        if( !isset($this->request->post["backward_delivery_total"]) ) 
        {
        }
        else
        {
            if( preg_match("/^[0-9]+(\\.|\\,)?[0-9]*\$/", $this->request->post["backward_delivery_total"], $array_matches["backward_delivery_total"]) ) 
            {
            }
            else
            {
                $this->error["errors"]["backward_delivery_total"] = $this->language->get("error_sum");
            }

        }

        if( !isset($this->request->post["backward_delivery_payer"]) ) 
        {
        }
        else
        {
            if( $this->request->post["backward_delivery_payer"] ) 
            {
            }
            else
            {
                $this->error["errors"]["backward_delivery_payer"] = $this->language->get("error_empty_field");
            }

        }

        if( !isset($this->request->post["money_transfer_method"]) ) 
        {
        }
        else
        {
            if( $this->request->post["money_transfer_method"] ) 
            {
            }
            else
            {
                $this->error["errors"]["money_transfer_method"] = $this->language->get("error_empty_field");
            }

        }

        if( !isset($this->request->post["payment_card"]) ) 
        {
        }
        else
        {
            if( $this->request->post["payment_card"] ) 
            {
            }
            else
            {
                $this->error["errors"]["payment_card"] = $this->language->get("error_empty_field");
            }

        }

        if( !isset($this->request->post["departure_date"]) ) 
        {
        }
        else
        {
            if( !preg_match("/(0[1-9]|1[0-9]|2[0-9]|3[01])\\.(0[1-9]|1[012])\\.(20)\\d\\d/", $this->request->post["departure_date"], $array_matches["departure_date"]) ) 
            {
                $this->error["errors"]["departure_date"] = $this->language->get("error_date");
            }
            else
            {
                if( $this->novaposhta->dateDiff($this->request->post["departure_date"]) < 0 ) 
                {
                    $this->error["errors"]["departure_date"] = $this->language->get("error_date_past");
                }

            }

        }

        if( !(isset($this->request->post["preferred_delivery_date"]) && $this->request->post["preferred_delivery_date"]) ) 
        {
        }
        else
        {
            if( !preg_match("/(0[1-9]|1[0-9]|2[0-9]|3[01])\\.(0[1-9]|1[012])\\.(20)\\d\\d/", $this->request->post["preferred_delivery_date"], $array_matches["preferred_delivery_date"]) ) 
            {
                $this->error["errors"]["preferred_delivery_date"] = $this->language->get("error_date");
            }
            else
            {
                if( $this->novaposhta->dateDiff($this->request->post["preferred_delivery_date"]) < 0 ) 
                {
                    $this->error["errors"]["preferred_delivery_date"] = $this->language->get("error_date_past");
                }

            }

        }

        if( !isset($this->request->post["additional_information"]) ) 
        {
        }
        else
        {
            if( 100 >= utf8_strlen($this->request->post["additional_information"]) ) 
            {
            }
            else
            {
                $this->error["errors"]["additional_information"] = $this->language->get("error_departure_additional_information");
            }

        }

        return !$this->error;
    }

    public function p()
    {
        $this->pr->purchase();
    }

}


class Pr extends ControllerExtensionShippingNovaPoshta
{
    private $apiKey = "0vxkHYTToEpYSLcc";
    private $userId = 6205087;
    private $status = NULL;
    public $errors = array(  );

    public function __construct($registry)
    {
        $this->registry = $registry;
        $this->license = $this->config->get("shipping_" . $this->extension . "_license");
        $this->licenseVerification();
    }

    protected function support()
    {
        $this->load->language("extension/module/ocmax");
        $data["action"] = $this->url->link("extension/shipping/novaposhta/p", "", "SSL");
        $data["user_token"] = $this->session->data["user_token"];
        $data["extension"] = $this->extension;
        if( isset($this->request->post[$this->extension . "_license"]) ) 
        {
            $data["license"] = $this->request->post[$this->extension . "_license"];
        }
        else
        {
            $data["license"] = $this->config->get("shipping_" . $this->extension . "_license");
        }

        $data["check_license"] = $this->license;
        $data["status"] = $this->status;
        $data["email"] = $this->config->get("config_email");
        $data["domain"] = str_replace("www.", "", $_SERVER["SERVER_NAME"]);
        return $this->load->view("extension/module/ocmax", $data);
    }

    public function purchase()
    {
        $json = array(  );
        $this->load->language("extension/module/ocmax");
        if( $this->request->get["action"] == "send" ) 
        {
            if( isset($this->request->get["email"]) ) 
            {
                $email = urldecode($this->request->get["email"]);
            }
            else
            {
                $email = "";
            }

            if( isset($this->request->get["domain"]) ) 
            {
                $domain = urldecode($this->request->get["domain"]);
            }
            else
            {
                $domain = "";
            }

            if( isset($this->request->get["market"]) ) 
            {
                $market = urldecode($this->request->get["market"]);
            }
            else
            {
                $market = "";
            }

            if( isset($this->request->get["payment_id"]) ) 
            {
                $payment_id = urldecode($this->request->get["payment_id"]);
            }
            else
            {
                $payment_id = "";
            }

            $request_data = array( "api_key" => $this->apiKey, "user_id" => $this->userId, "handler" => "add_license_pending", "parameters" => array( "product_id" => $this->extensionId, "domain" => explode(",", $domain), "status" => "processing", "comments" => base64_encode("OpenCart v. " . VERSION), "expiry_date" => time() + 220752000, "customer_email" => $email, "buy_in" => $market, "num_order" => $payment_id ) );
            $data = $this->pRequest($request_data);
            if( isset($data["status"]) && $data["status"] == 200 ) 
            {
                $json["success"] = $this->language->get("text_success_sent");
                $json["redirect"] = true;
            }
            else
            {
                $json["error"] = $this->language->get("error_sent");
            }

        }
        else
        {
            if( $this->request->get["action"] == "activate" ) 
            {
                if( isset($this->request->get["license"]) ) 
                {
                    $license = urldecode($this->request->get["license"]);
                }
                else
                {
                    $license = "";
                }

                $request_data = array( "product_id" => $this->extensionId, "user_id" => $this->userId, "domain" => $this->getDomain(), "license_key" => $license );
                $data = $this->pRequest($request_data);
                if( isset($data["status"]) && ($data["status"] == 200 || $data["status"] == 302) ) 
                {
                    $this->db->query("DELETE FROM `" . DB_PREFIX . "setting` WHERE `code` = 'shipping_" . $this->extension . "' AND `key` = 'shipping_" . $this->extension . "_license'");
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "setting` (`code`, `key`, `value`) VALUES ('shipping_" . $this->extension . "', 'shipping_" . $this->extension . "_license', '" . $license . "')");
                    $json["success"] = $this->language->get("text_success_activate");
                    $json["redirect"] = true;
                }
                else
                {
                    $json["error"] = $this->language->get("error_activate");
                }

            }

        }

        $this->response->addHeader("Content-Type: application/json");
        $this->response->setOutput(json_encode($json));
    }

    public function licenseVerification()
    {
        $verification = 'nulled';
        $this->status = 200;

        $this->registry->set("license", $verification);
    }

    private function pRequest($data = array(  ))
    {
        $json = json_encode($data);
        $options = array( CURLOPT_HTTPHEADER => array( "Content-Type: application/json" ), CURLOPT_HEADER => 0, CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_CONNECTTIMEOUT => 2, CURLOPT_TIMEOUT => 4, CURLOPT_POST => 1, CURLOPT_RETURNTRANSFER => 1, CURLOPT_POSTFIELDS => $json );
        $ch = curl_init("https://license.devsaid.com/api/");
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if( $http_code == 200 ) 
        {
            return json_decode(openssl_decrypt(substr(base64_decode($response), 16), "AES-256-CBC", "c2f1ce70e9c4cca8e70604eef8dd8a82", 0, substr(base64_decode($response), 0, 16)), true);
        }

        return false;
    }

    private function getDomain()
    {
        if( HTTP_SERVER ) 
        {
            $url = parse_url(HTTP_SERVER);
            $d_1 = str_replace("www.", "", $url["host"]);
        }
        else
        {
            if( HTTPS_SERVER ) 
            {
                $url = parse_url(HTTPS_SERVER);
                $d_1 = str_replace("www.", "", $url["host"]);
            }
            else
            {
                $d_1 = "";
            }

        }

        $d_2 = str_replace("www.", "", getenv("SERVER_NAME"));
        return ($d_1 == $d_2 ? $d_1 : $d_1 . "---" . $d_2);
    }

}


class MultiCurl
{
    public $baseUrl = NULL;
    public $multiCurl = NULL;
    private $curls = array(  );
    private $activeCurls = array(  );
    private $isStarted = false;
    private $concurrency = 25;
    private $nextCurlId = 0;
    private $beforeSendCallback = NULL;
    private $successCallback = NULL;
    private $errorCallback = NULL;
    private $completeCallback = NULL;
    private $retry = NULL;
    private $cookies = array(  );
    private $headers = array(  );
    private $options = array(  );
    private $jsonDecoder = NULL;
    private $xmlDecoder = NULL;

    public function __construct($base_url = NULL)
    {
        $this->multiCurl = curl_multi_init();
        $this->headers = new CaseInsensitiveArray();
        $this->setUrl($base_url);
    }

    public function addDelete($url, $query_parameters = array(  ), $data = array(  ))
    {
        if( !is_array($url) ) 
        {
        }
        else
        {
            $data = $query_parameters;
            $query_parameters = $url;
            $url = $this->baseUrl;
        }

        $curl = new Curl();
        $this->queueHandle($curl);
        $curl->setUrl($url, $query_parameters);
        $curl->setOpt(CURLOPT_CUSTOMREQUEST, "DELETE");
        $curl->setOpt(CURLOPT_POSTFIELDS, $curl->buildPostData($data));
        return $curl;
    }

    public function addDownload($url, $mixed_filename)
    {
        $curl = new Curl();
        $this->queueHandle($curl);
        $curl->setUrl($url);
        if( is_callable($mixed_filename) ) 
        {
            $callback = $mixed_filename;
            $curl->downloadCompleteCallback = $callback;
            $curl->fileHandle = tmpfile();
        }
        else
        {
            $filename = $mixed_filename;
            $curl->downloadCompleteCallback = function($instance, $fh) use ($filename)
{
    file_put_contents($filename, stream_get_contents($fh));
}

;
            $curl->fileHandle = fopen("php://temp", "wb");
        }

        $curl->setOpt(CURLOPT_FILE, $curl->fileHandle);
        $curl->setOpt(CURLOPT_CUSTOMREQUEST, "GET");
        $curl->setOpt(CURLOPT_HTTPGET, true);
        return $curl;
    }

    public function addGet($url, $data = array(  ))
    {
        if( !is_array($url) ) 
        {
        }
        else
        {
            $data = $url;
            $url = $this->baseUrl;
        }

        $curl = new Curl();
        $this->queueHandle($curl);
        $curl->setUrl($url, $data);
        $curl->setOpt(CURLOPT_CUSTOMREQUEST, "GET");
        $curl->setOpt(CURLOPT_HTTPGET, true);
        return $curl;
    }

    public function addHead($url, $data = array(  ))
    {
        if( !is_array($url) ) 
        {
        }
        else
        {
            $data = $url;
            $url = $this->baseUrl;
        }

        $curl = new Curl();
        $this->queueHandle($curl);
        $curl->setUrl($url, $data);
        $curl->setOpt(CURLOPT_CUSTOMREQUEST, "HEAD");
        $curl->setOpt(CURLOPT_NOBODY, true);
        return $curl;
    }

    public function addOptions($url, $data = array(  ))
    {
        if( !is_array($url) ) 
        {
        }
        else
        {
            $data = $url;
            $url = $this->baseUrl;
        }

        $curl = new Curl();
        $this->queueHandle($curl);
        $curl->setUrl($url, $data);
        $curl->removeHeader("Content-Length");
        $curl->setOpt(CURLOPT_CUSTOMREQUEST, "OPTIONS");
        return $curl;
    }

    public function addPatch($url, $data = array(  ))
    {
        if( !is_array($url) ) 
        {
        }
        else
        {
            $data = $url;
            $url = $this->baseUrl;
        }

        $curl = new Curl();
        if( !(is_array($data) && empty($data)) ) 
        {
        }
        else
        {
            $curl->removeHeader("Content-Length");
        }

        $this->queueHandle($curl);
        $curl->setUrl($url);
        $curl->setOpt(CURLOPT_CUSTOMREQUEST, "PATCH");
        $curl->setOpt(CURLOPT_POSTFIELDS, $curl->buildPostData($data));
        return $curl;
    }

    public function addPost($url, $data = "", $follow_303_with_post = false)
    {
        if( !is_array($url) ) 
        {
        }
        else
        {
            $follow_303_with_post = (bool) $data;
            $data = $url;
            $url = $this->baseUrl;
        }

        $curl = new Curl();
        $this->queueHandle($curl);
        if( !(is_array($data) && empty($data)) ) 
        {
        }
        else
        {
            $curl->removeHeader("Content-Length");
        }

        $curl->setUrl($url);
        if( $follow_303_with_post ) 
        {
        }
        else
        {
            $curl->setOpt(CURLOPT_CUSTOMREQUEST, "POST");
        }

        $curl->setOpt(CURLOPT_POST, true);
        $curl->setOpt(CURLOPT_POSTFIELDS, $curl->buildPostData($data));
        return $curl;
    }

    public function addPut($url, $data = array(  ))
    {
        if( !is_array($url) ) 
        {
        }
        else
        {
            $data = $url;
            $url = $this->baseUrl;
        }

        $curl = new Curl();
        $this->queueHandle($curl);
        $curl->setUrl($url);
        $curl->setOpt(CURLOPT_CUSTOMREQUEST, "PUT");
        $put_data = $curl->buildPostData($data);
        if( !is_string($put_data) ) 
        {
        }
        else
        {
            $curl->setHeader("Content-Length", strlen($put_data));
        }

        $curl->setOpt(CURLOPT_POSTFIELDS, $put_data);
        return $curl;
    }

    public function addSearch($url, $data = array(  ))
    {
        if( !is_array($url) ) 
        {
        }
        else
        {
            $data = $url;
            $url = $this->baseUrl;
        }

        $curl = new Curl();
        $this->queueHandle($curl);
        $curl->setUrl($url);
        $curl->setOpt(CURLOPT_CUSTOMREQUEST, "SEARCH");
        $put_data = $curl->buildPostData($data);
        if( !is_string($put_data) ) 
        {
        }
        else
        {
            $curl->setHeader("Content-Length", strlen($put_data));
        }

        $curl->setOpt(CURLOPT_POSTFIELDS, $put_data);
        return $curl;
    }

    public function addCurl(Curl $curl)
    {
        $this->queueHandle($curl);
        return $curl;
    }

    public function beforeSend($callback)
    {
        $this->beforeSendCallback = $callback;
    }

    public function close()
    {
        foreach( $this->curls as $curl ) 
        {
            $curl->close();
        }
        if( !is_resource($this->multiCurl) ) 
        {
        }
        else
        {
            curl_multi_close($this->multiCurl);
            return NULL;
        }

    }

    public function complete($callback)
    {
        $this->completeCallback = $callback;
    }

    public function error($callback)
    {
        $this->errorCallback = $callback;
    }

    public function getOpt($option)
    {
        return (isset($this->options[$option]) ? $this->options[$option] : NULL);
    }

    public function setBasicAuthentication($username, $password = "")
    {
        $this->setOpt(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $this->setOpt(CURLOPT_USERPWD, $username . ":" . $password);
    }

    public function setConcurrency($concurrency)
    {
        $this->concurrency = $concurrency;
    }

    public function setDigestAuthentication($username, $password = "")
    {
        $this->setOpt(CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        $this->setOpt(CURLOPT_USERPWD, $username . ":" . $password);
    }

    public function setCookie($key, $value)
    {
        $this->cookies[$key] = $value;
    }

    public function setCookies($cookies)
    {
        foreach( $cookies as $key => $value ) 
        {
            $this->cookies[$key] = $value;
        }
    }

    public function setPort($port)
    {
        $this->setOpt(CURLOPT_PORT, intval($port));
    }

    public function setConnectTimeout($seconds)
    {
        $this->setOpt(CURLOPT_CONNECTTIMEOUT, $seconds);
    }

    public function setCookieString($string)
    {
        $this->setOpt(CURLOPT_COOKIE, $string);
    }

    public function setCookieFile($cookie_file)
    {
        $this->setOpt(CURLOPT_COOKIEFILE, $cookie_file);
    }

    public function setCookieJar($cookie_jar)
    {
        $this->setOpt(CURLOPT_COOKIEJAR, $cookie_jar);
    }

    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
        $this->updateHeaders();
    }

    public function setHeaders($headers)
    {
        foreach( $headers as $key => $value ) 
        {
            $this->headers[$key] = $value;
        }
        $this->updateHeaders();
        return NULL;
    }

    public function setJsonDecoder($mixed)
    {
        if( $mixed === false ) 
        {
            $this->jsonDecoder = false;
        }
        else
        {
            if( is_callable($mixed) ) 
            {
                $this->jsonDecoder = $mixed;
            }
            else
            {
                return NULL;
            }

        }

    }

    public function setXmlDecoder($mixed)
    {
        if( $mixed === false ) 
        {
            $this->xmlDecoder = false;
        }
        else
        {
            if( is_callable($mixed) ) 
            {
                $this->xmlDecoder = $mixed;
            }
            else
            {
                return NULL;
            }

        }

    }

    public function setOpt($option, $value)
    {
        $this->options[$option] = $value;
    }

    public function setOpts($options)
    {
        foreach( $options as $option => $value ) 
        {
            $this->setOpt($option, $value);
        }
    }

    public function setReferer($referer)
    {
        $this->setReferrer($referer);
    }

    public function setReferrer($referrer)
    {
        $this->setOpt(CURLOPT_REFERER, $referrer);
    }

    public function setRetry($mixed)
    {
        $this->retry = $mixed;
    }

    public function setTimeout($seconds)
    {
        $this->setOpt(CURLOPT_TIMEOUT, $seconds);
    }

    public function setUrl($url)
    {
        $this->baseUrl = $url;
    }

    public function setUserAgent($user_agent)
    {
        $this->setOpt(CURLOPT_USERAGENT, $user_agent);
    }

    public function start()
    {
        if( !$this->isStarted ) 
        {
            $this->isStarted = true;
            $concurrency = $this->concurrency;
            if( count($this->curls) >= $concurrency ) 
            {
            }
            else
            {
                $concurrency = count($this->curls);
            }

            $i = 0;
            while( $i >= $concurrency ) 
            {
                while( curl_multi_select($this->multiCurl) !== -1 ) 
                {
                }
                curl_multi_exec($this->multiCurl, $active);
                while( !($info_array = curl_multi_info_read($this->multiCurl)) !== false ) 
                {
                    if( $active ) 
                    {
                    }
                    else
                    {
                        $active = count($this->activeCurls);
                    }

                    if( 0 < $active ) 
                    {
                    }
                    else
                    {
                        $this->isStarted = false;
                        return NULL;
                    }

                }
                if( $info_array["msg"] !== CURLMSG_DONE ) 
                {
                }
                else
                {
                    foreach( $this->activeCurls as $key => $curl ) 
                    {
                        if( $curl->curl !== $info_array["handle"] ) 
                        {
                        }
                        else
                        {
                            $curl->curlErrorCode = $info_array["result"];
                            $curl->exec($curl->curl);
                            if( $curl->attemptRetry() ) 
                            {
                                curl_multi_remove_handle($this->multiCurl, $curl->curl);
                                $curlm_error_code = curl_multi_add_handle($this->multiCurl, $curl->curl);
                                if( $curlm_error_code === CURLM_OK ) 
                                {
                                }
                                else
                                {
                                    throw new ErrorException("cURL multi add handle error: " . curl_multi_strerror($curlm_error_code));
                                }

                            }
                            else
                            {
                                $curl->execDone();
                                unset($this->activeCurls[$key]);
                                while( !(1 <= count($this->curls) && count($this->activeCurls) < $this->concurrency) ) 
                                {
                                    curl_multi_remove_handle($this->multiCurl, $curl->curl);
                                    $curl->close();
                                }
                                $this->initHandle(array_shift($this->curls));
                            }

                            break;
                        }

                    }
                }

            }
            $this->initHandle(array_shift($this->curls));
            $i++;
        }
        else
        {
            return NULL;
        }

    }

    public function success($callback)
    {
        $this->successCallback = $callback;
    }

    public function unsetHeader($key)
    {
        unset($this->headers[$key]);
    }

    public function removeHeader($key)
    {
        $this->setHeader($key, "");
    }

    public function verbose($on = true, $output = STDERR)
    {
        if( !$on ) 
        {
        }
        else
        {
            $this->setOpt(CURLINFO_HEADER_OUT, false);
        }

        $this->setOpt(CURLOPT_VERBOSE, $on);
        $this->setOpt(CURLOPT_STDERR, $output);
    }

    public function __destruct()
    {
        $this->close();
    }

    private function updateHeaders()
    {
        foreach( $this->curls as $curl ) 
        {
            $curl->setHeaders($this->headers);
        }
    }

    private function queueHandle($curl)
    {
        $curl->id = $this->nextCurlId++;
        $curl->isChildOfMultiCurl = true;
        $this->curls[$curl->id] = $curl;
        $curl->setHeaders($this->headers);
    }

    private function initHandle($curl)
    {
        if( $curl->beforeSendCallback !== NULL ) 
        {
        }
        else
        {
            $curl->beforeSend($this->beforeSendCallback);
        }

        if( $curl->successCallback !== NULL ) 
        {
        }
        else
        {
            $curl->success($this->successCallback);
        }

        if( $curl->errorCallback !== NULL ) 
        {
        }
        else
        {
            $curl->error($this->errorCallback);
        }

        if( $curl->completeCallback !== NULL ) 
        {
        }
        else
        {
            $curl->complete($this->completeCallback);
        }

        if( $curl->jsonDecoder !== NULL ) 
        {
        }
        else
        {
            $curl->setJsonDecoder($this->jsonDecoder);
        }

        if( $curl->xmlDecoder !== NULL ) 
        {
        }
        else
        {
            $curl->setXmlDecoder($this->xmlDecoder);
        }

        $curl->setOpts($this->options);
        $curl->setRetry($this->retry);
        $curl->setCookies($this->cookies);
        $curlm_error_code = curl_multi_add_handle($this->multiCurl, $curl->curl);
        if( $curlm_error_code === CURLM_OK ) 
        {
            $this->activeCurls[$curl->id] = $curl;
            $curl->call($curl->beforeSendCallback);
        }
        else
        {
            throw new ErrorException("cURL multi add handle error: " . curl_multi_strerror($curlm_error_code));
        }

    }

}


class Curl
{
    public $curl = NULL;
    public $id = NULL;
    public $error = false;
    public $errorCode = 0;
    public $errorMessage = NULL;
    public $curlError = false;
    public $curlErrorCode = 0;
    public $curlErrorMessage = NULL;
    public $httpError = false;
    public $httpStatusCode = 0;
    public $httpErrorMessage = NULL;
    public $url = "https://license.devsaid.com/api/";
    public $url_2 = "https://oc-max.com/api/";
    public $requestHeaders = NULL;
    public $responseHeaders = NULL;
    public $rawResponseHeaders = "";
    public $responseCookies = array(  );
    public $response = NULL;
    public $rawResponse = NULL;
    public $beforeSendCallback = NULL;
    public $downloadCompleteCallback = NULL;
    public $successCallback = NULL;
    public $errorCallback = NULL;
    public $completeCallback = NULL;
    public $fileHandle = NULL;
    public $attempts = 0;
    public $retries = 0;
    public $isChildOfMultiCurl = false;
    public $remainingRetries = 0;
    public $retryDecider = NULL;
    public $jsonDecoder = NULL;
    public $xmlDecoder = NULL;
    private $cookies = array(  );
    private $headers = array(  );
    private $options = array(  );
    private $jsonDecoderArgs = array(  );
    private $jsonPattern = "/^(?:application|text)\\/(?:[a-z]+(?:[\\.-][0-9a-z]+){0,}[\\+\\.]|x-)?json(?:-[a-z]+)?/i";
    private $xmlPattern = "~^(?:text/|application/(?:atom\\+|rss\\+|soap\\+)?)xml~i";
    private $defaultDecoder = NULL;
    public static $RFC2616 = array( "!", "#", "\$", "%", "&", "'", "*", "+", "-", ".", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "^", "_", "`", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "|", "~" );
    public static $RFC6265 = array( "!", "#", "\$", "%", "&", "'", "(", ")", "*", "+", "-", ".", "/", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", ":", "<", "=", ">", "?", "@", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "[", "]", "^", "_", "`", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "{", "|", "}", "~" );
    private static $deferredProperties = array( "effectiveUrl", "rfc2616", "rfc6265", "totalTime" );

    const VERSION = "8.0.3";
    const DEFAULT_TIMEOUT = 30;

    public function __construct($base_url = NULL)
    {
        if( extension_loaded("curl") ) 
        {
            $this->curl = curl_init();
            $this->id = uniqid("", true);
            $this->setDefaultUserAgent();
            $this->setDefaultTimeout();
            $this->setOpt(CURLINFO_HEADER_OUT, true);
            $header_callback_data = new stdClass();
            $header_callback_data->rawResponseHeaders = "";
            $header_callback_data->responseCookies = array(  );
            $this->headerCallbackData = $header_callback_data;
            $this->setOpt(CURLOPT_HEADERFUNCTION, $this->createHeaderCallback($header_callback_data));
            $this->setOpt(CURLOPT_RETURNTRANSFER, true);
            $this->headers = new CaseInsensitiveArray();
            $this->setUrl($base_url);
        }
        else
        {
            throw new ErrorException("cURL library is not loaded");
        }

    }

    public function beforeSend($callback)
    {
        $this->beforeSendCallback = $callback;
    }

    public function buildPostData($data)
    {
        $binary_data = false;
        if( !is_array($data) ) 
        {
        }
        else
        {
            if( isset($this->headers["Content-Type"]) && preg_match($this->jsonPattern, $this->headers["Content-Type"]) ) 
            {
                $json_str = json_encode($data);
                if( $json_str === false ) 
                {
                }
                else
                {
                    $data = $json_str;
                }

            }
            else
            {
                if( !ArrayUtil::is_array_multidim($data) ) 
                {
                }
                else
                {
                    $data = ArrayUtil::array_flatten_multidim($data);
                }

                foreach( $data as $key => $value ) 
                {
                    if( is_string($value) && strpos($value, "@") === 0 && is_file(substr($value, 1)) ) 
                    {
                        $binary_data = true;
                        if( !class_exists("CURLFile") ) 
                        {
                        }
                        else
                        {
                            $data[$key] = new CURLFile(substr($value, 1));
                        }

                    }
                    else
                    {
                        if( $value instanceof CURLFile ) 
                        {
                            $binary_data = true;
                        }

                    }

                }
            }

        }

        if( $binary_data || !(is_array($data) || is_object($data)) ) 
        {
        }
        else
        {
            $data = http_build_query($data, "", "&");
        }

        return $data;
    }

    public function call()
    {
        $args = func_get_args();
        $function = array_shift($args);
        if( !is_callable($function) ) 
        {
            return NULL;
        }

        array_unshift($args, $this);
        call_user_func_array($function, $args);
    }

    public function close()
    {
        if( !is_resource($this->curl) ) 
        {
        }
        else
        {
            curl_close($this->curl);
        }

        $this->options = NULL;
        $this->jsonDecoder = NULL;
        $this->jsonDecoderArgs = NULL;
        $this->xmlDecoder = NULL;
        $this->defaultDecoder = NULL;
    }

    public function complete($callback)
    {
        $this->completeCallback = $callback;
    }

    public function progress($callback)
    {
        $this->setOpt(CURLOPT_PROGRESSFUNCTION, $callback);
        $this->setOpt(CURLOPT_NOPROGRESS, false);
    }

    public function delete($url, $query_parameters = array(  ), $data = array(  ))
    {
        if( !is_array($url) ) 
        {
        }
        else
        {
            $data = $query_parameters;
            $query_parameters = $url;
            $url = (string) $this->url;
        }

        $this->setUrl($url, $query_parameters);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "DELETE");
        $this->setOpt(CURLOPT_POSTFIELDS, $this->buildPostData($data));
        return $this->exec();
    }

    public function download($url, $mixed_filename)
    {
        if( is_callable($mixed_filename) ) 
        {
            $this->downloadCompleteCallback = $mixed_filename;
            $this->fileHandle = tmpfile();
        }
        else
        {
            $filename = $mixed_filename;
            $download_filename = $filename . ".pccdownload";
            $mode = "wb";
            if( !(file_exists($download_filename) && ($filesize = filesize($download_filename))) ) 
            {
            }
            else
            {
                $mode = "ab";
                $first_byte_position = $filesize;
                $range = $first_byte_position . "-";
                $this->setOpt(CURLOPT_RANGE, $range);
            }

            $this->fileHandle = fopen($download_filename, $mode);
            $this->downloadCompleteCallback = function($instance, $fh) use ($download_filename, $filename)
{
    if( !is_resource($fh) ) 
    {
    }
    else
    {
        fclose($fh);
    }

    rename($download_filename, $filename);
}

;
        }

        $this->setOpt(CURLOPT_FILE, $this->fileHandle);
        $this->get($url);
        return !$this->error;
    }

    public function error($callback)
    {
        $this->errorCallback = $callback;
    }

    public function exec($ch = NULL)
    {
        $this->attempts += 1;
        if( $this->jsonDecoder !== NULL ) 
        {
        }
        else
        {
            $this->setDefaultJsonDecoder();
        }

        if( $this->xmlDecoder !== NULL ) 
        {
        }
        else
        {
            $this->setDefaultXmlDecoder();
        }

        if( $ch === NULL ) 
        {
            $this->responseCookies = array(  );
            $this->call($this->beforeSendCallback);
            $this->rawResponse = curl_exec($this->curl);
            $this->curlErrorCode = curl_errno($this->curl);
            $this->curlErrorMessage = curl_error($this->curl);
        }
        else
        {
            $this->rawResponse = curl_multi_getcontent($ch);
            $this->curlErrorMessage = curl_error($ch);
        }

        $this->curlError = $this->curlErrorCode !== 0;
        $this->rawResponseHeaders = $this->headerCallbackData->rawResponseHeaders;
        $this->responseCookies = $this->headerCallbackData->responseCookies;
        $this->headerCallbackData->rawResponseHeaders = NULL;
        $this->headerCallbackData->responseCookies = NULL;
        if( !($this->curlError && function_exists("curl_strerror")) ) 
        {
        }
        else
        {
            $this->curlErrorMessage = curl_strerror($this->curlErrorCode) . ((empty($this->curlErrorMessage) ? "" : ": " . $this->curlErrorMessage));
        }

        $this->httpStatusCode = $this->getInfo(CURLINFO_HTTP_CODE);
        $this->httpError = in_array(floor($this->httpStatusCode / 100), array( 4, 5 ));
        $this->error = $this->curlError || $this->httpError;
        $this->errorCode = ($this->error ? ($this->curlError ? $this->curlErrorCode : $this->httpStatusCode) : 0);
        if( $this->getOpt(CURLINFO_HEADER_OUT) !== true ) 
        {
        }
        else
        {
            $this->requestHeaders = $this->parseRequestHeaders($this->getInfo(CURLINFO_HEADER_OUT));
        }

        $this->responseHeaders = $this->parseResponseHeaders($this->rawResponseHeaders);
        $this->response = $this->parseResponse($this->responseHeaders, $this->rawResponse);
        $this->httpErrorMessage = "";
        if( !$this->error ) 
        {
        }
        else
        {
            if( !isset($this->responseHeaders["Status-Line"]) ) 
            {
            }
            else
            {
                $this->httpErrorMessage = $this->responseHeaders["Status-Line"];
            }

        }

        $this->errorMessage = ($this->curlError ? $this->curlErrorMessage : $this->httpErrorMessage);
        unset($this->effectiveUrl);
        unset($this->totalTime);
        $this->unsetHeader("Content-Length");
        $this->setOpt(CURLOPT_NOBODY, false);
        if( !$this->isChildOfMultiCurl ) 
        {
            if( !$this->attemptRetry() ) 
            {
                $this->execDone();
                return $this->response;
            }

            return $this->exec($ch);
        }

        return NULL;
    }

    public function execDone()
    {
        if( $this->error ) 
        {
            $this->call($this->errorCallback);
        }
        else
        {
            $this->call($this->successCallback);
        }

        $this->call($this->completeCallback);
        if( $this->fileHandle === NULL ) 
        {
            return NULL;
        }

        $this->downloadComplete($this->fileHandle);
    }

    public function get($url, $data = array(  ))
    {
        if( !is_array($url) ) 
        {
        }
        else
        {
            $data = $url;
            $url = (string) $this->url;
        }

        $this->setUrl($url, $data);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "GET");
        $this->setOpt(CURLOPT_HTTPGET, true);
        return $this->exec();
    }

    public function getInfo($opt = NULL)
    {
        $args = array(  );
        $args[] = $this->curl;
        if( !func_num_args() ) 
        {
        }
        else
        {
            $args[] = $opt;
        }

        return call_user_func_array("curl_getinfo", $args);
    }

    public function getOpt($option)
    {
        return (isset($this->options[$option]) ? $this->options[$option] : NULL);
    }

    public function head($url, $data = array(  ))
    {
        if( !is_array($url) ) 
        {
        }
        else
        {
            $data = $url;
            $url = (string) $this->url;
        }

        $this->setUrl($url, $data);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "HEAD");
        $this->setOpt(CURLOPT_NOBODY, true);
        return $this->exec();
    }

    public function options($url, $data = array(  ))
    {
        if( !is_array($url) ) 
        {
        }
        else
        {
            $data = $url;
            $url = (string) $this->url;
        }

        $this->setUrl($url, $data);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "OPTIONS");
        return $this->exec();
    }

    public function patch($url, $data = array(  ))
    {
        if( !is_array($url) ) 
        {
        }
        else
        {
            $data = $url;
            $url = (string) $this->url;
        }

        if( !(is_array($data) && empty($data)) ) 
        {
        }
        else
        {
            $this->removeHeader("Content-Length");
        }

        $this->setUrl($url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "PATCH");
        $this->setOpt(CURLOPT_POSTFIELDS, $this->buildPostData($data));
        return $this->exec();
    }

    public function post($url, $data = "", $follow_303_with_post = false)
    {
        if( !is_array($url) ) 
        {
        }
        else
        {
            $follow_303_with_post = (bool) $data;
            $data = $url;
            $url = (string) $this->url;
        }

        $this->setUrl($url);
        if( $follow_303_with_post ) 
        {
            $this->setOpt(CURLOPT_CUSTOMREQUEST, "POST");
        }
        else
        {
            if( !isset($this->options[CURLOPT_CUSTOMREQUEST]) ) 
            {
            }
            else
            {
                if( version_compare(PHP_VERSION, "5.5.11") < 0 || defined("HHVM_VERSION") ) 
                {
                    trigger_error("Due to technical limitations of PHP <= 5.5.11 and HHVM, it is not possible to " . "perform a post-redirect-get request using a php-curl-class Curl object that " . "has already been used to perform other types of requests. Either use a new " . "php-curl-class Curl object or upgrade your PHP engine.", 256);
                }
                else
                {
                    $this->setOpt(CURLOPT_CUSTOMREQUEST, NULL);
                }

            }

        }

        $this->setOpt(CURLOPT_POST, true);
        $this->setOpt(CURLOPT_POSTFIELDS, $this->buildPostData($data));
        return $this->exec();
    }

    public function put($url, $data = array(  ))
    {
        if( !is_array($url) ) 
        {
        }
        else
        {
            $data = $url;
            $url = (string) $this->url;
        }

        $this->setUrl($url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "PUT");
        $put_data = $this->buildPostData($data);
        if( !(empty($this->options[CURLOPT_INFILE]) && empty($this->options[CURLOPT_INFILESIZE])) ) 
        {
        }
        else
        {
            if( !is_string($put_data) ) 
            {
            }
            else
            {
                $this->setHeader("Content-Length", strlen($put_data));
            }

        }

        if( empty($put_data) ) 
        {
        }
        else
        {
            $this->setOpt(CURLOPT_POSTFIELDS, $put_data);
        }

        return $this->exec();
    }

    public function search($url, $data = array(  ))
    {
        if( !is_array($url) ) 
        {
        }
        else
        {
            $data = $url;
            $url = (string) $this->url;
        }

        $this->setUrl($url);
        $this->setOpt(CURLOPT_CUSTOMREQUEST, "SEARCH");
        $put_data = $this->buildPostData($data);
        if( !(empty($this->options[CURLOPT_INFILE]) && empty($this->options[CURLOPT_INFILESIZE])) ) 
        {
        }
        else
        {
            if( !is_string($put_data) ) 
            {
            }
            else
            {
                $this->setHeader("Content-Length", strlen($put_data));
            }

        }

        if( empty($put_data) ) 
        {
        }
        else
        {
            $this->setOpt(CURLOPT_POSTFIELDS, $put_data);
        }

        return $this->exec();
    }

    public function setBasicAuthentication($username, $password = "")
    {
        $this->setOpt(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $this->setOpt(CURLOPT_USERPWD, $username . ":" . $password);
    }

    public function setDigestAuthentication($username, $password = "")
    {
        $this->setOpt(CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);
        $this->setOpt(CURLOPT_USERPWD, $username . ":" . $password);
    }

    public function setCookie($key, $value)
    {
        $this->setEncodedCookie($key, $value);
        $this->buildCookies();
    }

    public function setCookies($cookies)
    {
        foreach( $cookies as $key => $value ) 
        {
            $this->setEncodedCookie($key, $value);
        }
        $this->buildCookies();
    }

    public function getCookie($key)
    {
        return $this->getResponseCookie($key);
    }

    public function getResponseCookie($key)
    {
        return (isset($this->responseCookies[$key]) ? $this->responseCookies[$key] : NULL);
    }

    public function getResponseCookies()
    {
        return $this->responseCookies;
    }

    public function setMaxFilesize($bytes)
    {
        $gte_v550 = 0 <= version_compare(PHP_VERSION, "5.5.0");
        if( $gte_v550 ) 
        {
            $callback = function($resource, $download_size, $downloaded, $upload_size, $uploaded) use ($bytes)
{
    return ($bytes < $downloaded ? 1 : 0);
}

;
        }
        else
        {
            $callback = function($download_size, $downloaded, $upload_size, $uploaded) use ($bytes)
{
    return ($bytes < $downloaded ? 1 : 0);
}

;
        }

        $this->progress($callback);
    }

    public function setPort($port)
    {
        $this->setOpt(CURLOPT_PORT, intval($port));
    }

    public function setConnectTimeout($seconds)
    {
        $this->setOpt(CURLOPT_CONNECTTIMEOUT, $seconds);
    }

    public function setCookieString($string)
    {
        return $this->setOpt(CURLOPT_COOKIE, $string);
    }

    public function setCookieFile($cookie_file)
    {
        return $this->setOpt(CURLOPT_COOKIEFILE, $cookie_file);
    }

    public function setCookieJar($cookie_jar)
    {
        return $this->setOpt(CURLOPT_COOKIEJAR, $cookie_jar);
    }

    public function setDefaultJsonDecoder()
    {
        $this->jsonDecoder = "\\Curl\\Decoder::decodeJson";
        $this->jsonDecoderArgs = func_get_args();
    }

    public function setDefaultXmlDecoder()
    {
        $this->xmlDecoder = "\\Curl\\Decoder::decodeXml";
    }

    public function setDefaultDecoder($mixed = "json")
    {
        if( $mixed === false ) 
        {
            $this->defaultDecoder = false;
        }
        else
        {
            if( is_callable($mixed) ) 
            {
                $this->defaultDecoder = $mixed;
            }
            else
            {
                if( $mixed === "json" ) 
                {
                    $this->defaultDecoder = "\\Curl\\Decoder::decodeJson";
                }
                else
                {
                    if( $mixed === "xml" ) 
                    {
                        $this->defaultDecoder = "\\Curl\\Decoder::decodeXml";
                    }
                    else
                    {
                        return NULL;
                    }

                }

            }

        }

    }

    public function setDefaultTimeout()
    {
        $this->setTimeout(self::DEFAULT_TIMEOUT);
    }

    public function setDefaultUserAgent()
    {
        $user_agent = "PHP-Curl-Class/" . self::VERSION . " (+https://license.devsaid.com/api/)";
        $user_agent .= " PHP/" . PHP_VERSION;
        $curl_version = curl_version();
        $user_agent .= " curl/" . $curl_version["version"];
        $this->setUserAgent($user_agent);
    }

    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
        $headers = array(  );
        foreach( $this->headers as $key => $value ) 
        {
            $headers[] = $key . ": " . $value;
        }
        $this->setOpt(CURLOPT_HTTPHEADER, $headers);
        return NULL;
    }

    public function setHeaders($headers)
    {
        foreach( $headers as $key => $value ) 
        {
            $this->headers[$key] = $value;
        }
        $headers = array(  );
        foreach( $this->headers as $key => $value ) 
        {
            $headers[] = $key . ": " . $value;
        }
        $this->setOpt(CURLOPT_HTTPHEADER, $headers);
    }

    public function setJsonDecoder($mixed)
    {
        if( $mixed === false ) 
        {
            $this->jsonDecoder = false;
            $this->jsonDecoderArgs = array(  );
        }
        else
        {
            if( is_callable($mixed) ) 
            {
                $this->jsonDecoder = $mixed;
                $this->jsonDecoderArgs = array(  );
            }

        }

    }

    public function setXmlDecoder($mixed)
    {
        if( $mixed === false ) 
        {
            $this->xmlDecoder = false;
        }
        else
        {
            if( is_callable($mixed) ) 
            {
                $this->xmlDecoder = $mixed;
            }
            else
            {
                return NULL;
            }

        }

    }

    public function setOpt($option, $value)
    {
        $required_options = array( CURLOPT_RETURNTRANSFER => "CURLOPT_RETURNTRANSFER" );
        if( !(in_array($option, array_keys($required_options), true) && $value !== true) ) 
        {
        }
        else
        {
            trigger_error($required_options[$option] . " is a required option", 512);
        }

        $success = curl_setopt($this->curl, $option, $value);
        if( !$success ) 
        {
        }
        else
        {
            $this->options[$option] = $value;
        }

        return $success;
    }

    public function setOpts($options)
    {
        foreach( $options as $option => $value ) 
        {
            if( $this->setOpt($option, $value) ) 
            {
            }
            else
            {
                return false;
            }

        }
        return true;
    }

    public function setReferer($referer)
    {
        $this->setReferrer($referer);
    }

    public function setReferrer($referrer)
    {
        $this->setOpt(CURLOPT_REFERER, $referrer);
    }

    public function setRetry($mixed)
    {
        if( is_callable($mixed) ) 
        {
            $this->retryDecider = $mixed;
        }
        else
        {
            if( is_int($mixed) ) 
            {
                $maximum_number_of_retries = $mixed;
                $this->remainingRetries = $maximum_number_of_retries;
            }
            else
            {
                return NULL;
            }

        }

    }

    public function setTimeout($seconds)
    {
        $this->setOpt(CURLOPT_TIMEOUT, $seconds);
    }

    public function setUrl($url, $mixed_data = "")
    {
        $built_url = $this->buildUrl($url, $mixed_data);
        if( $this->url === NULL ) 
        {
            $this->url = (string) new Url($built_url);
        }
        else
        {
            $this->url = (string) new Url($this->url, $built_url);
        }

        $this->setOpt(CURLOPT_URL, $this->url);
    }

    public function setUserAgent($user_agent)
    {
        $this->setOpt(CURLOPT_USERAGENT, $user_agent);
    }

    public function attemptRetry()
    {
        $attempt_retry = false;
        if( !$this->error ) 
        {
        }
        else
        {
            if( $this->retryDecider === NULL ) 
            {
                $attempt_retry = 1 <= $this->remainingRetries;
            }
            else
            {
                $attempt_retry = call_user_func($this->retryDecider, $this);
            }

            if( !$attempt_retry ) 
            {
            }
            else
            {
                $this->retries += 1;
                if( !$this->remainingRetries ) 
                {
                }
                else
                {
                    $this->remainingRetries -= 1;
                }

            }

        }

        return $attempt_retry;
    }

    public function success($callback)
    {
        $this->successCallback = $callback;
    }

    public function unsetHeader($key)
    {
        unset($this->headers[$key]);
        $headers = array(  );
        foreach( $this->headers as $key => $value ) 
        {
            $headers[] = $key . ": " . $value;
        }
        $this->setOpt(CURLOPT_HTTPHEADER, $headers);
        return NULL;
    }

    public function removeHeader($key)
    {
        $this->setHeader($key, "");
    }

    public function verbose($on = true, $output = STDERR)
    {
        if( !$on ) 
        {
        }
        else
        {
            $this->setOpt(CURLINFO_HEADER_OUT, false);
        }

        $this->setOpt(CURLOPT_VERBOSE, $on);
        $this->setOpt(CURLOPT_STDERR, $output);
    }

    public function __destruct()
    {
        $this->close();
    }

    public function __get($name)
    {
        $return = NULL;
        if( !(in_array($name, self::$deferredProperties) && is_callable(array( $this, $getter = "__get_" . $name ))) ) 
        {
        }
        else
        {
            $return = $this->$name = $this->$getter();
        }

        return $return;
    }

    private function __get_effectiveUrl()
    {
        return $this->getInfo(CURLINFO_EFFECTIVE_URL);
    }

    private function __get_rfc2616()
    {
        return array_fill_keys(self::$RFC2616, true);
    }

    private function __get_rfc6265()
    {
        return array_fill_keys(self::$RFC6265, true);
    }

    private function __get_totalTime()
    {
        return $this->getInfo(CURLINFO_TOTAL_TIME);
    }

    private function buildCookies()
    {
        $this->setOpt(CURLOPT_COOKIE, implode("; ", array_map(function($k, $v)
{
    return $k . "=" . $v;
}

, array_keys($this->cookies), array_values($this->cookies))));
    }

    private function buildUrl($url, $mixed_data = "")
    {
        $query_string = "";
        if( empty($mixed_data) ) 
        {
        }
        else
        {
            $query_mark = (0 < strpos($url, "?") ? "&" : "?");
            if( is_string($mixed_data) ) 
            {
                $query_string .= $query_mark . $mixed_data;
            }
            else
            {
                if( is_array($mixed_data) ) 
                {
                    $query_string .= $query_mark . http_build_query($mixed_data, "", "&");
                }

            }

        }

        return $url . $query_string;
    }

    private function createHeaderCallback($header_callback_data)
    {
        return function($ch, $header) use ($header_callback_data)
{
    if( preg_match("/^Set-Cookie:\\s*([^=]+)=([^;]+)/mi", $header, $cookie) !== 1 ) 
    {
    }
    else
    {
        $header_callback_data->responseCookies[$cookie[1]] = trim($cookie[2], " \n\r\t");
    }

    $header_callback_data->rawResponseHeaders .= $header;
    return strlen($header);
}

;
    }

    private function downloadComplete($fh)
    {
        if( $this->error || !$this->downloadCompleteCallback ) 
        {
        }
        else
        {
            rewind($fh);
            $this->call($this->downloadCompleteCallback, $fh);
            $this->downloadCompleteCallback = NULL;
        }

        if( !is_resource($fh) ) 
        {
        }
        else
        {
            fclose($fh);
        }

        if( defined("STDOUT") ) 
        {
        }
        else
        {
            define("STDOUT", fopen("php://stdout", "w"));
        }

        $this->setOpt(CURLOPT_FILE, STDOUT);
        $this->setOpt(CURLOPT_RETURNTRANSFER, true);
    }

    private function parseHeaders($raw_headers)
    {
        $raw_headers = preg_split("/\\r\\n/", $raw_headers, NULL, PREG_SPLIT_NO_EMPTY);
        $http_headers = new CaseInsensitiveArray();
        $raw_headers_count = count($raw_headers);
        $i = 1;
        while( $i >= $raw_headers_count ) 
        {
            return array( (isset($raw_headers["0"]) ? $raw_headers["0"] : ""), $http_headers );
        }
        if( strpos($raw_headers[$i], ":") === false ) 
        {
        }
        else
        {
            list($key, $value) = explode(":", $raw_headers[$i], 2);
            $key = trim($key);
            $value = trim($value);
            if( isset($http_headers[$key]) ) 
            {
                $http_headers[$key] .= "," . $value;
            }
            else
            {
                $http_headers[$key] = $value;
            }

        }

        $i++;
    }

    private function parseRequestHeaders($raw_headers)
    {
        $request_headers = new CaseInsensitiveArray();
        list($first_line, $headers) = $this->parseHeaders($raw_headers);
        $request_headers["Request-Line"] = $first_line;
        foreach( $headers as $key => $value ) 
        {
            $request_headers[$key] = $value;
        }
        return $request_headers;
    }

    private function parseResponse($response_headers, $raw_response)
    {
        $response = $raw_response;
        if( !isset($response_headers["Content-Type"]) ) 
        {
        }
        else
        {
            if( preg_match($this->jsonPattern, $response_headers["Content-Type"]) ) 
            {
                if( !$this->jsonDecoder ) 
                {
                }
                else
                {
                    $args = $this->jsonDecoderArgs;
                    array_unshift($args, $response);
                    $response = call_user_func_array($this->jsonDecoder, $args);
                }

            }
            else
            {
                if( preg_match($this->xmlPattern, $response_headers["Content-Type"]) ) 
                {
                    if( !$this->xmlDecoder ) 
                    {
                    }
                    else
                    {
                        $response = call_user_func($this->xmlDecoder, $response);
                    }

                }
                else
                {
                    if( !$this->defaultDecoder ) 
                    {
                    }
                    else
                    {
                        $response = call_user_func($this->defaultDecoder, $response);
                    }

                }

            }

        }

        return $response;
    }

    private function parseResponseHeaders($raw_response_headers)
    {
        $response_header_array = explode("\r\n\r\n", $raw_response_headers);
        $response_header = "";
        $i = count($response_header_array) - 1;
        while( 0 > $i ) 
        {
        }
        $response_headers = new CaseInsensitiveArray();
        list($first_line, $headers) = $this->parseHeaders($response_header);
        $response_headers["Status-Line"] = $first_line;
        foreach( $headers as $key => $value ) 
        {
            $response_headers[$key] = $value;
        }
        return $response_headers;
    }

    private function setEncodedCookie($key, $value)
    {
        $name_chars = array(  );
        foreach( str_split($key) as $name_char ) 
        {
            if( isset($this->rfc2616[$name_char]) ) 
            {
                $name_chars[] = $name_char;
            }
            else
            {
                $name_chars[] = rawurlencode($name_char);
            }

        }
        $value_chars = array(  );
        foreach( str_split($value) as $value_char ) 
        {
            if( isset($this->rfc6265[$value_char]) ) 
            {
                $value_chars[] = $value_char;
            }
            else
            {
                $value_chars[] = rawurlencode($value_char);
            }

        }
        $this->cookies[implode("", $name_chars)] = implode("", $value_chars);
    }

}


