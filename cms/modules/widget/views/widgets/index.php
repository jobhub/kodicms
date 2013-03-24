<div class="map widget widget-nopad">
	
	<div class="widget-header">
		<?php echo UI::button(__('Add widget'), array(
			'href' => 'widgets/add', 'icon' => UI::icon('plus'),
		)); ?>
	</div>
	
	<div class="widget-content">
		<table class=" table table-striped table-hover" id="SnippetList">
			<colgroup>
				<col width="150px" />
				<col />
				<col width="100px" />
			</colgroup>
			<thead>
				<tr>
					<th><?php echo __('Widget name'); ?></th>
					<th><?php echo __('Description'); ?></th>
					<th><?php echo __('Actions'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($widgets as $type => $_widgets): ?>
				<tr>
					<td colspan="3"><h5><?php echo $type; ?></h5></td>
				</tr>
				<?php foreach ($_widgets as $widget): ?>
				<tr>
					<th class="name">
						<?php echo HTML::anchor('widgets/edit/'.$widget->id, $widget->name); ?>
					</th>
					<td class="description">
						<span class="muted"><?php echo $widget->description; ?></span>
					</td>
					<td class="actions">
						<?php echo UI::button(NULL, array(
							'href' => 'widgets/delete/'. $widget->id, 'icon' => UI::icon('remove'),
							 'class' => 'btn btn-mini btn-confirm'
						)); ?>
					</td>
				</tr>
				<?php endforeach; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div><!--/#snippetMap-->