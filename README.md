# MASTER

--Setting up a Raspberry Pi with PHP 7.1--

1) I first installed Raspbian Stretch on the Raspberry Pi 3 in headless mode

2) I ssh'd into into it adn updated and upgraded the software ("sudo apt-get update && sudo apt-get upgrade -y")

3) After that I used the groupadd and usermod commands. ("sudo groupadd www-data") then ("sudo usermod -a -G www-data www-data")

4) PHP 7.1 is not directly available on raspbian stretch, so you need to add a repository which contains it. You do this by editing the souces list ("sudo nano /etc/apt/sources.list")

5) Add this source on a new line, underneath the one already there. ("deb http://raspbian.raspberrypi.org/raspbian/ buster main contrib non-free rpi")

5b) Remove any previous installations of php, if you have them using the command ("sudo apt-get remove '^php.*'") 

6) Now install Apache2 ("sudo apt-get install -t stretch apache2 -y")

7) Now install PHP 7.1 using ("sudo apt-get install php7.1-fpm php7.1-cli")

8) Install json and curl too using ("sudo apt-get install php-curl") and ("sudo apt-get install php-7.1-json")

9) Install MySQL using ("sudo apt-get install -t stretch mysql-server mysql-client -y")

10) Reboot your raspberry pi using ("sudo reboot").

11) Test to see if your server is working by going to your html directory using ("sudo nano /var/www/html/info.php")

11b) Paste this in ("<?php echo "server is online </br>"; phpinfo(); ?>") and save.


12) On your PC find the IP address of the raspberry Pi and navigate to this address ("http://[IP-eures-Pis]/info.php")

13) This should display all the PHP modules loaded. Check to see if curl is on that list and if json is there too.

14) You paste your website folder in /var/www/html/, you can access this via ("cd /var/www/html/").

-- To change the root folder of Apache 2 and make it go live--

1) Navigate to Apache's root directory using ("cd /etc/apache2/sites-available")

2) Open the 0 default file using ("sudo nano 000-default.conf")

3) Edit the document root on the DocumentRoot /path/to/myProject as desired

4) Go on your router's setting page in a browser. This is usually ("192.168.1.1")

5) Enable port forwarding. Enter the IP adress of the Raspberry Pi and forward from port 80.

5b) This is sometimes bloacked by the ISP, so look up how to change th port on a raspberry pi.

6) Make an account on a dynamic dns website such as NoIP ("https://www.noip.com/free")

7) Once you have picked your URL, you can use this to access the website.
