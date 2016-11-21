<?php
require_once('header.php');
?>
<h1>TP ISEN 2016/2017</h1>
<h2>Scénario</h2>
<p>Vous êtes responsable technique d'une entreprise et vous devez mettre en place un système de téléphonie interne.</p>
<p>Vous allez devoir installer :</p>
<ul>
    <li>Un serveur Ubuntu 16.04 dans le cloud</li>
    <li>Configurer ce serveur pour faire office de serveur VoIP SIP</li>
</ul>
<p>Pendant ce TP, vous allez donc découvrir :</p>
<ul>
    <li>Les joies du Cloud OpenStack</li>
    <li>Les API d'OpenStack -- même en ligne de commande</li>
    <li>Ansible -- un outil de déploiement et d'automatisation vraiment super</li>
    <li>Asterisk -- un serveur de VoIP qui sait parler le protocole SIP</li>
</ul>


<!------------------------------------------------------
Amazon
------------------------------------------------------->
<h2>Amazon Web Services</h2>
    <p>Amazon Web Services (AWS) est une collection de services informatiques distants (aussi appelés Services Web) fournis via internet par Amazon.com.</p>
    <p>Lancés en juillet 2002, Amazon Web Services fournit des services en lignes à d'autres sites internet ou applications clientes. La plupart d'entre eux ne sont pas directement exposés à l'utilisateur final, mais offrent des fonctionnalités que d'autres développeurs peuvent utiliser. En juin 2007, Amazon revendiquait plus de 330 000 développeurs ayant souscrit pour l'utilisation des Amazon Web Services.</p>
    <p>Les offres Amazon Web Services sont accessibles en HTTP, sur architecture REST et par le protocole SOAP. Tout est facturé en fonction de l'utilisation, avec la valeur exacte variant de services en services, ou selon la zone géographique d'appel.</p>
    <p><a href='http://fr.wikipedia.org/wiki/Amazon_Web_Services' target='_blank'></a></p>
    <p>Liste des produits AWS : <a href="http://aws.amazon.com/fr/products/">http://aws.amazon.com/fr/products/</a></p>

    <p>Quel service d'Amazon pourrait-on utiliser pour créer notre serveur Ubuntu 16.04 ?</p>
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
<h3>Connexion a un serveur de rebond</h3>
    <p>Vous allez utiliser le cloud public d'OVH pour créer votre machine virtuelle.</p>
    <p>Pour cela, il faut vous connecter en SSH sur un serveur de rebond qui contient tous les acces et outils necessaires.</p>

    <p>Connectez vous au rebond (mot de passe <i>moutarde</i>) :</p>
    <pre>
ssh jump@jump.arnaudmorin.fr
    </pre>
    <p>Bravo, vous voilà maintenant prêt à piloter OpenStack au travers des lignes de commande !</p>

<h3>HTTP, just for fun!</h3>
    <p>Pour le fun, nous allons utiliser OpenStack en effectuant quelques requetes HTTP en ligne de commande (l'objectif caché est de vous faire comprendre que le cloud c'est avant tout des API, et que ces API sont la base de toutes les actions effectuées dans le cloud).
    <p>Avez vous compris et êtes vous convaincus ?</p>
    <input id="compris" type="text" value=""/>
    <input id="compris_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('compris');"/>

    <p>Pour cela nous allons utiliser la commande <i>curl</i></p>
    <p>Pour utiliser les differents services du cloud, il vous faut un token. On peut récuperer ce token en effectuant une requête HTTP comme la suivante :</p>
    <pre>
# Get token 
curl -s -X POST https://auth.cloud.ovh.net/v2.0/tokens \
 -H "Content-Type: application/json" \
 -d '{"auth": {"tenantName": "'$OS_TENANT_NAME'", "passwordCredentials": {"username": "'$OS_USERNAME'", "password": "'$OS_PASSWORD'"}}}'\
 | j
    </pre>

    <p>Notez que vous n'avez nullement eu besoin de donner un login ou un mot de passe pour effectuer votre première requête HTTP au cloud. En effet, ce serveur de rebond dispose d'un accès au cloud OpenStack d'OVH grâce a une configuration et un environnement pré-installé : vous n'avez donc pas à vous soucier des logins et mots de passe.</p>
    <p>Essayez de trouver le token dans le crachat json</p>
    <p>Puis exportez le dans une variable pour le reutiliser plus tard dans votre shell :</p>
    <pre>
# Export the token so that we can reuse it as a variable
export TOKEN=the_long_uuid_token
    </pre>

    <p>Allez, grace a votre token, listez les flavors :</p>
    <pre>
# List flavors
curl -s -H "X-Auth-Token: $TOKEN" \
 https://compute.gra1.cloud.ovh.net/v2/$OS_TENANT_ID/flavors \
 | j
    </pre>

    <p>Combien y-a-t-il de flavors ?</p>
    <input id="flavors" type="text" value=""/>
    <input id="flavors_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('flavors');"/>

    <p>De même, listez les images :</p>
    <pre>
# List images
curl -s -H "X-Auth-Token: $TOKEN" \
  https://image.compute.gra1.cloud.ovh.net/v2/images \
  | j
    </pre>

    <p>Combien y-a-t-il d'image ?</p>
    <input id="images" type="text" value=""/>
    <input id="images_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('images');"/>

    <p>Puis les servers :</p>
    <pre>
# List servers
curl -s -H "X-Auth-Token: $TOKEN" \
  https://compute.gra1.cloud.ovh.net/v2/$OS_TENANT_ID/servers \
  | j
    </pre>

<h3>CLI openstack: less fuck, more fun</h3>
    <p>Manipuler OpenStack au travers de requêtes HTTP, c'est bien, mais c'est surtout bien pour coder des robots, des scripts ou des logiciels.</p>
    <p>Pour le debuggage ou la manipulation des objets d'OpenStack, le plus simple est d'utiliser les clients officiels.</p>
    <p>Ces clients sont en fait une collection de scripts <i>python</i> qui font les mêmes requêtes HTTP que celles que vous avez fait dans les parties précédentes.</p>
    <p>Un des clients python s'appelle <i>openstack</i> et est déja installé sur votre machine.</p>
    <p>Testez l'usage du client : </p>
    <pre>
openstack help
    </pre>

    <p>Quelle commande allez vous utiliser pour lister les servers ?</p>
    <input id="servers" type="text" value=""/>
    <input id="servers_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('servers');"/>

    <p>Utilisez la même commande en y ajoutant l'option <i>--debug</i> pour voir les requêtes HTTP que fait le script python</p>
    <p>Combien de requêtes HTTP le client fait-il pour vous ?</p>
    <input id="debug" type="text" value=""/>
    <input id="debug_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('debug');"/>

    <p>Essayez d'expliquer les requêtes que vous voyez (il peut y avoir des choses bizarres) :</p>
    <textarea></textarea>

<!------------------------------------------------------
Création d'une machine
------------------------------------------------------->
<h2>Création de la machine virtuelle (instance)</h2>
    <p>Listez les images :</p>
    <pre>
openstack image list
    </pre>

    <p>Listez les flavors :</p>
    <pre>
openstack flavor list
    </pre>

    <p>Listez les clef SSH :</p>
    <pre>
openstack keypair list
    </pre>

    <p>Listez les reseaux :</p>
    <pre>
openstack network list
    </pre>

    <p>Enfin bootez une image Ubuntu 16.04 de type vps-ssd-1 :</p>
    <pre>
# ok, bien, boot d'une VM
openstack server create \
 --key-name isen_nopass \
 --nic net-id=Ext-Net \
 --image 'Ubuntu 16.04' \
 --flavor vps-ssd-1 \
 --wait \
 le-nom-de-la-machine-que-vous-voulez
    </pre>

    <p>Pour voir le statut de votre machine :</p>
    <pre>
openstack server show le-nom-de-la-machine-que-vous-voulez
    </pre>

    <p>Vous pouvez aussi afficher les logs et la console avec les commandes suivantes :</p>
    <pre>
