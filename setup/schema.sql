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

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  is_admin TINYINT(1) DEFAULT 0
);

CREATE TABLE IF NOT EXISTS gallery (
  image_id INT AUTO_INCREMENT PRIMARY KEY,
  filename VARCHAR(255) NOT NULL,
  uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(64),
    email VARCHAR(255),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

