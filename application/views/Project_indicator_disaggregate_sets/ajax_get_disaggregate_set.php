

<?php echo form_multiselect('id_disaggregate_sets[]', $disaggregate_sets, set_value('id_disaggregate_sets[]',$disaggregate_set), ['id' => 'id_disaggregate_sets', 'class' => 'input_textbox form-control', 'onchange' => 'ajax_get_code_by_id()']); ?>