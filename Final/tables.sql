/*
only for testing
*/
DROP TABLE IF EXISTS publish;
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
DROP TABLE IF EXISTS games;
DROP TABLE IF EXISTS users;
/*
 *
 */
CREATE TABLE users (
    user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    balance DECIMAL(6,2) NOT NULL DEFAULT 0,
    joinDate TIMESTAMP DEFAULT NOW(),    
    isAdmin BOOLEAN  NOT NULL DEFAULT FALSE,
    isGameDev BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE games (
    game_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    game_name VARCHAR(255) NOT NULL UNIQUE,
    price DECIMAL(5,2) NOT NULL,
    img_location VARCHAR(255),
    release_date TIMESTAMP DEFAULT NOW(),
    description LONGTEXT,
    publisher_id INT,
    discount_amount DECIMAL (2,2) DEFAULT 0,
    FOREIGN KEY (publisher_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE buy(
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    price DECIMAL(5,2) NOT NULL,
    date TIMESTAMP NOT NULL DEFAULT NOW(),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE,    
    PRIMARY KEY(user_id,game_id)
);

CREATE TABLE in_game(
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE, 
    FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE,
    PRIMARY KEY (user_id,game_id)    
);

CREATE TABLE library(
    library_name VARCHAR(255) NOT NULL DEFAULT "My Games",
    user_id INT NOT NULL,
    game_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE,
    PRIMARY KEY (user_id,library_name,game_id)  
);

CREATE TABLE plays(
    user_id INT NOT NULL,
    game_id INT NOT NULL,
    start_date TIMESTAMP DEFAULT NOW(),
    end_date TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE,
    UNIQUE(user_id,start_date)
);
CREATE TABLE genres (
    genre_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    genre_name VARCHAR(30) NOT NULL,
    age_registriction INT NOT NULL
);

CREATE TABLE game_genres(
    game_id INT NOT NULL,
    genre_id INT NOT NULL,
    FOREIGN KEY (genre_id) REFERENCES genres(genre_id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE,
    PRIMARY KEY (game_id,genre_id)
);

CREATE TABLE friend_request(
    sender_id INT NOT NULL,
    reciever_id INT NOT NULL,
    date TIMESTAMP DEFAULT NOW(),
    FOREIGN KEY (sender_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (reciever_id) REFERENCES users(user_id) ON DELETE CASCADE,
    PRIMARY KEY (sender_id,reciever_id)
);

CREATE TABLE friends(
    user_id1 INT NOT NULL,
    user_id2 INT NOT NULL,
    date TIMESTAMP DEFAULT NOW(),
    FOREIGN KEY (user_id1) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id2) REFERENCES users(user_id) ON DELETE CASCADE,
    PRIMARY KEY (user_id1, user_id2)
);

CREATE TABLE messages(
    message_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    reciever_id INT NOT NULL,
    date TIMESTAMP DEFAULT NOW(),
    text LONGTEXT,
    FOREIGN KEY (sender_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (reciever_id) REFERENCES users(user_id) ON DELETE CASCADE,
    UNIQUE(sender_id,reciever_id,date)
);

CREATE TABLE send_invitation(
    sender_id INT NOT NULL,
    reciever_id INT NOT NULL,
    game_id INT NOT NULL,
    date TIMESTAMP DEFAULT NOW(),
    FOREIGN KEY (sender_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (reciever_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE,
    PRIMARY KEY (game_id, sender_id, reciever_id) 
);

CREATE TABLE discussions(
    discussion_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    discussion_name VARCHAR(30) NOT NULL,
    game_id INT NOT NULL,
    FOREIGN KEY (game_id) REFERENCES games(game_id) ON DELETE CASCADE,
    UNIQUE(game_id, discussion_name)
);
CREATE TABLE moderates(
    user_id INT NOT NULL,
    discussion_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (discussion_id) REFERENCES discussions(discussion_id) ON DELETE CASCADE,
    PRIMARY KEY (user_id,discussion_id)
);

CREATE TABLE banned_users(
    banned_user_id INT NOT NULL,
    moderator_id INT NOT NULL,
    discussion_id INT NOT NULL,
    date TIMESTAMP DEFAULT NOW(),
    FOREIGN KEY (banned_user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (moderator_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (discussion_id) REFERENCES discussions(discussion_id) ON DELETE CASCADE,
    PRIMARY KEY (banned_user_id, discussion_id)
);

CREATE TABLE comments(
    comment_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    date TIMESTAMP DEFAULT NOW(),    
    text LONGTEXT NOT NULL
);

CREATE TABLE posts(
    user_id INT NOT NULL,    
    title varchar(255) NOT NULL,
    comment_id INT NOT NULL,
    discussion_id INT NOT NULL,
    date TIMESTAMP DEFAULT NOW(),    
    FOREIGN KEY (comment_id) REFERENCES comments(comment_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    PRIMARY KEY(user_id,comment_id, discussion_id,date)
); 

CREATE TABLE replies(
    user_id INT NOT NULL,    
    comment_id INT NOT NULL,
    replied_id INT NOT NULL,
    date TIMESTAMP DEFAULT NOW(),    
    FOREIGN KEY (comment_id) REFERENCES comments(comment_id) ON DELETE CASCADE,
    FOREIGN KEY (replied_id) REFERENCES comments(comment_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    PRIMARY KEY(user_id,comment_id, date)
);

/*
CREATE TABLE replied(
    user_id INT NOT NULL,
    parent_id INT NOT NULL,
    reply_id INT NOT NULL,
    date TIMESTAMP DEFAULT NOW(),
    FOREIGN KEY (parent_id) REFERENCES comments(comment_id) ON DELETE CASCADE,
    FOREIGN KEY (reply_id) REFERENCES comments(comment_id) ON DELETE CASCADE,
    UNIQUE(user_id, reply_id)    
);

CREATE TABLE post_of(
    user_id INT NOT NULL,
    comment_id INT NOT NULL,
    discussion_id INT NOT NULL,
    date TIMESTAMP DEFAULT NOW(),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,    
    FOREIGN KEY (comment_id) REFERENCES comments(comment_id) ON DELETE CASCADE,
    FOREIGN KEY (discussion_id) REFERENCES discussions(discussion_id) ON DELETE CASCADE,
    PRIMARY KEY (user_id,comment_id)
);
*/
