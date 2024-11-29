<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EventModel extends CI_Model {

    // Get All Events
    public function getEvents() {
        return $this->db->get('events')->result_array();
    }

    // Add New Event
    public function addEvent($data) {
        // Ensure the data includes 'title', 'start_date', and 'end_date'
        $insert_data = array(
            'title' => $data['title'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        );
        return $this->db->insert('events', $insert_data);
    }
    

    // Update Existing Event
    public function updateEvent($id, $updated_data) {
        // Ensure the correct event is being updated
        $this->db->where('id', $id);
        return $this->db->update('events', $updated_data);
    }
    
    
    

    // Delete Event
    public function deleteEvent($id) {
        $this->db->where('id', $id);
        return $this->db->delete('events');
    }
}
