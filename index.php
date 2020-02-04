<?php
require_once('header.php');
?>
<h1>TP ISEN</h1>
<h2>Scénario</h2>
<p>Vous êtes responsable technique d'une entreprise et vous devez mettre en place :</p>
<ul>
    <li>un cloud OpenStack</li>
    <li>un système de téléphonie Asterisk</li>
</ul>
<p>Pendant ce TP, vous allez donc découvrir :</p>
<ul>
    <li>Les joies du Cloud OpenStack</li>
    <li>Les API d'OpenStack -- même en ligne de commande</li>
    <li>Ansible -- un outil de déploiement et d'automatisation vraiment super</li>
    <li>Git -- un outil pour gerer votre code</li>
    <li>Asterisk -- un serveur de VoIP qui sait parler le protocole SIP</li>
</ul>


<!------------------------------------------------------
OpenStack
------------------------------------------------------->
<h2>OpenStack</h2>
    <p>OpenStack est un ensemble de modules logiciels ecris en Python. Il est en quelque sorte un Operating System pour infrastructure cloud. En plus, c'est un logiciel libre (Open Source / sous licence Apache 2). Il est donc possible d'installer OpenStack dans un datacenter privé (au sein de votre entreprise par exemple, on parle alors de Cloud Privé) ou d'utiliser un Cloud Public (OVH par exemple).</p>
    <p><a href="http://fr.wikipedia.org/wiki/OpenStack">http://fr.wikipedia.org/wiki/OpenStack</a></p>

    <p>Quel module d'OpenStack sert à gérer les machines virtuelles (le composant responsable de la partie "compute") ?</p>
    <input id="openstack" type="text" value=""/>
    <input id="openstack_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('openstack');"/>

<!------------------------------------------------------
Cloud OVH
------------------------------------------------------->
<h2>Utilisation d'un cloud public</h2>

<h3>Connexion a un serveur de rebond</h3>
    <p>Vous allez utiliser le cloud public d'OVH pour créer votre cloud. Un cloud sur un cloud (inception).</p>
    <p>Pour cela, vous pouvez vous connecter en SSH sur un serveur de rebond qui contient tous les acces et outils necessaires.</p>

    <p>Connectez vous au rebond (mot de passe <i>moutarde</i>) :</p>
    <pre>
# Remplacer x par un chiffre entre 1 et 6
ssh jump@jump<i>x</i>.arnaudmorin.fr
    </pre>
    <p>Bravo, vous voilà maintenant prêt à piloter OpenStack au travers des lignes de commande !</p>

<h3>Connexion au cloud a travers Horizon</h3>
    <p>Vous pouvez utiliser horizon pour vous connecter au cloud d'OVH :</p>
    <p><a href='https://horizon.cloud.ovh.net'>https://horizon.cloud.ovh.net</a></p>
    <p>Les logins et mot de passe sont sur votre serveur de rebond dans le fichier :</p>
    <pre>
cat .openrc
    </pre>
    <p>Prenez un peu de temps pour jouer avec</p>

<h3>CLI openstack: less fuck, more fun</h3>
    <p>Manipuler OpenStack au travers d'horizon, c'est bien, mais c'est ce n'est pas tres pratique pour automatiser. Pour cela, rien de tel que des scripts ou des logiciels.</p>
    <p>Heureusement, sur votre machine de rebond, un outil en CLI est deja preinstalle : <i>openstack</i></p>
    <p>Testez l'usage de cet outil (qu'on appelle aussi client OpenStack) :</p>
    <pre>
openstack help
    </pre>

    <p>Quelle commande allez vous utiliser pour lister les servers ?</p>
    <input id="servers" type="text" value=""/>
    <input id="servers_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('servers');"/>

<!------------------------------------------------------
Création d'une machine
------------------------------------------------------->
<h2>Création d'une instance (machine virtuelle)</h2>
    <p>Listez les images :</p>
    <pre>
openstack image list
    </pre>

    <p>Listez les flavors :</p>
    <pre>
