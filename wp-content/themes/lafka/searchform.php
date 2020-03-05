<?php

$lafka_search_params = array(
	'placeholder'  	=> esc_attr__('Pretraži','lafka'),
	'search_id'	   	=> 's',
	'form_action'	=> lafka_wpml_get_home_url(),
	'ajax_disable'	=> false
);

?>

<form action="<?php echo esc_url($lafka_search_params['form_action']); ?>" id="searchform" method="get">
	<div>
		<input type="submit" id="searchsubmit"  value="<?php esc_attr_e('Pretraži', 'lafka') ?>"/>
		<input type="text" id="s" name="<?php echo esc_attr($lafka_search_params['search_id']); ?>" value="<?php if(!empty($_GET['s'])) echo esc_attr(get_search_query()); ?>" placeholder='<?php echo esc_attr($lafka_search_params['placeholder']); ?>' />
        <small class="lafka-search-hint-text"><?php echo esc_html__('Otkucaj naziv ženjenog proizvoda i klikni Enter', 'lafka') ?></small>
	</div>
</form>