<div class="widget-content widget-no-border-radius">
	<div class="control-group">
		<label class="control-label" for="list_offset"><?php echo __('Number of documents to omit'); ?></label>
		<div class="controls">
			<?php
			echo Form::input( 'list_offset', $widget->list_offset, array(
				'class' => 'input-small', 'id' => 'list_offset'
			) );
			?>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="list_size"><?php echo __('Number of documents per page'); ?></label>
		<div class="controls">
			<?php
			echo Form::input( 'list_size', $widget->list_size, array(
				'class' => 'input-small', 'id' => 'list_size'
			) );
			?>
		</div>
	</div>
</div>