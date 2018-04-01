/*
    Login Screen
    Inputs: @email_username, @password
*/

SELECT user_id
FROM users
WHERE email = @email_username OR user_name = email_username AND password = @password
;

/*
    Sign-up Screen
    Inputs: @user_name, @password, @email
*/

INSERT INTO users (user_name, password, email) VALUES (
    @user_name,
    @password,
    @email
);

/*
    Buy Game
    Inputs @user_id, @game_id, @library_name
*/

INSERT INTO library (library_name, user_id, game_id) VALUES (
    @library_name,
    @user_id,
    @game_id
);

/*
    Viewin Library
    Inputs @user_id, @library_name
*/

SELECT game_name, img_location
FROM games g NATURAL JOIN library l on l.game_id = g.game_id
WHERE user_id = @user_id AND library_name = @library_name
;

/*
    Buy Game
    Inputs: @user_id, @game_id, @library_name
*/ 

INSERT INTO buy (user_id,game_id) VALUES (
    @user_id,
    @game_id
);

/*
    Increase balance
    Inputs: @user_id, @amount
*/

UPDATE users
SET balance = balance + @amount
WHERE user_id = @user_id
;

/*
    View Post Discussion
    Input: @discussion_id
*/

SELECT title, u.user_name, date
FROM post_of p NATURAL JOIN users u ON u.user_id = p.user_id
WHERE discussion_id = @discussion_id
ORDER BY date DESC
LIMIT 10
;

/*
    Add Friends
    Input: @user_id1, @user_id2
*/

INSERT INTO friends (user_id1,user_id2) VALUES (
    user_id1,
    user_id2
);

/*
    Starting to Play a Game
    Inputs: @user_id, @game_id
*/

INSERT INTO plays (user_id, game_id) VALUES (
    @user_id,
    @game_id
);

/*
    Exiting From Game
    Inputs: @user_id, @game_id
*/

UPDATE plays 
SET end_date = NOW()
WHERE user_id = @user_id AND
      game_id = @game_id AND
      end_date is NULL
;

/*
    Send Game Invation
    Inputs: @sender_id, @reciever_id, @game_id
*/

INSERT INTO send_invitation  (sender_id, reciever_id, game_id) VALUES (
    @sender_id,
    @reciever_id,
    @game_id
);

/*
    Send Message
    Inputs: @sender_id, @reciever_id, @text
*/

INSERT INTO messages  (sender_id, reciever_id, text) VALUES (
    @sender_id,
    @reciever_id,
    @text
);

/*
    Unfriend
    Inputs: @user_id1, @user_id2
*/

DELETE FROM friends
WHERE (user_id1 = @user_id1 AND user_id2 = @user_id2) OR 
      (user_id1 = @user_id2 AND user_id2 = @user_id1)
;




/*
    Ban Users
    Inputs: @banned_id, @moderator_id, @discussion_id
*/

INSERT INTO banned_users (banned_user_id, moderator_id, discussion_id) VALUES (
    @banned_id,
    @moderator_id,
    @discussion_id
);

/*
    UnBan Users
    Inputs: @banned_id, @discussion_id
*/
DELETE FROM banned_users
WHERE discussion_id = @discussion_id, banned_id = @banned_id
;


/*
    Publish a Game
    Inputs: @game_name, @price, @img_location, @publisher_id, @description
*/

INSERT INTO games (game_name, price, img_location, description, publisher_id) VALUES (
    @game_name,
    @price,
    @img_location,
    @description,
    @publisher_id
);

/*
    Add Genres to Published Game
    Inputs: @game_id, @genre_name
*/

INSERT INTO game_genres (game_id, genre_name) VALUES (
    @game_id,
    @genre_name
);

CREATE VIEW most_sold AS
     SELECT game_id, COUNT(user_id) as units_sold
          FROM buy
          WHERE date >= (DATE(NOW()) - INTERVAL 7 DAY)
          ORDER BY units_sold DESC

;

CREATE VIEW trending AS
    SELECT game_name
    FROM most_sold m NATURAL JOIN games g
;
    
    /*buy b NATURAL JOIN games g on g.game_id = b.game_id
    WHERE b.date >= DATE(NOW()) - INTERVAL 7 DAY
;*/