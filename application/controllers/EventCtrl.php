<?php
defined('BASEPATH') or exit('No direct script access allowed');

class EventCtrl extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('EventModel');
	}

	public function index()
	{
		//echo 111;exit;
		$this->load->view('calendar_view');
	}

	// Fetch Events
	public function fetchEvents()
	{
		$events = $this->EventModel->getEvents();

		// Format the events data to match FullCalendar's expected format
		$formatted_events = array();
		foreach ($events as $event) {
			$formatted_events[] = array(
				'id' => $event['id'],
				'title' => $event['title'],
				'start' => $event['start_date'],
				'end' => $event['end_date'],
			);
		}

		// Return the events in JSON format
		echo json_encode($formatted_events);
	}


	// Create Event
	public function createEvent()
	{
		//echo 111;exit;
		$data = [
			'title' => $this->input->post('title'),
			'start_date' => $this->input->post('start_date'),
			'end_date' => $this->input->post('end_date')
		];

		if ($this->EventModel->addEvent($data)) {
			echo json_encode(['status' => 'success']);
		} else {
			echo json_encode(['status' => 'error']);
		}
	}

	// Update Event
	public function updateEvent()
	{
		$data = $this->input->post();

		if (!empty($data['id']) && !empty($data['title']) && !empty($data['start_date'])) {
			// Ensure the timezone is set to Asia/Kolkata before processing the dates
			date_default_timezone_set('Asia/Kolkata');

			// Convert the received dates to DateTime objects in local timezone
			$start_date = new DateTime($data['start_date'], new DateTimeZone('Asia/Kolkata')); // Local timezone
			$start_date->setTimezone(new DateTimeZone('UTC')); // Convert to UTC
			// $start_date = $start_date->format('Y-m-d H:i:s'); // MySQL compatible format

			$start_date = $start_date->modify('+1 day')->format('Y-m-d H:i:s'); // Increment date by 1 day


			$end_date = isset($data['end_date']) ? new DateTime($data['end_date'], new DateTimeZone('Asia/Kolkata')) : $start_date;
			$end_date->setTimezone(new DateTimeZone('UTC')); // Convert to UTC
			// $end_date = $end_date->format('Y-m-d H:i:s'); // MySQL compatible format
			$end_date = $end_date->modify('+1 day')->format('Y-m-d H:i:s'); // Increment date by 1 day

			// Prepare the updated data
			$updated_data = array(
				'title' => $data['title'],
				'start_date' => $start_date,
				'end_date' => $end_date
			);

			if ($this->EventModel->updateEvent($data['id'], $updated_data)) {
				echo json_encode(['status' => 'success']);
			} else {
				echo json_encode(['status' => 'error']);
			}
		}
		// for dragging the event
		else if (!empty($data['id']) && !empty($data['start_date'])) {



			// Ensure the timezone is set to Asia/Kolkata before processing the dates
			date_default_timezone_set('Asia/Kolkata');

			// Convert the received dates to DateTime objects in local timezone
			$start_date = new DateTime($data['start_date'], new DateTimeZone('Asia/Kolkata')); // Local timezone
			$start_date->setTimezone(new DateTimeZone('UTC')); // Convert to UTC
			// $start_date = $start_date->format('Y-m-d H:i:s'); // MySQL compatible format

			$start_date = $start_date->modify('+1 day')->format('Y-m-d H:i:s'); // Increment date by 1 day


			$end_date = isset($data['end_date']) ? new DateTime($data['end_date'], new DateTimeZone('Asia/Kolkata')) : $start_date;
			$end_date->setTimezone(new DateTimeZone('UTC')); // Convert to UTC
			// $end_date = $end_date->format('Y-m-d H:i:s'); // MySQL compatible format
			$end_date = $end_date->modify('+1 day')->format('Y-m-d H:i:s'); // Increment date by 1 day

			// echo $end_date;
			// exit;
			// Prepare the updated data
			$updated_data = array(
				'start_date' => $start_date,
				'end_date' => $end_date
			);

			if ($this->EventModel->updateEvent($data['id'], $updated_data)) {
				echo json_encode(['status' => 'success']);
			} else {
				echo json_encode(['status' => 'error']);
			}

		} else {
			echo json_encode(['status' => 'error']);
		}
	}
	


	// Delete Event
	public function deleteEvent($id)
	{
		if ($this->EventModel->deleteEvent($id)) {
			echo json_encode(['status' => 'success']);
		} else {
			echo json_encode(['status' => 'error']);
		}
	}
}
