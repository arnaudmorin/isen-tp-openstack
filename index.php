<?php
require_once('header.php');
?>
<h1>TP ISEN 2013</h1>
<h2>Scénario</h2>
<p>Vous êtes responsable technique d'une entreprise et vous devez mettre en place un système téléphonique interne.</p>
<p>Vous allez devoir installer :</p>
<ul>
    <li>Un serveur Ubuntu 12.04 32b dans le cloud</li>
    <li>Configurer ce serveur pour faire office de serveur VoIP SIP</li>
</ul>



<!------------------------------------------------------
Amazon
------------------------------------------------------->
<h2>Amazon Web Services</h2>
    <p>Amazon Web Services (AWS) est une collection de services informatiques distants (aussi appelés Services Web) fournis via internet par Amazon.com.</p>
    <p>Lancés en juillet 2002, Amazon Web Services fournit des services en lignes à d'autres sites internet ou applications clientes. La plupart d'entre eux ne sont pas directement exposés à l'utilisateur final, mais offrent des fonctionnalités que d'autres développeurs peuvent utiliser. En juin 2007, Amazon revendiquait plus de 330 000 développeurs ayant souscrit pour l'utilisation des Amazon Web Services2.</p>
    <p>Les offres Amazon Web Services sont accessibles en HTTP, sur architecture REST et par le protocole SOAP. Tout est facturé en fonction de l'utilisation, avec la valeur exacte variant de services en services, ou selon la zone géographique d'appel.</p>
    <p><a href='http://fr.wikipedia.org/wiki/Amazon_Web_Services' target='_blank'></a></p>
    <p>Liste des produits AWS : <a href="http://aws.amazon.com/fr/products/">http://aws.amazon.com/fr/products/</a></p>

    <p>Quel service d'Amazon pourrait-on utiliser pour héberger notre serveur Ubuntu 12.04 ?</p>
    <input id="amazon" type="text" value=""/>
    <input id="amazon_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('amazon');"/>

<!------------------------------------------------------
OpenStack
------------------------------------------------------->
<h2>OpenStack</h2>
    <p>OpenStack est un ensemble de modules logiciels Open Source entièrement compatibles avec les API d'Amazon. Il est en quelque sorte la version libre d'AWS. Il est donc possible d'installer OpenStack dans un datacenter privé (au sein de votre entreprise par exemple, on parle alors de Cloud Privé) ou d'utiliser un Cloud Public (OVH par exemple).</p>
    <p><a href="http://fr.wikipedia.org/wiki/OpenStack">http://fr.wikipedia.org/wiki/OpenStack</a></p>

    <p>Quel module d'OpenStack sert à gérer les machines virtuelles (le composant responsable de la partie "compute") ?</p>
    <input id="openstack" type="text" value=""/>
    <input id="openstack_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('openstack');"/>

