<div class="btn-toolbar" id="datasource-toolbar">
	<div class="btn-group">
		<?php echo UI::button(__('Create section'), array(
			'href' => '#', 'class' => 'btn dropdown-toggle btn-success',
			'icon' => UI::icon( 'plus icon-white' ), 'data-toggle' => 'dropdown'
		)); ?>

		<ul class="dropdown-menu">
		<?php foreach (Datasource_Data_Manager::types() as $type => $title): ?>
			<?php if(ACL::check($ds_type.'.section.create')): ?>
			<li><?php echo HTML::anchor(Route::url('datasources', array(
					'controller' => 'section',
					'directory' => $type,
					'action' => 'create'
				)), $title); ?></li>
			<?php endif; ?>
		<?php endforeach; ?>
		</ul>
	</div>

	<?php if($ds_id): ?>
	<div class="btn-group pull-right">
		<?php if(ACL::check($ds_type.$ds_id.'.section.edit')): ?>
		<?php echo UI::button(__('Edit'), array(
			'href' => Route::url('datasources', array(
				'controller' => 'section',
				'directory' => $ds_type,
				'action' => 'edit',
				'id' => $ds_id
			)),
			'icon' => UI::icon( 'cog' ),
			'class' => 'btn btn-mini'
		)); ?>
		<?php endif; ?>

		<?php if(ACL::check($ds_type.$ds_id.'.section.remove')): ?>
		<?php echo UI::button(__('Remove'), array(
			'href' => Route::url('datasources', array(
				'controller' => 'section',
				'directory' => $ds_type,
				'action' => 'remove',
				'id' => $ds_id
			)),
			'icon' => UI::icon( 'trash icon-white' ),
			'class' => 'btn btn-danger btn-confirm btn-mini'
		)); ?>
		<?php endif; ?>
	</div>
	<?php endif; ?>
</div>