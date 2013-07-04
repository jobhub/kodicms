cms.init.add(['user_edit', 'user_add'], function () {

	$('input[name="user_permission"]').select2({
		placeholder: __("Click to get list of roles"),
		minimumInputLength: 0,
		multiple: true,
		ajax: {
			url: '/api/user-roles.get',
			data: function(query, pageNumber, context) {
				return {
					key: query,
					fields: 'id,name'
				}
			},
			dataType: 'json',
			results: function (resp, page) {
				var roles = [];
				if(resp.response) {
					for(i in resp.response) {
						roles.push({
							id: resp.response[i]['id'],
							text: resp.response[i]['name']
						});
					}
				}

				return {results: roles};
			}
		},
		initSelection: function(element, callback) {
			if (USER_ID == 0) return;
			$.ajax('/api-users.roles', {
				data: {
					uid: USER_ID,
					fields: 'id,name'
				},
				dataType: 'json',
			}).done(function(resp, page) {
				var roles = [];
				if(resp.response) {
					for(i in resp.response) {
						roles.push({
							id: resp.response[i]['id'],
							text: resp.response[i]['name']
						});
					}
				}

				callback(roles);
			});
		}
	});
});

cms.init.add('user_add', function () {
	$(function() {
		$('.spoiler-toggle').click();
	})
});