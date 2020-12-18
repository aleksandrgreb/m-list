Настройка белых и черных списков в Zimbra:

Адреса занесённые в "чёрный список" блокируются на уровне SMTP протокола (настаивается в конфигурации postfix).
Адреса занесённые в "белый список" не должны блокироваться антиспамерскими фильтрами, причём есть два варианта "белых списков":
исключения для фильтров (drbl/dnsbl blocklist, greylisting, SPF, DKIM), то есть не блокируются на этапе SMTP соединения,
исключения для фильтров по контенту (spamassassin, razor, pyzor, clamav).

Настройка белых и чёрных списков в postfix:

Редактируем файл /opt/zimbra/conf/zmconfigd/smtpd_recipient_restrictions.cf
Добавляем после параметров permit_sasl_authenticated и permit_mynetworks , но до параметра reject_rbl_client, следующие строки

check_client_access hash:/opt/zimbra/conf/postfix_rbl_override
check_sender_access hash:/opt/zimbra/conf/sender_access_list

Параметр check_client_access означает проверку из списка IP адреса SMTP сервера отправителя.
Пример файла /opt/zimbra/conf/postfix_rbl_override

111.222.88.11 OK
11.22.88.11 REJECT

Параметр check_sender_access означает проверку из списка e-mail адреса или домена (который подставляется в SMTP команду MAIL FROM).
Пример файла /opt/zimbra/conf/sender_access_list

test1@yandex.ru OK
test2@gmail.com REJECT
kpp.ru OK

Далее создаём хеш файлы списков:

su - zimbra
cd /opt/zimbra/conf/
postmap postfix_rbl_override
postmap sender_access_list

Перезапускаем почтовый агент в Zimbra (при этом перезаписывается новая конфигурация в main.cf):

zmmtactl restart

Настройка белых списков (исключений) в spamassassin.
Добавляем строки в файл /opt/zimbra/conf/sa/sauser.cf

whitelist_from домен

Пример:

whitelist_from rzdp.ru
whitelist_from ural.rt.ru

Перезапускаем Amavis-D:

zmamavisdctl restart

Для управления белыми и черными списками, для вышеописанной конфигурации posfix,
написан web-интерфейс для почтового сервера zimbra.

Инсталяция Web-интерфейса:





