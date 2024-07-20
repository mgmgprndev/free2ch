# free2ch
Free2Ch is Inspired by Anonymous BBS website 2ch.sc and open2ch.net and this  project is intent for make it more freedom and open source for everyone.

# Disclaimer
Please note, please use this at your own risk and There are big differences between free2ch.net and this repo. <br>
Well If I got an time for update, I will sync both :) (no eta, no guarantee)

# This need illuminate/database (ORM)!
`composer require illuminate/database`

# Known Issues
## Hi! The time using is not my timezone It is UTC! 
Please make sure your timezone of server is same as your timezone. <br>
`date` command will give you current time with timezeon. <br>
And try update it using `timedatectl set-timezone`. <br>
Also please also modify the `php.ini` in the /etc/php/{version}/fpm/php.ini (for me, the file here!). <br.
and modify date.timezone. I think it is commented out, so please remove comment out to make it work.