# Show console log and url
openstack console log show le-nom-de-la-machine-que-vous-voulez
openstack console url show le-nom-de-la-machine-que-vous-voulez
    </pre>
    <p>Mais vous ne pourrez pas vous connecter avec la console par ce biais car vous ne connaissez pas le mot de passe ! (et pour cause, il n'existe pas)</p>


<!------------------------------------------------------
Connexion à la machine
------------------------------------------------------->
<h2>Votre machine</h2>
    <p>Vous disposez donc maintenant d'une machine Ubuntu 16.04, vous allez l'administrer grâce à une console à distance</p>
    
    <p>Quel protocole va-t-on utiliser pour s'y connecter ?</p>
    <input id="connexion" type="text" value=""/>
    <input id="connexion_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('connexion');"/>

    <p>Généralement, avec ce protocol pour se connecter a une machine, il vous faut :</p>
    <ul>
        <li>Soit un login et un password</li>
        <li>Soit un login et une clef privée RSA</li>
    </ul>

    <p>Selon vous, quelle technique va-t-on utiliser pour se connecter a la machine (clef ou password) ?</p>
    <input id="sshlogin" type="text" value=""/>
    <input id="sshlogin_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('sshlogin');"/>

    <p>Où est enregistrée la clef privée sur le serveur de rebond ?</p>
    <input id="keylocation" type="text" value=""/>
    <input id="keylocation_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('keylocation');"/>

    <p>Les images cloud Ubuntu 16.04 utilisent toutes le même login: <i>ubuntu</i></p>
    <p>Maintenant que vous avez les informations nécessaires, allez-y, connectez vous à votre machine !</p>

    <p>De combien d'adresses IPv4 dispose votre machine ?</p>
    <input id="ip" type="text" value=""/>
    <input id="ip_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('ip');"/>

    <p>Notez la ou les adresses IP que vous avez trouvé : </p>
    <textarea></textarea>

    <p>Installez le paquet <i>python-simplejson</i>, qui nous servira plus tard :</p>
    <pre>
sudo apt-get install python-simplejson
    </pre>

<!------------------------------------------------------
Ansible
------------------------------------------------------->
<h2>Ansible</h2>
<h3>Starting blocks</h3>
    <p>Ansible est un outil d'automatisation facilitant le déploiement, les gestion et la configuration des serveurs.</p>
    <p>D'autres outils similaires éxistent : puppet, chef, saltstack, cfengine, etc.</p>

    <p>Avant de procéder a l'installation d'un serveur de VoIP sur votre machine, prenez en main ansible et effectuez quelques essais.</p>

    <p>Pour cela, depuis la machine de rebond, essayez cette commande en replaçant <i>ip_address</i> par l'adresse ip de votre machine (mais gardez bien la virgule après l'adresse IP !)</p>
    <pre>
ansible all -i ip_address, -m ping
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
ansible all -i ip_address, -m shell -a 'echo hello from $(hostname)'
    </pre>
    
<h3>Playbooks</h3>
    <p>Les playbooks sont des collections d'instructions que vous pouvez donner a ansible pour qu'il les execute sur les serveurs distants.</p>
    <p>Les playbooks peuvent être utilisés pour installer, configurer et maintenir la configuration du système distant.</p>

    <p>Essayez d'exécuter le playbook suivant sur votre serveur :</p>
    <pre>
ansible-playbook -i ip_address, ansible/wtf.yaml
    </pre>

    <p>Essayez la même chose mais en ajoutant de la verbosité :</p>
    <pre>
ansible-playbook -i ip_address, -vvv ansible/wtf.yaml
    </pre>

    <p>Ouvrez le playbook wtf.yaml et essayez de comprendre un peu le fonctionnement.</p>
    <p>Quel rôle est appliqué au serveur ?</p>
    <input id="wtfrole" type="text" value=""/>
    <input id="wtfrole_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('wtfrole');"/>

    <p>Que fait le rôle ?</p>
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
    <p>Nous allons donc installer <i>Asterisk</i> avec <i>Ansible</i> ! Pour cela, il vous suffit d'appliquer le playbook suivant à votre serveur :</p>
    <pre>
ansible-playbook -i ip_address, ansible/asterisk.yaml
    </pre>

    <p>Expliquer ce que fait le playbook :</p>
    <textarea></textarea>

    <p>Vérifiez maintenant qu'asterisk est bien installé et démarré sur votre serveur</p>
    <p>Quelle commande vous permet de vérifier que le service asterisk est démarré ?</p>
    <input id="asterisk2" type="text" value=""/>
    <input id="asterisk2_btn" type="button" value="Vérifier!" class="btn btn-default" onclick="javascript:testReponse('asterisk2');"/>

    <p>Quelle commande utilisez-vous pour vérifier qu'Asterisk écoute bien sur les ports 5060 ?</p>
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
    <p><img src="images/linphone2.png"/></p>
    
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

<!--

Mot de passe par défaut administrateur du téléphone : 22222
Reset du télépone possible en appuyant simultanément sur 1 et # au boot du téléphone

-->
<?php
require_once('footer.php');
?>

