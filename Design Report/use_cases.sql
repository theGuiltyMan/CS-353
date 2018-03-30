/*
    Login Screen
    Inputs: @email_username, @password
*/

SELECT user_id
FROM users
WHERE email = @email_username OR user_name = email_username AND pssword = @password
;

/*
    Sign-up Screen
    Inputs: @user_name, @password, @email
*/

INSERT INTO users (user_name, pssword, email) VALUES (
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