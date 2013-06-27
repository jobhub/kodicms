<script>
	var DS_ID = '<?php echo $ds->ds_id; ?>';
</script>

<div class="widget">
<?php echo Form::open(Request::current()->uri(), array(
	'class' => 'form-horizontal'
)); ?>

	<?php echo Form::hidden('ds_id', $ds->ds_id); ?>
	
	<div class="widget-header">
		<h3><?php echo __( 'Add hybrid field' ); ?></h3>
	</div>

	<div class="widget-content" id="filed-type">
		<div class="control-group">
			<label class="control-label title" for="header"><?php echo __('Field header'); ?></label>
			<div class="controls">
				<?php echo Form::input( 'header', Arr::get($post_data, 'header'), array(
					'class' => 'input-xlarge slug-generator input-title span12', 'id' => 'header', 'data-separator' => '_'
				) ); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="name"><?php echo __('Field key'); ?></label>
			<div class="controls">
				<?php echo Form::input( 'name', Arr::get($post_data, 'name'), array(
					'class' => 'input-xlarge slug', 'id' => 'name'
				) ); ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="field-type-select"><?php echo __('Field type'); ?></label>
			<div class="controls">
				<?php echo Form::select( 'family', DataSource_Data_Hybrid_Field::types(), Arr::get($post_data, 'family'), array(
					'id' => 'field-type-select'
				)); ?>
			</div>
		</div>
		
		<div id="field-options">
			<?php foreach (DataSource_Data_Hybrid_Field::types() as $type => $title): ?>
			<?php echo View::factory('datasource/data/hybrid/field/add/' . $type, array(
				'sections' => $sections, 'post_data' => $post_data, 'title' => $title
			)); ?>
			<?php endforeach; ?>
		</div>
		
		<hr />
		
		<div class="control-group">
			<label class="control-label" for="isreq"><?php echo __('Required'); ?></label>
			<div class="controls">
				<div class="checkbox">
					<?php echo Form::checkbox( 'isreq', 1, (Arr::get($post_data, 'isreq') == 1), array(
						'id' => 'isreq'
					)); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="widget-footer form-actions">
		<?php echo UI::button( __('Add field'), array(
			'icon' => UI::icon( 'plus'), 'class' => 'btn btn-large'
		)); ?>
	</div>
<?php echo Form::close(); ?>
</div>