openstack flavor list
    </pre>

    <p>Listez les paires de clef :</p>
    <pre>
openstack keypair list
    </pre>

    <p>Listez les reseaux :</p>
    <pre>
openstack network list
    </pre>

    <p>Creez une paire de clef et importez la dans votre openstack</p>
    <p>Donnez la commande que vous avez utilise pour faire cela</p>
    <textarea></textarea>

    <p>Creez aussi un petit fichier de configuration pour vous aider a vous connecter</p>
    <pre>
cat &lt;&lt;EOF &gt; ~/.ssh/config
host *
 identityfile <i>nom_de_votre_clef</i>
EOF</pre>
    <p>Remplacer <i>nom_de_votre_clef</i> par la clef que vous avez cree precedement</p>
    <p>Vous aurez peut etre besoin de mettre les bons droits (600) sur votre clef (avec chmod)</p>

    <p>Donnez la commande que vous allez utiliser pour booter une instance Ubuntu 16.04 sur le reseau <i>Ext-Net</i> :</p>
    <textarea></textarea>

    <p>Pour voir le statut de votre machine :</p>
    <pre>
openstack server show votre_machine
    </pre>

    <p>Vous pouvez aussi afficher les logs et la console avec les commandes suivantes :</p>
    <pre>
# Show console log and url
openstack console log show votre_machine
openstack console url show votre_machine
    </pre>
    <p>Mais vous ne pourrez pas vous connecter avec la console par ce biais car vous ne connaissez pas le mot de passe ! (et pour cause, il n'existe pas)</p>


<!------------------------------------------------------
Connexion à la machine
------------------------------------------------------->
<h2>Votre machine</h2>
    <p>Vous disposez donc maintenant d'une machine, vous allez l'administrer grâce à une console à distance</p>
    
    <p>Quel protocole va-t-on utiliser pour s'y connecter ?</p>
    <input id="connexion" type="text" value=""/>
    <input id="connexion_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('connexion');"/>

    <p>Généralement, avec ce protocol pour se connecter a une machine, il vous faut :</p>
    <ul>
        <li>Soit un login et un password</li>
        <li>Soit un login et une clef privée</li>
    </ul>

    <p>Essayez de vous connecter a la machine</p>

    <p>Une fois connecte, installez le paquet <i>python-simplejson</i>, qui nous servira plus tard :</p>
    <pre>
sudo apt update
sudo apt install python-simplejson
    </pre>

<!------------------------------------------------------
Ansible
------------------------------------------------------->
<h2>Ansible</h2>
<h3>Starting blocks</h3>
    <p>Ansible est un outil d'automatisation facilitant le déploiement, la gestion et la configuration des serveurs.</p>
    <p>D'autres outils similaires existent : puppet, chef, saltstack, cfengine, etc.</p>

    <p>Avant de procéder a l'installation d'un OpenStack avec Ansible, suivez le guide qui suit pour le prendre en main.</p>

    <p>Pour cela, depuis la machine de rebond (vous pouvez ouvrir un second terminal si ce n'est pas déjà fait), essayez cette commande en replaçant <i>ip_address</i> par l'adresse ip de votre machine (mais gardez bien la virgule après l'adresse IP !)</p>
    <pre>
ansible all -i <i>ip_address</i>, -m ping -u ubuntu
    </pre>

    <p>Vous devriez obtenir quelque chose du genre :</p>
    <pre>
167.114.232.60 | SUCCESS => {
    "changed": false, 
    "ping": "pong"
}
    </pre>

    <p>Vous venez de faire votre première action avec ansible, bravo !</p>
    <p>Sans le savoir, en activant le module <i>ping</i> d'ansible, vous avez effectué une connexion SSH sur le serveur et affiché <i>pong</i> (le module ping ne fait que ça).</p>

    <p>Vous pouvez aussi lancer des commandes avec le module shell :</p>
    <pre>
ansible all -i ip_address, -u ubuntu -m shell -a 'echo hello from $(hostname)'
    </pre>
    
<h3>Playbooks</h3>
    <p>Les playbooks sont des collections d'instructions que vous pouvez donner a ansible pour qu'il les execute sur les serveurs distants.</p>
    <p>Les playbooks peuvent être utilisés pour installer, configurer et maintenir la configuration du système distant.</p>

    <p>Clonez ce repo github :</p>
    <pre>
git clone https://github.com/arnaudmorin/isen-tp-ansible ansible
    </pre>

    <p>Essayez d'exécuter le playbook suivant sur votre serveur :</p>
    <pre>
ansible-playbook -i ip_address, ansible/wtf.yaml
    </pre>

    <p>Relancer la même commande</p>
    <p>Quelle différence voyez-vous ? Essayez d'expliquer pourquoi :</p>
    <textarea></textarea>

    <p>Essayez maintenant la même chose mais en ajoutant de la verbosité :</p>
    <pre>
ansible-playbook -i ip_address, -vvv ansible/wtf.yaml
    </pre>

    <p>Ouvrez le playbook wtf.yaml et essayez de comprendre un peu le fonctionnement.</p>
    <p>Quel rôle est appliqué au serveur ?</p>
    <input id="wtfrole" type="text" value=""/>
    <input id="wtfrole_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('wtfrole');"/>

    <p>Que fait le rôle ?</p>
    <textarea></textarea>

<h3>On your own</h3>
    <p>Maintenant que vous maitrisez ansible a la perfection, vous aller devoir ecrire votre propre playbook ansible pour pouvoir deployer une application web.</p>
    <p>L'application que vous devez deplyer est une application python: <a href='https://github.com/arnaudmorin/demo-flask'>https://github.com/arnaudmorin/demo-flask</a></p>
    <p>Elle ecoute par defaut sur le port 8080.</p>
    <p>Votre objectif : la deployer en tandem avec un server web en frontal (apache2 ou nginx, a votre guise) pour rediriger le port 80 vers le port 8080 de l'application</p>
    
    <pre>
          +------------------------------+             +-----------------------------+
          |                              |             |                             |
 port 80  |                              |   port 8080 |                             |
+-------->+       Apache ou Nginx        +------------>+          demo-flask         |
          |                              |             |                             |
          |                              |             |                             |
          +------------------------------+             +-----------------------------+
</pre>

<h3>Si vous avez fini et que vous etes sur</h3>
    <p>Vous pouvez maintenant supprimer votre machine, mais pas d'inquietudes, vous allez en creer d'autres !</p>

<!------------------------------------------------------
OpenStack
------------------------------------------------------->
<h2>OpenStack</h2>
<h3>Introduction</h3>
    <p>Avant de commencer: <a href='https://www.arnaudmorin.fr/p10/slides/#/'>Slides d'introduction</a> </p>
    <p>L'installation d'OpenStack est fastidieuse, peut etre longue et necessite parfois de debugger assez longtemps avant d'obtenir quelque chose de fonctionnel.</p>
    <p>Pour vous aider, vous aller utiliser des playbooks ansible deja existants, qui devraient presque tout faire sauf le cafe (que vous payerez au prof par contre).</p>
    <!--<p>Vous allez vous separer en plusieurs equipes, chaque equipe va deployer un OpenStack, la premiere equipe a reussir aura le droit a un bonbon (youpi!).</p>-->

<h3>Arhictecture</h3>
    <p>L'architecture de ce que vous allez deployer est la suivante :</p>
<pre>
             ssh       +----------+
you     +----------->  | deployer |
                       +----------+
                            |
                        ansible (ssh)
                            |

+----------+   +----------+   +----------+         +---+
|  rabbit  |   |   nova   |   |  neutron | <-----> | V |
+----------+   +----------+   +----------+         | R |
                                                   | a |
+----------+   +----------+   +----------+         | c |
|  mysql   |   |  glance  |   |  compute | <-----> | k |
+----------+   +----------+   +----------+         +---+
                                                     |
+----------+   +----------+   +----------+           |
|  horizon |   | keystone |   | designate|           |
+----------+   +----------+   +----------+           |
                                          Instances public access
             |                             with /28 network block
       HTTP API access                               |
             |                                       |
             +----------+----------------------------+
                        |
                    Internet

</pre>

    <p>L'objectif est de deployer un petit OpenStack complet et fonctionnel.</p>
    <p>La machine deployer vous servira d'admin / rebond. Ansible sera installe dessus.<p>
    <p>Chaque machine aura une adresse IP publique et sera donc accessible depuis le web.</p>
    <p>Chaque machine sera aussi interconnectee aux autres par un reseau prive : management.</p>
    <p>Neutron et Compute auront en plus un autre reseau : public. Ce reseau leur permettra de donner acces a internet a vos futurs instances (au travers du vRack d'OVH).</p>
    <p>Dans le vRack d'OVH, des adresses IP publiques seront disponibles et vous serons distribuer au moment venu.</p>
    <p></p>

<h3>Installation</h3>
    <p>Clonez ce repo :</p>
    <pre>
git clone https://github.com/arnaudmorin/bootstrap-openstack
    </pre>

    <p>Puis demarrer l'installation</p>
    <pre>
cd bootstrap-openstack
./bootstrap.sh
    </pre>

    <p>Suivez le tutoriel sur le github <a href='https://github.com/arnaudmorin/bootstrap-openstack'>https://github.com/arnaudmorin/bootstrap-openstack</a></p>
    <p>Vous pouvez passer la partie "Prepare your environment"</p>

    <p>Si vous avez termine, vous devez avoir un OpenStack fonctionnel.</p>
    <p>Prouvez le en me montrant votre horizon et le boot d'une machine Ubutun 16.04 avec une adresse IP publique (sur le reseau provider).</p>
    <p>Gardez cette machine Ubuntu 16.04, elle vous servira pour la suite</p>

<!------------------------------------------------------
Asterisk
------------------------------------------------------->
<h2>Asterisk</h2>
<h3>Définition Wikipedia</h3>
    <p><a href='http://fr.wikipedia.org/wiki/Asterisk_(logiciel)' target='_blank'>http://fr.wikipedia.org/wiki/Asterisk_(logiciel)</a></p>
    <p>Asterisk est un autocommutateur téléphonique privé, abréviation :</p>
    <input id="asterisk" type="text" value=""/>
    <input id="asterisk_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('asterisk');"/>

<h3>Installation</h3>
    <p>Nous allons donc installer <i>Asterisk</i> avec <i>Ansible</i> sur le serveur que vous avez booter sur votre OpenStack ! Pour cela, il vous suffit d'appliquer le playbook suivant à votre serveur :</p>
    <pre>
ansible-playbook -i ip_address, ansible/asterisk.yaml
    </pre>

    <p>Expliquer ce que fait le playbook :</p>
    <textarea></textarea>

    <p>Vérifiez maintenant qu'asterisk est bien installé et démarré sur votre serveur</p>
    <p>Quelle commande vous permet de vérifier que le service asterisk est démarré ?</p>
    <input id="asterisk2" type="text" value=""/>
    <input id="asterisk2_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('asterisk2');"/>

    <p>Quelle commande utilisez-vous pour vérifier qu'Asterisk écoute bien sur le port 5060 (juste la commande, sans les options) ?</p>
    <input id="laputen" type="text" value=""/>
    <input id="laputen_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('laputen');"/>
    
<h3>Quelques commandes utiles pour asterisk</h3>
    <p>Pour se connecter à la console d'asterisk :</p>
    <pre>
sudo rasterisk -vvvvvvvvvvvvvvvv
    </pre>
    
    <p>Pour recharger la configuration depuis la console d'asterisk :</p>
    <pre>
*CLI> core reload
    </pre>

    <p>Pour quitter la console d'asterisk :</p>
    <pre>
*CLI> exit
    </pre>

    <p>Les fichiers de configuration sont dans /etc/asterisk/</p>
    <pre>
cd /etc/asterisk/
ls
    </pre>

<!------------------------------------------------------
Premier appel
------------------------------------------------------->
<h2>Client SIP</h2>
    <p>Nous allons utiliser <a href='http://letmegooglethat.com/?q=linphone' target='_blank'>Linphone</a></p>
    <pre>
Login : 8000
Mot de passe : moutarde
Serveur SIP : ip de votre serveur
    </pre>
        
    <p><img src="images/linphone1.png"/></p>
    <!-- <p><img src="images/linphone2.png"/></p> -->
    
    <p>Quel adresse SIP appeler pour tester le bon fonctionnement de notre serveur ? (indice : regardez dans les fichiers de configuration d'asterisk)</p>
    <input id="firstcall" type="text" value=""/>
    <input id="firstcall_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('firstcall');"/>

<!------------------------------------------------------
Wireshark
------------------------------------------------------->
<h2>Wireshark</h2>
    <p><a href='http://fr.wikipedia.org/wiki/Wireshark' target='_blank'>http://fr.wikipedia.org/wiki/Wireshark</a></p>
    <p>Utiliser wireshark pour capturer une trace d'un appel entre votre client et le serveur (capture côté client).</p>
    <p>Utiliser tshark (wireshark en ligne de commande) pour capturer la trace côté serveur.</p>
    
    <p>Quel filtre applique-t-on dans wireshark pour n'afficher que les paquets SIP ?</p>
    <input id="filtrews" type="text" value=""/>
    <input id="filtrews_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('filtrews');"/>
    
    <p>Quelle réponse reçoit-on à notre première requête INVITE ?</p>
    <input id="1reponse" type="text" value=""/>
    <input id="1reponse_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('1reponse');"/>
    
    <p>Capturer des traces pour :</p>
    <ul>
        <li>Un enregistrement</li>
        <li>Un appel vers le serveur</li>
        <li>Un désenregistrement</li>
    </ul>
    
    <p>Vérifier la cohérence des traces envers le cours.</p>
    <p>Identifier la/les transaction(s).</p>
    <p>Identifier le/les dialogue(s).</p>
    
    <p>Pendant l'enregistrement, combien de requête REGISTER observez vous ?</p>
    <input id="register" type="text" value=""/>
    <input id="register_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('register');"/>
    <p>Essayer d'expliquer pourquoi</p>
    <textarea></textarea>
    
<!------------------------------------------------------
RTP TC
------------------------------------------------------->
<h2>Tests RTP, codecs, débits et bande passante</h2>
    <p>Quel est le débit moyen théorique pour le codec GSM Full Rate (en kbit/s) :</p>
    <input id="debitgsm" type="text" value=""/>
    <input id="debitgsm_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('debitgsm');"/>
    
    <p>Et pour PCMA (G711 A-law) :</p>
    <input id="debitpcma" type="text" value=""/>
    <input id="debitpcma_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('debitpcma');"/>
    
    <p>Capturer des traces pour :</p>
    <ul>
        <li>Un appel vers votre kiosque de démo en utilisant le codec GSM</li>
        <li>Un appel vers votre kiosque de démo en utilisant le codec PCMA</li>
    </ul>
    
    <p>Télécharger sur votre serveur les deux scripts suivants :</p>
    <ul>
        <li><a href='scripts/iptables_arnaud.sh' target="_blank">iptables_arnaud.sh</a></li>
        <li><a href='scripts/tc_arnaud.sh' target="_blank">tc_arnaud.sh</a></li>
    </ul>
    
    <p>Executer les deux scripts</p>
    <p>Le script <strong>iptables</strong> va permettre de tagguer les paquets RTP afin de leur appliquer une règle de filtrage particulière. Il ne prend aucun paramètre. Exemple :</p>
    <pre>
sudo bash iptables_arnaud.sh
    </pre>
    
    <p>Le script <strong>tc</strong> (comme traffic control) ordonne au noyau linux d'écrêter le débit sortant sur les paquets IP filtrés par iptables. Pour utiliser ce script, vous devez lui passer le débit maximum comme paramètre sur la ligne de commande. Exemple :</p>
    <pre>
sudo bash tc_arnaud.sh 100kbit
    </pre>
    
    <p>Pour chaque codec, commencer les tests à 100kbit et descendre de 10 en 10. Attention à bien désactiver tous les autres codecs dans linphone !</p>
    <p>À partir de combien de kbit/s, le son commence-t-il a être mauvais pour le codec GSM ?</p>
    <input id="gsmtc" type="text" value=""/>
    <input id="gsmtc_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('gsmtc');"/>
    <p>À partir de combien de kbit/s, le son commence-t-il a être mauvais pour le codec PCMA ?</p>
    <input id="pcmatc" type="text" value=""/>
    <input id="pcmatc_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('pcmatc');"/>
    
    <p>Essayer d'expliquer pourquoi le son est mauvais avant d'avoir atteint le débit du codec</p>
    <textarea></textarea>

<!------------------------------------------------------
Asterisk plus loin
------------------------------------------------------->
<h2>Transfert, boite vocale, conférences avec Asterisk</h2>
    <p>Configurer Asterisk pour renvoyer les appels vers une boite vocale si la personne est absente ou occupée.</p>
        <p>Quelle fonction d'Asterisk avez-vous utilisée ?</p>
        <input id="astvocal" type="text" value=""/>
        <input id="astvocal_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('astvocal');"/>

    <p>Configurer Asterisk pour permettre de consulter les messages de la boite vocale en composant le 888.</p>
        <p>Quelle fonction d'Asterisk avez-vous utilisée ?</p>
        <input id="astvocal2" type="text" value=""/>
        <input id="astvocal2_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('astvocal2');"/>
    
    <p>Configurer Asterisk pour permettre aux clients de rejoindre un pont téléphonique.</p>
        <p>Quelle fonction d'Asterisk avez-vous utilisée ?</p>
        <input id="astpont" type="text" value=""/>
        <input id="astpont_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('astpont');"/>
    
    <p>Configurer Asterisk pour transférer un appel en cours vers un autre numéro.</p>
        <p>Quelles options avez-vous ajoutées pour transférer un appel ?</p>
        <input id="asttransf" type="text" value=""/>
        <input id="asttransf_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('asttransf');"/>

<!-- extensions.conf

[from-internal]
exten => _800X,1,Dial(SIP/${EXTEN},10,tT)
exten => _800X,n,VoiceMail(${EXTEN}@internal-vm)

exten => DEMO,1,Answer()
exten => DEMO,n,Playback(demo-echotest)
exten => DEMO,n,Record(tmp.gsm,0,10)
exten => DEMO,n,Playback(beep)
exten => DEMO,n,Playback(tmp)
exten => DEMO,n,Playback(demo-echodone)
exten => DEMO,n,Playback(demo-thanks)
exten => DEMO,n,hangup()

exten => 888,1,VoiceMailMain(${CALLERID(num)}@internal-vm)

exten => _900X,1,ConfBridge(${EXTEN})

-->

<!-- voicemail.conf

[internal-vm]
8000 => 1234,Chantal
8001 => 1234,Martine
8002 => 1234,Gertrude

-->

<!------------------------------------------------------
Vrai téléphone
------------------------------------------------------->

<h2>Utilisation d'un téléphone SIP</h2>
    
    <p><a href='ftp://downloads.aastra.com/Downloads/User_Guides/6755i_41-001386-00_REV01_UG_E_06_2013.pdf' target="_blank">ftp://downloads.aastra.com/Downloads/User_Guides/6755i_41-001386-00_REV01_UG_E_06_2013.pdf</a></p>

<!--

Mot de passe par défaut administrateur du téléphone : 22222
Reset du télépone possible en appuyant simultanément sur 1 et # au boot du téléphone

-->

<h2>Ajout d'un trunk sip pour avoir une interconnexion avec le reseau telephonique commute (RTC)</h2>

A configurer dans sip.conf


<h2>Interconnexion de deux serveurs</h2>

Trouver comment interconnecter deux serveurs asterisk.

<?php
require_once('footer.php');
?>

