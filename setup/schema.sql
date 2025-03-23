CREATE TABLE IF NOT EXISTS room_type(
  room_type_id INT NOT NULL AUTO_INCREMENT,
  room_type_name VARCHAR(32) UNIQUE,
  total_room INT DEFAULT 3,
  CONSTRAINT pk_room_type PRIMARY KEY (room_type_id)
);

CREATE TABLE IF NOT EXISTS reservation(
  reservation_id INT NOT NULL AUTO_INCREMENT,
  room_type_id INT NOT NULL,
  begin_date DATE NOT NULL,
  end_date DATE NOT NULL,
  confirm_number CHAR(13),
  CONSTRAINT pk_reservation PRIMARY KEY (reservation_id),
  CONSTRAINT fk_reservation_room_type FOREIGN KEY (room_type_id) REFERENCES room_type(room_type_id)
);

CREATE TABLE IF NOT EXISTS testimonials (
    testimonial_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(64) NOT NULL,
    comment TEXT NOT NULL,
    photo VARCHAR(255) DEFAULT NULL,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

