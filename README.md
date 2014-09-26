simplelogin
===========

Simple basic register/login script created using php, there are safer options out there I think, but this is just so simple :)
This is created by a first year IT student, who does not know anything about optimalization or security, so keep that in mind.
There could be some really bad pieces of code inhere, but I just do not really know the right way to do it. If you have suggestions I really would like to hear them. Feedback is always appreciated  :)

Also, about license and stuff:
I am to lazy to find a right way to put it atm, so:

Use it however you like! Its free for ever! If you want, you can put my name on your website, or give me a link to your website if you use my scripts, i would always appreciate that!

How it basicly works:

register(nickname, password, email) -> encrypted using password as key -> random verification string created -> stored in database -> mail with link to verify.php?verifycode="random verification string" will be send to email. -> return to specified webpage

verification(verifycode) -> compare to verifycode in database -> change verified column to yes -> return to specified webpage

login(email/nickname, password) -> retrieve password using email/nickname for comparing -> decrypt password with password retrieved from user -> compare password retrieved from user with password retrieved from db -> a session with nickname is created-> return to specified webpage (on this webpage you can get nicknamen from the session)

Thats it. There is a lot of room for improvements, and its still in development!
