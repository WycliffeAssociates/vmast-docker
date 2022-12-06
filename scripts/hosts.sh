#!/bin/sh

VMAST=$(cat /etc/hosts | grep $MAIN_HOST)

ADDR=$(ping -c1 web | sed -nE 's/^PING[^(]+\(([^)]+)\).*/\1/p')

[[ -z "$VMAST" ]] && echo "$ADDR      $MAIN_HOST" >> /etc/hosts