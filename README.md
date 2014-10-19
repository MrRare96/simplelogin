simplelogin
===========

Simple basic register/login script created using php, there are safer options out there I think, but this is just so simple :)
This is created by a first year IT student, who does not know anything about optimalization or security, so keep that in mind.
There could be some really bad pieces of code inhere, but I just do not really know the right way to do it. If you have suggestions I really would like to hear them. Feedback is always appreciated  :)

Also, about license and stuff:
I am to lazy to find a right way to put it atm, so:

Use it however you like! Its free for ever! If you want, you can put my name on your website, or give me a link to your website if you use my scripts, i would always appreciate that!

How it basicly works:

register(nickname, password, email) -> encrypted using password_hash function from password_compat lib (https://github.com/ircmaxell/password_compat)-> stored in database -> mail with link to verify.php?verifycode="random verification string" will be send to email. -> return to specified webpage

verification(verifycode) -> compare to verifycode in database -> change verified column to yes -> return to specified webpage

login(email/nickname, password) -> retrieve password using email/nickname for comparing -> compare password hash using password_verify function from password_compat lib(https://github.com/ircmaxell/password_compat) -> a session with nickname is created-> return to specified webpage (on this webpage you can get nicknamen from the session)

Thats it. There is a lot of room for improvements, and its still in development!

UPDATE 19-10-14:
+prepared sql statements added, works perfectly now, its much safer I guess. Also form's now need to be verified by the scripts before the form can send stuff to the scripts, which makes spamming harder using forms from outside. So: SAFER!


Future Features:
- Auto delete verify url thing after a specified amount of time. (Auto delete full account if not verified).
- Let the user be able to delete his account.
- Let the user be able to change his nickname and email.
- Let the user be able to create a profile (this will take a while though).
