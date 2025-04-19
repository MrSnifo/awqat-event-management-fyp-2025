<?php
class Event
{
    private $db;
    private $table = 'events';

    public function __construct($db) {
        $this->db = $db;
    }

    // Create a new event.
    public function create(array $data): bool {
        $query = "INSERT INTO {$this->table} 
            (title, description, start_date, end_date, start_time, end_time, location, tags, cover_image, interested)
            VALUES (:title, :description, :start_date, :end_date, :start_time, :end_time, :location, :tags, :cover_image, :interested)";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':title'        => $data['title'],
            ':description'  => $data['description'],
            ':start_date'   => $data['start_date'],
            ':end_date'     => $data['end_date'],
            ':start_time'   => $data['start_time'],
            ':end_time'     => $data['end_time'],
            ':location'     => $data['location'],
            ':tags'         => json_encode($data['tags'] ?? []),
            ':cover_image'  => $data['cover_image'],
            ':interested'   => $data['interested'] ?? 0
        ]);
    }

    // Get event by ID.
    public function getById(int $id): ?array {
        $query = "SELECT * FROM {$this->table} WHERE id_event = :id_event LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id_event' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    // Update event by ID.
    public function update(int $id, array $data): bool {
        $query = "UPDATE {$this->table} 
                  SET title = :title, description = :description, start_date = :start_date, 
                      end_date = :end_date, start_time = :start_time, end_time = :end_time, 
                      location = :location, tags = :tags, cover_image = :cover_image, 
                      interested = :interested
                  WHERE id_event = :id_event";

        $stmt = $this->db->prepare($query);

        return $stmt->execute([
            ':id_event'     => $id,
            ':title'        => $data['title'],
            ':description'  => $data['description'],
            ':start_date'   => $data['start_date'],
            ':end_date'     => $data['end_date'],
            ':start_time'   => $data['start_time'],
            ':end_time'     => $data['end_time'],
            ':location'     => $data['location'],
            ':tags'         => json_encode($data['tags'] ?? []),
            ':cover_image'  => $data['cover_image'],
            ':interested'   => $data['interested'] ?? 0
        ]);
    }

    // Delete an event by ID.
    public function delete(int $id): bool {
        $query = "DELETE FROM {$this->table} WHERE id_event = :id_event";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id_event' => $id]);
    }

    // Get all events.
    public function getAll(): array {
        $query = "SELECT * FROM {$this->table} ORDER BY start_date ASC";
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Search events by title.
    public function searchByTitle(string $keyword): array {
        $query = "SELECT * FROM {$this->table} WHERE title LIKE :keyword";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':keyword' => '%' . $keyword . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
