/*
only for testing
*/
DROP TABLE IF EXISTS moderates;
DROP TABLE IF EXISTS banned_users;
DROP TABLE IF EXISTS in_game;
DROP TABLE IF EXISTS plays;
DROP TABLE IF EXISTS library;
DROP TABLE IF EXISTS buy;
DROP TABLE IF EXISTS post_of;
DROP TABLE IF EXISTS replied;
DROP TABLE IF EXISTS replies;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS game_genres;
DROP TABLE IF EXISTS genres;
DROP TABLE IF EXISTS friend_request;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS send_invitation;
DROP TABLE IF EXISTS friends;
DROP TABLE IF EXISTS discussions;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS games;
/*
 *
 */
CREATE TABLE users (
    user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(20) NOT NULL UNIQUE,
    pssword VARCHAR(45) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    balance DECIMAL(6,2),
    joinDate TIMESTAMP DEFAULT NOW(),    
    isAdmin BOOLEAN  NOT NULL DEFAULT FALSE,
    isGameDev BOOLEAN NOT NULL DEFAULT FALSE,
    isOnline BOOLEAN NOT NULL
);

CREATE TABLE games (
    game_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    game_name VARCHAR(255) NOT NULL UNIQUE,
    price DECIMAL(5,2),
    img_location VARCHAR(255),
    description LONGTEXT
);

CREATE TABLE buy(
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    date TIMESTAMP NOT NULL DEFAULT NOW(),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (game_id) REFERENCES games(game_id),    
    PRIMARY KEY(user_id,game_id)
);

CREATE TABLE in_game(
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (game_id) REFERENCES games(game_id),
    PRIMARY KEY (user_id,game_id)    
);

CREATE TABLE library(
    library_name VARCHAR(255) NOT NULL DEFAULT "My Games",
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (game_id) REFERENCES games(game_id),
    PRIMARY KEY (user_id,library_name)    
);

CREATE TABLE plays(
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    start_date TIMESTAMP NOT NULL,
    end_date TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (game_id) REFERENCES games(game_id)
);
CREATE TABLE genres (
    genre_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    genre VARCHAR(30) NOT NULL
);

CREATE TABLE game_genres(
    game_id INT NOT NULL,
    genre_id INT NOT NULL,
    FOREIGN KEY (game_id) REFERENCES genres(genre_id),
    FOREIGN KEY (game_id) REFERENCES games(game_id),
    PRIMARY KEY (game_id,genre_id)
);

CREATE TABLE friend_request(
    sender INT NOT NULL,
    reciever INT NOT NULL,
    FOREIGN KEY (sender) REFERENCES users(user_id),
    FOREIGN KEY (reciever) REFERENCES users(user_id),
    PRIMARY KEY (sender,reciever)
);

CREATE TABLE friends(
    user_id1 INT NOT NULL,
    user_id2 INT NOT NULL,
    FOREIGN KEY (user_id1) REFERENCES users(user_id),
    FOREIGN KEY (user_id2) REFERENCES users(user_id),
    PRIMARY KEY (user_id1, user_id2)
);

CREATE TABLE messages(
    message_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    sender INT NOT NULL,
    reciever INT NOT NULL,
    date TIMESTAMP DEFAULT NOW(),
    text LONGTEXT,
    FOREIGN KEY (sender) REFERENCES users(user_id),
    FOREIGN KEY (reciever) REFERENCES users(user_id)
);

CREATE TABLE send_invitation(
    sender INT NOT NULL,
    reciever INT NOT NULL,
    game_id INT NOT NULL,
    date TIMESTAMP DEFAULT NOW(),
    FOREIGN KEY (sender) REFERENCES users(user_id),
    FOREIGN KEY (reciever) REFERENCES users(user_id),
    FOREIGN KEY (game_id) REFERENCES games(game_id),
    PRIMARY KEY (game_id, sender, reciever)
);

CREATE TABLE discussions(
    discussion_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    discussion_name VARCHAR(30) NOT NULL,
    game_id INT NOT NULL,
    FOREIGN KEY (game_id) REFERENCES games(game_id),
    UNIQUE(game_id, discussion_name)
);
CREATE TABLE moderates(
    user_id INT NOT NULL,
    discussion_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (discussion_id) REFERENCES discussions(discussion_id),
    PRIMARY KEY (user_id,discussion_id)
);

CREATE TABLE banned_users(
    banned_user_id INT NOT NULL,
    moderator_id INT NOT NULL,
    discussion_id INT NOT NULL,
    FOREIGN KEY (banned_user_id) REFERENCES users(user_id),
    FOREIGN KEY (moderator_id) REFERENCES users(user_id),
    FOREIGN KEY (discussion_id) REFERENCES discussions(discussion_id),
    PRIMARY KEY (banned_user_id, moderator_id, discussion_id)
);

CREATE TABLE comments(
    comment_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    text LONGTEXT NOT NULL
);

CREATE TABLE posts(
    title varchar(255) NOT NULL,
    comment_id INT NOT NULL,
    FOREIGN KEY (comment_id) REFERENCES comments(comment_id)
);

CREATE TABLE replies(
    depth INT NOT NULL,
    comment_id INT NOT NULL,
    FOREIGN KEY (comment_id) REFERENCES comments(comment_id)
);

CREATE TABLE replied(
    user_id INT NOT NULL,
    parent_id INT NOT NULL,
    reply_id INT NOT NULL,
    date TIMESTAMP DEFAULT NOW(),
    FOREIGN KEY (parent_id) REFERENCES comments(comment_id),
    FOREIGN KEY (reply_id) REFERENCES comments(comment_id)    
);

CREATE TABLE post_of(
    user_id INT NOT NULL,
    comment_id INT NOT NULL,
    discussion_id INT NOT NULL,
    date TIMESTAMP DEFAULT NOW(),
    FOREIGN KEY (user_id) REFERENCES users(user_id),    
    FOREIGN KEY (comment_id) REFERENCES comments(comment_id),
    FOREIGN KEY (discussion_id) REFERENCES discussions(discussion_id)
);