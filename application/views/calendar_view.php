<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Event Calendar</title>

	<!-- FullCalendar CSS -->
	<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

	<!-- jQuery -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

	<!-- FullCalendar JS -->
	<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
</head>

<style>
	.event-modal {
		position: fixed;
		top: 50%;
		left: 50%;
		transform: translate(-50%, -50%);
		background-color: #dcdcdc;
		padding: 20px;
		border-radius: 5px;
		box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		z-index: 9999;
	}

	.event-modal h3 {
		margin-bottom: 20px;
	}

	.event-modal button {
		margin: 5px;
		padding: 10px;
		background-color: #3788d8;
		color: white;
		border: none;
		cursor: pointer;
		border-radius: 5px;
	}

	.event-modal button:hover {
		background-color: #0056b3;
	}

	#closeModalBtn {
		background-color: #dc3545;
		/* Red color for Close */
	}

	#closeModalBtn:hover {
		background-color: #c82333;
		/* Darker red */
	}
</style>



<body>

	<div id="calendar"></div>

	<script>
		$(document).ready(function () {
			var base_url = '<?= base_url() ?>';

			const calendarEl = document.getElementById('calendar');
			const calendar = new FullCalendar.Calendar(calendarEl, {
				initialView: 'dayGridMonth',
				events: base_url + 'EventCtrl/fetchEvents',
				selectable: true,
				editable: true,

				// When a date is clicked, prompt the user to enter an event title
				dateClick: function (info) {
					const title = prompt('Enter Event Title:');
					if (title) {
						$.post(base_url + 'EventCtrl/createEvent', {
							title: title,
							start_date: info.dateStr,
							end_date: info.dateStr
						}, function (response) {
							// Reload the events to show the new event
							calendar.refetchEvents();
							alert('Event added successfully!');
						});
					}
				},

				// When an event is clicked, show buttons for update or delete
				eventClick: function (info) {
					// Create a confirmation dialog with two buttons (Update, Delete) and a Close button
					var modalHtml = `
					<div class="event-modal">
						<h3>What do you want to do with this event?</h3>
						<button id="updateEventBtn">Update</button>
						<button id="deleteEventBtn">Delete</button>
						<button id="closeModalBtn">Close</button>
					</div>
				`;

					// Append modal to the body
					$('body').append(modalHtml);

					// When the update button is clicked
					$('#updateEventBtn').on('click', function () {
						const newTitle = prompt('Enter new event title:', info.event.title);
						if (newTitle !== null) {
							$.post(base_url + 'EventCtrl/updateEvent', {
								id: info.event.id,
								title: newTitle,
								start_date: info.event.start.toISOString(),
								end_date: info.event.end ? info.event.end.toISOString() : info.event.start.toISOString()
							}, function (response) {
								// Reload the events to reflect the updated event
								calendar.refetchEvents();
								alert('Event updated successfully!');
								$('.event-modal').remove(); // Close the modal
							});
						}
					});

					// When the delete button is clicked
					$('#deleteEventBtn').on('click', function () {
						if (confirm('Do you want to delete this event?')) {
							$.post(base_url + 'EventCtrl/deleteEvent/' + info.event.id, function (response) {
								// Reload events after deletion
								calendar.refetchEvents();
								alert('Event deleted successfully!');
								$('.event-modal').remove(); // Close the modal
							});
						}
					});

					// When the close button is clicked, remove the modal
					$('#closeModalBtn').on('click', function () {
						$('.event-modal').remove(); // Close the modal
					});
				},

				// When an event is dragged and dropped, update the event's date in the backend
				eventDrop: function (info) {
					$.post(base_url + 'EventCtrl/updateEvent', {
						id: info.event.id,
						start_date: info.event.start.toISOString(),
						end_date: info.event.end ? info.event.end.toISOString() : info.event.start.toISOString()
					}, function (response) {
						// Reload the events to reflect the update
						calendar.refetchEvents();
						alert('Event updated successfully!');
					});
				}
			});

			// Render the calendar
			calendar.render();
		});
	</script>








</body>

</html>