<!------------------------------------------------------
Connexion au rebond
------------------------------------------------------->
<h2>Utilisation d'un cloud public</h2>
    <p>Vous allez utiliser le cloud public d'OVH pour créer votre machine virtuelle.</p>
    <p>Pour cela, vous avez deux possibilités : utiliser l'interface web <i>horizon</i> d'openstack (<a href='https://horizon.cloud.ovh.net/'>https://horizon.cloud.ovh.net/</a>) ou bien la ligne de commande. Pour des raisons de simplicités évidentes, nous allons utiliser la ligne de commande !</p>
    <p>Pour cela, vous allez devoir vous connecter en SSH à une machine de rebond (jumphost), puis entrer dans un conteneur privé qui vous sera dédié.</p>
    <p>Cette machine vous permet de <i>controller</i> le cloud a travers des outils en lignes de commande, ce qui est utile lorsque vous avez a automatiser ou répéter des taches.</p>
    <p>Connectez vous au rebond (mot de passe <i>moutarde</i>) :</p>
    <pre>
ssh bounce@XXX.XXX.XXX.XXX
    </pre>
    <p>Vous êtes sur le rebond, connectez vous sur votre conteneur privé (mot de passe <i>moutarde</i>) :</p>
    <pre>
ssh -p 22XXX root@localhost
    </pre>
    <p>Bravo, vous voilà maintenant prêt à piloter OpenStack au travers des lignes de commande !</p>
    <p>Pour info, vous trouverez les logins et mots de passes pour vous connecter à horizon dans le fichier openrc de votre espace privé</p>
    <pre>
cat openrc
    </pre>

    <p>Pour continuer le TP vous aurez besoin d'avoir acces aux variables qui sont dans ce fichier. Pour y avoir acces automatiquement sur votre ligne de commande, il vous faut <i>sourcer</i> le fichier :</p>
    <pre>
source openrc
    </pre>

<!------------------------------------------------------
Création d'une clef SSH
------------------------------------------------------->
<h2>Création d'un clef SSH</h2>
    <p>Pour vous connecter aux instances (machines virtuelles) du cloud, on utilise généralement des clefs SSH au lieu des mots de passes :</p>
    <ul>
        <li>c'est plus sécurisé</li>
        <li>c'est utilisable au travers de scripts, et donc automatisable</li>
    </ul>
    <p>Vous allez devoir créer une clef SSH, puis l'uploader sur le cloud.</p>

    <p>Quelle commande allez vous utiliser pour générer votre clef ssh ?</p>
    <input id="clef_ssh" type="text" value=""/>
    <input id="clef_ssh_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('clef_ssh');"/>

    <p>Importez maintenant votre clef SSH dans le cloud : </p>
    <pre>
nova keypair-add --pub_key ~/.ssh/id_rsa.pub clef
    </pre>

    <p>Listez les clefs et vérifiez que la clef <i>clef</i> est bien présente : </p>
    <pre>
nova keypair-list
    </pre>

<!------------------------------------------------------
Création d'une machine
------------------------------------------------------->
<h2>Création de la machine virtuelle (instance)</h2>
    <p>Listez les images :</p>
    <pre>
nova image-list
    </pre>

    <p>Listez les flavors :</p>
    <pre>
nova flavor-list
    </pre>

    <p>Enfin bootez une image Ubuntu 12.04 de type vps-ssd-1 :</p>
    <pre>
nova boot --image "Ubuntu 12.04" --flavor "vps-ssd-1" --key-name "clef" nomdelamachinequevousvoulez
    </pre>

<!------------------------------------------------------
Connexion à la machine
------------------------------------------------------->
<h2>Votre machine</h2>
    <p>Vous disposez donc maintenant d'une machine Ubuntu 12.04, vous allez l'administrer grâce à une console à distance</p>
    
    <p>Quel protocole va-t-on utiliser pour s'y connecter ?</p>
    <input id="connexion" type="text" value=""/>
    <input id="connexion_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('connexion');"/>

    <p>Pour vous le statut de votre machine :</p>
    <pre>
nova show nomdelamachinequevousvoulez
    </pre>

    <p>Allez-y, connectez vous à votre machine!</p>

    <p>De combien d'adresses IP dispose votre machine ?</p>
    <input id="ip" type="text" value=""/>
    <input id="ip_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('ip');"/>

    <p>Notez la ou les adresses IP que vous avez trouvé : </p>
    <textarea></textarea>

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
    <p>Normalement il faudrait le <strong>compiler</strong>. Je l'ai fait pour vous ! Vous n'avez donc qu'à installer la version packagée disponible depuis un launchpad (<a href='https://launchpad.net/~emerginovteam/+archive/ubuntu/emerginov' target='_blank'>https://launchpad.net/~emerginovteam/+archive/ubuntu/emerginov</a>)</p>
    
    <p>Pour cela, ajouter le dépôt :</p>
    <pre>
sudo apt-get install python-software-properties
sudo add-apt-repository ppa:emerginovteam/emerginov
sudo apt-get update
    </pre>
    
    <p>Quelle commande allez vous utiliser pour installer le paquet asterisk ?</p>
    <textarea></textarea>
    
<h3>Quelques commandes</h3>
    <p>Si besoin, pour démarrer asterisk :</p>
    <pre>
sudo service asterisk start
    </pre>
    
    <p>A la place de start, quelle action peut-on utiliser pour vérifier si asterisk est démarré ?</p>
    <input id="asterisk2" type="text" value=""/>
    <input id="asterisk2_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('asterisk2');"/>
    
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

<h2>Configuration</h2>
    <p>Création du lien symbolique pour les fichiers de démo : </p>
    <pre>
sudo ln -s /usr/share/asterisk/sounds/en /var/lib/asterisk/sounds/
    </pre>

    <p>Les fichiers de configuration sont dans /etc/asterisk/</p>
    <pre>
cd /etc/asterisk/
    </pre>
    
    Copier coller les fichiers :
    <h3>sip.conf</h3>
        <pre>
[general]
context=public
allowoverlap=no
udpbindaddr=0.0.0.0
tcpenable=no
tcpbindaddr=0.0.0.0
transport=udp,ws
srvlookup=no
realm=<strong>adresse ip de votre serveur</strong>
externip=<strong>adresse ip de votre serveur</strong>
localnet=<strong>10.0.0.0</strong>/255.255.255.0

[8000]
secret=moutarde
context=from-internal
host=dynamic
trustrpid=yes
sendrpid=no
type=friend
qualify=yes
qualifyfreq=600
transport=udp,ws
;encryption=yes
dial=SIP/8000
callerid=Chantal <8000>
callcounter=yes
;avpf=yes
icesupport=yes
directmedia=no

[8001]
secret=moutarde
context=from-internal
host=dynamic
trustrpid=yes
sendrpid=no
type=friend
qualify=yes
qualifyfreq=600
transport=udp,ws
;encryption=yes
dial=SIP/8001
callerid=Martine <8001>
callcounter=yes
;avpf=yes
icesupport=yes
directmedia=no

[8002]
secret=moutarde
context=from-internal
host=dynamic
trustrpid=yes
sendrpid=no
type=friend
qualify=yes
qualifyfreq=600
transport=udp,ws
;encryption=yes
dial=SIP/8002
callerid=Gertrude <8002>
callcounter=yes
;avpf=yes
icesupport=yes
directmedia=no
        </pre>
        
    <h3>rtp.conf</h3>
        <pre>
[general]
rtpstart=10000
rtpend=20000
icesupport=yes
stunaddr=stun.ekiga.net
        </pre>
        
    <h3>http.conf</h3>
        <pre>
[general]
enabled=yes
bindaddr=0.0.0.0
        </pre>
    
    <h3>extensions.conf</h3>
        <pre>
[general]
static=yes
writeprotect=no
clearglobalvars=no

[from-internal]
exten => _800X,1,Dial(SIP/${EXTEN})
exten => _800X,n,hangup()

exten => DEMO,1,Answer()
exten => DEMO,n,Playback(demo-echotest)
exten => DEMO,n,Record(tmp.gsm,0,10)
exten => DEMO,n,Playback(beep)
exten => DEMO,n,Playback(tmp)
exten => DEMO,n,Playback(demo-echodone)
exten => DEMO,n,Playback(demo-thanks)
exten => DEMO,n,hangup()
        </pre>

    <p>Redémarrer asterisk (ou recharger la configuration) après chaque modification des fichiers.</p>
    
    <p>Quelle commande utiliser pour vérifier qu'Asterisk écoute bien sur les ports 5060 et 8088 ?</p>
    <input id="laputen" type="text" value=""/>
    <input id="laputen_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('laputen');"/>


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
    <p><img src="images/linphone2.png"/></p>
    
    <p>Quel adresse SIP appeler pour tester le bon fonctionnement de notre serveur ?</p>
    <input id="firstcall" type="text" value=""/>
    <input id="firstcall_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('firstcall');"/>

<!------------------------------------------------------
Wireshark
------------------------------------------------------->
<h2>Wireshark</h2>
    <p><a href='http://fr.wikipedia.org/wiki/Wireshark' target='_blank'>http://fr.wikipedia.org/wiki/Wireshark</a></p>
    <p>Utiliser Wireshark pour capturer une trace d'un appel entre votre client et le serveur (capture côté client).</p>
    <p>Utiliser tshark (wireshark en ligne de commande) pour capturer la trace côté serveur.</p>
    <p>Tester aussi en désactivant STUN dans le client Linphone.</p>
    
    <p>Quel filtre applique-t-on dans wireshark pour n'afficher que les paquets SIP ?</p>
    <input id="filtrews" type="text" value=""/>
    <input id="filtrews_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('filtrews');"/>
    
    <p>Quelle réponse reçoit-on à notre première requête INVITE ?</p>
    <input id="1reponse" type="text" value=""/>
    <input id="1reponse_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('1reponse');"/>
    
    <p>Quelle autre réponse pourrait-on attendre si l'authentification était gérée par un proxy ?</p>
    <input id="2reponse" type="text" value=""/>
    <input id="2reponse_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('2reponse');"/>
    
    <p>Les requêtes STUN apparaissent-elles avant ou après les requêtes SIP ?</p>
    <input id="stun" type="text" value=""/>
    <input id="stun_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('stun');"/>
    
    <p>Capturer des traces pour :</p>
    <ul>
        <li>Un REGISTER</li>
        <li>Un appel entre deux personnes</li>
    </ul>
    
    <p>Vérifier la cohérence des traces envers le cours.</p>
    <p>Identifier la/les transaction(s).</p>
    <p>Identifier le/les dialogue(s).</p>
    
    <p>Combien de requête REGISTER observez vous ?</p>
    <input id="register" type="text" value=""/>
    <input id="register_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('register');"/>
    <p>Essayer d'expliquer pourquoi</p>
    <textarea></textarea>
    
    <p>Combien de dialogues différents obtenez vous en tout (enregistrement, appel, raccrochage, ne pas tenir compte des requêtes OPTIONS) ?</p>
    <input id="dialogue" type="text" value=""/>
    <input id="dialogue_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('dialogue');"/>
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

    <p>Le téléphone ne sait pas utiliser le protocole STUN, que peut-on rajouter dans la configuration de l'user sur Asterisk pour que les paquets RTP traversent quand même le NAT ?</p>
    <textarea></textarea>

<!--

Mot de passe par défaut administrateur du téléphone : 22222
Reset du télépone possible en appuyant simultanément sur 1 et # au boot du téléphone
    
<!------------------------------------------------------
WebRTC
------------------------------------------------------->
<!--
<h2>WebRTC C'est quoi ?</h2>
    <p><a href='http://fr.wikipedia.org/wiki/WebRTC' target='_blank'>http://fr.wikipedia.org/wiki/WebRTC</a></p>
    
    <p>Asterisk est compatible avec WebRTC, il doit donc être possible d'établir une communication entre un client SIP et un client sur navigateur web classique.</p>
    
    <p>Commencer par installer apache, subversion</p>
    <pre>
sudo apt-get install apache subversion
    </pre>
    
    <h3>SIPml5</h3>
        <p>Récupérer le code source :</p>
        <pre>
cd /var/www/
sudo svn checkout http://sipml5.googlecode.com/svn/trunk/ /var/www/myphone
sudo chown -R www-data:www-data /var/www/myphone/
        </pre>
        
        <p>Désactiver les GA à la fin du fichier call.htm :</p>
        <pre>
cd /var/www/myphone/
sudo vim call.htm
        </pre>
        
        <p>Dans <strong>sip.conf</strong>, décommenter pour chaque utilisateur :</p>
        <pre>
avpf=yes
        </pre>
    
    <h3>Configuration de SIPml5</h3>
        <p><img src='images/webrtc1.png'</img></p>
        <p><img src='images/webrtcexpert.png'</img></p>
    
    <p>Que signifie ws dans ws:// ?</p>
    <input id="webrtc" type="text" value=""/>
    <input id="webrtc_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('webrtc');"/>

-->
<?php
require_once('footer.php');
?>

