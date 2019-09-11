$(function() {
	$('#delete_task').click(function(event) {
		if (!confirm('Voulez-vous supprimer cette tâche ?'))
		{
			event.preventDefault();
		}
	});
});