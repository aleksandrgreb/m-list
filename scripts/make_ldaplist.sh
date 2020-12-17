#!/usr/bin/bash

rm -f /tmp/list_ldap-luxms.com && \
/usr/bin/su -l zimbra -c '/opt/zimbra/bin/zmprov -l gaa -v luxms.com > /tmp/list_ldap-luxms.com' && \
/usr/bin/su -l zimbra -c '/opt/zimbra/bin/zmprov -l gaa -v rcslabs.ru >> /tmp/list_ldap-luxms.com' && \
/usr/bin/su -l zimbra -c '/opt/zimbra/bin/zmprov -l gaa -v yasp.ru >> /tmp/list_ldap-luxms.com' && \
/usr/bin/su -l zimbra -c '/opt/zimbra/bin/zmprov -l gaa -v luxmsbi.com >> /tmp/list_ldap-luxms.com' && \
/usr/bin/su -l zimbra -c '/opt/zimbra/bin/zmprov -l gaa -v luxfs.ru >> /tmp/list_ldap-luxms.com' && \
/usr/bin/su -l zimbra -c '/opt/zimbra/bin/zmprov -l gaa -v v2chat.com >> /tmp/list_ldap-luxms.com' && \
/usr/bin/su -l zimbra -c '/opt/zimbra/bin/zmprov -l gaa -v v2chat.ru >> /tmp/list_ldap-luxms.com' && \
/usr/bin/su -l zimbra -c '/opt/zimbra/bin/zmprov -l gaa -v visualcontroltool.com >> /tmp/list_ldap-luxms.com' && \
/usr/bin/su -l zimbra -c '/opt/zimbra/bin/zmprov -l gaa -v vizqt.com >> /tmp/list_ldap-luxms.com' && \
/usr/bin/su -l zimbra -c '/opt/zimbra/bin/zmprov -l gaa -v luxms.spb.ru >> /tmp/list_ldap-luxms.com' && \
/usr/bin/chown poststat.poststat /tmp/list_ldap-luxms.com

