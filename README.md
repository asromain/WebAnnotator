# User's manual


## How to install lampp server

A server for PHP development is needeed to run the application : https://www.apachefriends.org/fr/index.html 

Once the '.run' files has been downloaded, you have to execute the command line below : 

- $ sudo chmod 777 -R xampp-linux-x64-5.6.3-0-installer.run 
- $ ./xampp-linux-x64-5.6.3-0-installer.run

To launch lampp server :

- $ sudo su
- $ /opt/lampp/lampp start

To stop lampp : 

- $ /opt/lampp/lampp stop


## How to install the triplestore Redstore 

The first step is to have Homebrew installed as it is easier to install Redstore : http://brew.sh/linuxbrew/

Then, you have to execute the command line below in a terminal : 

- $ brew install redstore

If libxml2 is missing, paste the command line below :
 
- $ sudo apt-get install libxml2-dev

Finally it is possible to launch redstore :

$ bash
$ redstore

The interface of redstore is accessible at : http://localhost:8080/
It is possible to do some actions like queries on the RDF graph (insert, delete), download dump of data, and finally get a view of all the graphe « list named graphs »


## First installation of Web Annotator 

For the first time, you need to load a RDF graph named data-init.rdf, in the data directory.
This is a configuration file and it allows you to have a first account as administrator "MASTER" (all permissions).

It is possible to load it from http://localhost:8080/load or using the terminal : 

$ "curl -T PATH_TO_WEBSITE/data-init.rdf 'http://localhost:8080/data/database.rdf'


## Web application : 

### Inscription and connexion

To have a full acces system, an account is needed. For that click on inscription.
Length of pseudo has to be greater that 4 caracters.
Length of password has to be greater that 6 caracters.

A new user has a permission acces : R (for read only)


###Administration

Two users class exist : MASTER (administrator of administrator) and ARW (for admin read write).
The administration interface is accessible at the link "Administration" in the header

These administrators can modify the permission of other users. However, only the MASTER's can
delete a user. The MASTER can also download backups of the database.

Permissions : 

- MASTER : Administrator principal 
- ARW : Admin Read Write 
- RW : Read Write, can read all annotations and texts and edit/delete annotations
- R : Read only, can see the text and annotations

### Texts et annotations

- Les textes
To select a text, the user has to click on "Annoter". If his permission is RW, ARW or MASTER then
he can download his own text.

The user can fill "contenu du texte" or get the file from his computer clicking on "Parcourir".
Even, he can just drag and drop his file.

- Annotations
To annotate a text, the user has to go on "Annoter" and then select an existing text.
If his permission is RW, ARW or MASTER then he can annotate texts.

To annotate, the user select a fragment of text. Then a panel appears to annotate.
When the annotations is submitted, it appears next to the text.

The user (RW, ARW, MASTER) can edit annotations by clicking on the "..." button, or delete by
clicking on the cross