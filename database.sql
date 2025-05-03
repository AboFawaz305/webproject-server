CREATE DATABASE blogsAPI;
USE blogsAPI;

CREATE TABLE IF NOT EXISTS Users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(64) NOT NULL,
  hashed_password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS Articles (
  article_id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255),
  content TEXT,
  summary VARCHAR(255),
  author_id INT NOT NULL,
  publish_date DATE DEFAULT CURRENT_DATE,
  title_img_src VARCHAR(255),
  title_img_alt VARCHAR(255),
  quote VARCHAR(255),
  FOREIGN KEY(author_id) REFERENCES Users(user_id)
);
