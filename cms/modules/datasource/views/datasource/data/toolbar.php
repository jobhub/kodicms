<div class="btn-toolbar" id="datasource-toolbar">
	<div class="btn-group">
		<?php echo UI::button('Create', array(
			'href' => '#', 'class' => 'btn dropdown-toggle btn-success',
			'icon' => UI::icon( 'plus icon-white' ), 'data-toggle' => 'dropdown'
		)); ?>

		<ul class="dropdown-menu">
		<?php foreach (Datasource_Data_Manager::types() as $type => $title): ?>
			<li><?php echo HTML::anchor($type . '/section/create', $title); ?></li>
		<?php endforeach; ?>
		</ul>
	</div>

	<?php if($ds_id): ?>
	<div class="btn-group pull-right">
		<?php echo UI::button('Edit', array(
			'href' => $ds_type . '/section/edit/' . $ds_id,
			'icon' => UI::icon( 'cog' ),
			'class' => 'btn btn-mini'
		)); ?>

		<?php echo UI::button('Remove', array(
			'href' => $ds_type . '/section/remove/' . $ds_id,
			'icon' => UI::icon( 'trash icon-white' ),
			'class' => 'btn btn-danger btn-confirm btn-mini'
		)); ?>
	</div>
	<?php endif; ?>
</div>