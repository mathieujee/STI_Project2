# STI_Projet_1

Authors: Mathieu Jee, Florent Nicolas Piller

Date: October 2018

## Setup the application with docker

1. Run `docker run -ti -v "$PWD/site":/usr/share/nginx/ -d -p 8080:80 --name sti_project --hostname sti arubinst/sti:project2018` in the root folder.

2. Run web and php services:

   `docker exec -u root sti_project service nginx start`

   `docker exec -u root sti_project service php5-fpm start`

3. You can now access the application wihtin your internet browser: 

   `CONTAINER_IP_ADRESS:8080`

