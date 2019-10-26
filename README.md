# full_auto_abo_telegram
Ein voll Automatisiertes Abo- System für mehrere Telegram Kanäle.

Support On href="https://discord.gg/jsvX9pz"&gt;Discordgt;

### 1. BotFather erstellen.

Als ersten Schritt gebe ich im Suchfeld des Telegram-Clients "BotFather" ein und beginne einen Chat mit ihm.

Mit /newbot wird ein neuer Bot erstellt. Anschließend müssen im Dialog der Botname und der Benutzername angegeben werden.

Der BotFather antwortet, wie oben zusehen, mit einem Token für die HTTP-API (HTTP-Token).

### 1.2 Parameter
gehe auf ‘https://my.telegram.org/apps’ und loge dich mit deiner Handynummer ein, klicke dann auf "API development tools" hier siehst du deine basic api_id und api_hash parameter für die TelegramApiServer Konfiguration..

### Installation von full_auto_abo_telegram und TelegramApiServer

cd /var/www/html

git clone https://github.com/Micha854/full_auto_abo_telegram.git

dann lade folgendes in den admin/ ordner im unterverzeichnis von full_auto_abo_telegram

git clone https://github.com/xtrime-ru/TelegramApiServer.git

### Konfiguration von TelegramApiServer

installiere Composer // google ist dein freund ;)

anschließend die.env.example in .env umbenennen und die SERVER_ADDRESSE sowie die Parameter einfügen aus Schritt 1.2

anschließend  im Ordner TelegramApiServer
folgendes command ausführen zum Starten der Api

php server.php

danach auf mit a bestätigen für automatisch

dann wähle b für Bot

jetzt musst du dein bot Namen und HTTP TOKEN vom BotFather einfügen

### PayPal API einrichten
Logge dich in deinen PayPal Account ein! Danach öffne im selben Browser-Tab folgende URL:

https://www.paypal.com/businessmanage/credentials/apiAccess

Wähle die Option "NVP/SOAP API integration" und erstelle API Username, Password & Signature (diese Daten dann in die config.php)

### Config
folgende Dateien müssen angepasst werden:

* config_example.php		--> config.php
* ggf. noch den admin/ per .htaccess schützen !

Erstelle einen stündlichen Cronjob für YOURURL.COM/admin/_cron.php (hierbei werden abgelaufene Abos-User aus dem Kanal und der Datenbank entfernt)

### Zugriff auf Rocketmap via .htpasswd

Wenn User Zugriff auf die Rocketmap haben dürfen muss folgendes (in Ubuntu) konfiguriert werden. 
erstelle eine neue Datei "rocketmap.conf" in /etc/apache2/sites-available/

```
ServerName YOURDOMAIN.de
ProxyPass /go/ http://YOURIP:46516/
ProxyPassReverse /go/ http://127.0.0.1:46516/

    <Proxy *>
        Order deny,allow
        Allow from all
        Authtype Basic
        Authname "Password Required"
        AuthUserFile /var/www/vhosts/YOURDOMAIN.de/httpdocs/.htpasswd
        Require valid-user
    </Proxy>

RewriteCond %{HTTP_HOST} !^YOURDOMAIN\.de/go/$ [NC]
RewriteRule ^/go/$ http://%{HTTP_HOST}/go/ [L,R=301]
```

eingebunden wird die configuration in 000-default.conf mit der zeile "Include sites-available/rocketmap.conf
"

### bei Verwendung von PMSF als MAP

Es müssen manualdb (PMSF) und unsere 3 Tabellen (siehe unten) zusammengeführt werden!
Folgende Anpassung muss außerdem die Spalte "users" bekommen

```
ALTER TABLE `users`
  ADD UNIQUE KEY `user` (`user`);
```

### SQL Telegram Chanel
Name der Tabelle muss in --> config_example.php angepasst werden!!


```
CREATE TABLE `abos` (
  `id` int(11) NOT NULL,
  `buyerName` varchar(155) NOT NULL,
  `buyerEmail` varchar(255) NOT NULL,
  `Amount` varchar(5) NOT NULL,
  `TelegramUser` varchar(155) NOT NULL,
  `channels` varchar(55) NOT NULL,
  `pass` varchar(8) NOT NULL,
  `paydate` datetime NOT NULL,
  `endtime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indizes für die Tabelle `abos`
--
ALTER TABLE `abos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `TelegramUser` (`TelegramUser`);

--
-- AUTO_INCREMENT für Tabelle `abos`
--
ALTER TABLE `abos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
```
### SQL products (Produkte müssen angepasst werden)

```
CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `months` varchar(2) NOT NULL,
  `item_number` varchar(6) NOT NULL,
  `item_price` varchar(5) NOT NULL,
  `abo_days` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `products`
--

INSERT INTO `products` (`id`, `months`, `item_number`, `item_price`, `abo_days`) VALUES
(1, '1', '10000', '0.87', '30'),
(2, '3', '30000', '1.90', '90'),
(3, '6', '60000', '3.44', '180');

--
-- Indizes für die Tabelle `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für Tabelle `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;
```
### SQL channels

```
CREATE TABLE `channels` (
  `id` int(11) NOT NULL,
  `name` varchar(155) NOT NULL,
  `url` varchar(155) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `channels`
--

INSERT INTO `channels` (`id`, `name`, `url`) VALUES
(1, 'Kanal 1', 'https://t.me/joinchat/XXXXXXXzl0uIG6rC2xuqjQ'),
(2, 'Kanal 2', 'https://t.me/Kanal2'),
(3, 'Kanal 3', 'https://t.me/joinchat/XXXXXXgy6i4Y6WxnEQQNqw'),
(4, 'Kanal 4', 'https://t.me/Kanal4');

--
-- Indizes für die Tabelle `channels`
--
ALTER TABLE `channels`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für Tabelle `channels`
--
ALTER TABLE `channels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;
```
