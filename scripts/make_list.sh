#!/usr/bin/bash

/usr/bin/cp -f /var/log/maillog /tmp/ && \
/usr/bin/chown poststat.poststat /tmp/maillog

