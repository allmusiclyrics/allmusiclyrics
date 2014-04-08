allmusiclyrics.info website uses:
==============
- http://lessframework.com design
- http://thetvdb.com api
- http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js

Features:
===========
- user sign up with email verification 
- user subscription to shows 
- email daily new shows when there are songs for those episodes
- add shows/movies (no login required but indicated if user is logged in, admin must complete to combat spam)
- grab episodes of shows from thetvdb.com using api and save to database
- add songs to shows or movies by users or mass add by admins
- request songs from admins for episodes missing songs
- generate link to youtube search (with adf.ly advertising) for existing songs if no link present
- add links to songs
- generate adf.ly link for submitted links (user or admin), saves original link and can be changed for logged in or subscribed users

To set up:
==========
1. import database structure (allmusiclyrics.sql)
2. set database name, username, password, host other variables at the top of func.php

